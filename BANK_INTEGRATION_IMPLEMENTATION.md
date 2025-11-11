# Implémentation Pratique - Intégration Bancaire Temps Réel

## 🚀 Guide d'Implémentation Code par Code

Ce document contient le code exact à implémenter pour chaque étape.

---

## ÉTAPE 1 : Migration de Base de Données

### 1.1 Créer la Migration

```bash
php artisan make:migration add_bank_transfer_fields_to_transactions_table
```

### 1.2 Code de la Migration

**Fichier : `database/migrations/YYYY_MM_DD_HHMMSS_add_bank_transfer_fields_to_transactions_table.php`**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('bank_transfer_id')->nullable()->after('status');
            $table->timestamp('bank_confirmed_at')->nullable()->after('bank_transfer_id');
            $table->timestamp('webhook_received_at')->nullable()->after('bank_confirmed_at');
            $table->text('webhook_data')->nullable()->after('webhook_received_at');
        });

        // Créer la table bank_transfers (optionnelle mais recommandée)
        Schema::create('bank_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->string('bank_reference')->nullable();
            $table->string('bank_transfer_id')->unique();
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->timestamp('initiated_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->text('failure_reason')->nullable();
            $table->json('webhook_data')->nullable();
            $table->timestamps();

            $table->index('bank_transfer_id');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bank_transfers');
        
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'bank_transfer_id',
                'bank_confirmed_at',
                'webhook_received_at',
                'webhook_data'
            ]);
        });
    }
};
```

### 1.3 Exécuter la Migration

```bash
php artisan migrate
```

---

## ÉTAPE 2 : Configuration

### 2.1 Mettre à Jour config/services.php

**Fichier : `config/services.php`**

Ajouter après la section PayPal :

```php
'bank' => [
    'provider' => env('BANK_PROVIDER', 'stripe'), // 'stripe', 'paypal', 'm2t', 'direct_api'
    'api_key' => env('BANK_API_KEY'),
    'api_secret' => env('BANK_API_SECRET'),
    'webhook_secret' => env('BANK_WEBHOOK_SECRET'),
    'test_mode' => env('BANK_TEST_MODE', true),
    'webhook_url' => env('APP_URL') . '/api/bank/webhook',
    
    // Pour API bancaire directe (si applicable)
    'direct_api' => [
        'url' => env('BANK_API_URL'),
        'account_number' => env('BANK_ACCOUNT_NUMBER'),
        'account_iban' => env('BANK_ACCOUNT_IBAN'),
        'username' => env('BANK_API_USERNAME'),
        'password' => env('BANK_API_PASSWORD'),
    ],
],
```

### 2.2 Ajouter les Variables dans .env

```env
# Configuration Bancaire
BANK_PROVIDER=stripe
BANK_API_KEY=sk_test_...
BANK_API_SECRET=sk_test_...
BANK_WEBHOOK_SECRET=whsec_...
BANK_TEST_MODE=true
```

---

## ÉTAPE 3 : Créer le Modèle BankTransfer

### 3.1 Créer le Modèle

```bash
php artisan make:model BankTransfer
```

### 3.2 Code du Modèle

**Fichier : `app/Models/BankTransfer.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankTransfer extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'transaction_id',
        'bank_reference',
        'bank_transfer_id',
        'status',
        'initiated_at',
        'confirmed_at',
        'failure_reason',
        'webhook_data',
    ];

    protected $casts = [
        'initiated_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'webhook_data' => 'array',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function isProcessing(): bool
    {
        return $this->status === self::STATUS_PROCESSING;
    }
}
```

---

## ÉTAPE 4 : Créer le Service BankTransferService

### 4.1 Créer le Service

```bash
php artisan make:service BankTransferService
```

### 4.2 Code du Service

**Fichier : `app/Services/BankTransferService.php`**

```php
<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\BankTransfer;
use App\Models\Agency;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BankTransferService
{
    protected $provider;
    protected $apiKey;
    protected $apiSecret;
    protected $testMode;

    public function __construct()
    {
        $this->provider = config('services.bank.provider');
        $this->apiKey = config('services.bank.api_key');
        $this->apiSecret = config('services.bank.api_secret');
        $this->testMode = config('services.bank.test_mode');
    }

    /**
     * Initie un virement bancaire
     */
    public function initiateTransfer(Transaction $transaction): array
    {
        try {
            $agency = $transaction->agency;
            $metadata = $transaction->metadata ?? [];

            // Préparer les données du virement
            $transferData = [
                'amount' => $transaction->amount * 100, // Convertir en centimes
                'currency' => 'MAD',
                'destination' => [
                    'type' => 'bank_account',
                    'bank_name' => $metadata['bank_name'] ?? '',
                    'account_number' => $metadata['rib_number'] ?? '',
                    'account_holder' => $metadata['account_holder'] ?? '',
                ],
                'metadata' => [
                    'transaction_id' => $transaction->id,
                    'agency_id' => $agency->id,
                    'agency_name' => $agency->agency_name,
                ],
            ];

            // Appeler l'API selon le provider
            $response = $this->callBankAPI('initiate', $transferData);

            if ($response['success']) {
                // Créer l'enregistrement BankTransfer
                $bankTransfer = BankTransfer::create([
                    'transaction_id' => $transaction->id,
                    'bank_transfer_id' => $response['transfer_id'],
                    'bank_reference' => $response['reference'] ?? null,
                    'status' => BankTransfer::STATUS_PROCESSING,
                    'initiated_at' => now(),
                ]);

                // Mettre à jour la transaction
                $transaction->update([
                    'bank_transfer_id' => $response['transfer_id'],
                    'status' => Transaction::STATUS_PENDING, // En attente de confirmation
                ]);

                Log::info('Bank transfer initiated', [
                    'transaction_id' => $transaction->id,
                    'bank_transfer_id' => $response['transfer_id'],
                ]);

                return [
                    'success' => true,
                    'bank_transfer_id' => $response['transfer_id'],
                    'bank_transfer' => $bankTransfer,
                ];
            }

            throw new \Exception($response['error'] ?? 'Failed to initiate transfer');

        } catch (\Exception $e) {
            Log::error('Bank transfer initiation failed', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Traite un webhook de la banque
     */
    public function handleWebhook(array $payload): bool
    {
        try {
            // Vérifier la signature du webhook
            if (!$this->verifyWebhookSignature($payload)) {
                Log::warning('Invalid webhook signature', ['payload' => $payload]);
                return false;
            }

            $eventType = $payload['type'] ?? $payload['event'] ?? null;
            $transferId = $payload['transfer_id'] ?? $payload['id'] ?? null;

            if (!$transferId) {
                Log::warning('Webhook missing transfer_id', ['payload' => $payload]);
                return false;
            }

            // Trouver le BankTransfer
            $bankTransfer = BankTransfer::where('bank_transfer_id', $transferId)->first();

            if (!$bankTransfer) {
                Log::warning('Bank transfer not found', ['transfer_id' => $transferId]);
                return false;
            }

            $transaction = $bankTransfer->transaction;

            // Traiter selon le type d'événement
            switch ($eventType) {
                case 'transfer.completed':
                case 'payout.paid':
                case 'transfer.succeeded':
                    return $this->confirmTransfer($transaction, $bankTransfer, $payload);

                case 'transfer.failed':
                case 'payout.failed':
                case 'transfer.cancelled':
                    return $this->handleTransferFailure($transaction, $bankTransfer, $payload);

                default:
                    Log::info('Unhandled webhook event', [
                        'event_type' => $eventType,
                        'transfer_id' => $transferId,
                    ]);
                    return false;
            }

        } catch (\Exception $e) {
            Log::error('Webhook processing failed', [
                'error' => $e->getMessage(),
                'payload' => $payload,
            ]);
            return false;
        }
    }

    /**
     * Confirme un virement
     */
    protected function confirmTransfer(Transaction $transaction, BankTransfer $bankTransfer, array $webhookData): bool
    {
        DB::beginTransaction();

        try {
            // Mettre à jour le BankTransfer
            $bankTransfer->update([
                'status' => BankTransfer::STATUS_COMPLETED,
                'confirmed_at' => now(),
                'webhook_data' => $webhookData,
            ]);

            // Mettre à jour la Transaction
            $transaction->update([
                'status' => Transaction::STATUS_COMPLETED,
                'bank_confirmed_at' => now(),
                'webhook_received_at' => now(),
                'webhook_data' => json_encode($webhookData),
            ]);

            // Mettre à jour le solde de l'agence
            $agency = $transaction->agency;
            $agency->pending_earnings = max(0, ($agency->pending_earnings ?? 0) - $transaction->amount);
            $agency->last_payout_at = now();
            $agency->save();

            DB::commit();

            // Envoyer une notification à l'agence
            \App\Services\PaymentNotificationService::notifyAgencyPayoutConfirmed($transaction);

            Log::info('Bank transfer confirmed', [
                'transaction_id' => $transaction->id,
                'bank_transfer_id' => $bankTransfer->bank_transfer_id,
            ]);

            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transfer confirmation failed', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Gère l'échec d'un virement
     */
    protected function handleTransferFailure(Transaction $transaction, BankTransfer $bankTransfer, array $webhookData): bool
    {
        DB::beginTransaction();

        try {
            $failureReason = $webhookData['failure_reason'] ?? $webhookData['message'] ?? 'Unknown error';

            // Mettre à jour le BankTransfer
            $bankTransfer->update([
                'status' => BankTransfer::STATUS_FAILED,
                'failure_reason' => $failureReason,
                'webhook_data' => $webhookData,
            ]);

            // Mettre à jour la Transaction
            $transaction->update([
                'status' => Transaction::STATUS_FAILED,
                'webhook_received_at' => now(),
                'webhook_data' => json_encode($webhookData),
            ]);

            // Restaurer le solde de l'agence
            $agency = $transaction->agency;
            $agency->balance = ($agency->balance ?? 0) + $transaction->amount;
            $agency->pending_earnings = max(0, ($agency->pending_earnings ?? 0) - $transaction->amount);
            $agency->save();

            DB::commit();

            // Envoyer une notification à l'agence
            \App\Services\PaymentNotificationService::notifyAgencyPayoutFailed($transaction, $failureReason);

            Log::info('Bank transfer failed', [
                'transaction_id' => $transaction->id,
                'reason' => $failureReason,
            ]);

            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transfer failure handling failed', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Vérifie le statut d'un virement
     */
    public function checkTransferStatus(string $transferId): array
    {
        try {
            $response = $this->callBankAPI('status', ['transfer_id' => $transferId]);

            return [
                'success' => true,
                'status' => $response['status'] ?? 'unknown',
                'data' => $response,
            ];

        } catch (\Exception $e) {
            Log::error('Transfer status check failed', [
                'transfer_id' => $transferId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Appelle l'API bancaire selon le provider
     */
    protected function callBankAPI(string $action, array $data): array
    {
        // Exemple pour Stripe Connect Payouts
        if ($this->provider === 'stripe') {
            return $this->callStripeAPI($action, $data);
        }

        // Exemple pour PayPal Payouts
        if ($this->provider === 'paypal') {
            return $this->callPayPalAPI($action, $data);
        }

        // Pour API bancaire directe
        if ($this->provider === 'direct_api') {
            return $this->callDirectBankAPI($action, $data);
        }

        throw new \Exception("Unsupported bank provider: {$this->provider}");
    }

    /**
     * Appelle l'API Stripe
     */
    protected function callStripeAPI(string $action, array $data): array
    {
        // Implémentation Stripe Connect Payouts
        // Note: Cette implémentation nécessite le package stripe/stripe-php
        
        try {
            \Stripe\Stripe::setApiKey($this->apiSecret);

            if ($action === 'initiate') {
                // Créer un payout Stripe
                $payout = \Stripe\Payout::create([
                    'amount' => $data['amount'],
                    'currency' => strtolower($data['currency']),
                    'destination' => $data['destination']['account_number'],
                    'metadata' => $data['metadata'],
                ]);

                return [
                    'success' => true,
                    'transfer_id' => $payout->id,
                    'reference' => $payout->id,
                ];
            }

            if ($action === 'status') {
                $payout = \Stripe\Payout::retrieve($data['transfer_id']);

                return [
                    'success' => true,
                    'status' => $payout->status,
                    'data' => $payout->toArray(),
                ];
            }

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Appelle l'API PayPal
     */
    protected function callPayPalAPI(string $action, array $data): array
    {
        // Implémentation PayPal Payouts
        // Note: Nécessite une intégration PayPal Payouts API
        
        $baseUrl = $this->testMode 
            ? 'https://api.sandbox.paypal.com'
            : 'https://api.paypal.com';

        try {
            // Obtenir un token d'accès
            $tokenResponse = Http::asForm()
                ->withBasicAuth($this->apiKey, $this->apiSecret)
                ->post("{$baseUrl}/v1/oauth2/token", [
                    'grant_type' => 'client_credentials',
                ]);

            $accessToken = $tokenResponse->json()['access_token'];

            if ($action === 'initiate') {
                $payoutResponse = Http::withToken($accessToken)
                    ->post("{$baseUrl}/v1/payments/payouts", [
                        'sender_batch_header' => [
                            'sender_batch_id' => 'batch_' . time(),
                            'email_subject' => 'Payout from CarAgency',
                        ],
                        'items' => [
                            [
                                'recipient_type' => 'EMAIL',
                                'amount' => [
                                    'value' => $data['amount'] / 100,
                                    'currency' => $data['currency'],
                                ],
                                'receiver' => $data['destination']['account_number'],
                                'note' => 'Payout for transaction #' . $data['metadata']['transaction_id'],
                            ],
                        ],
                    ]);

                if ($payoutResponse->successful()) {
                    $payout = $payoutResponse->json();
                    return [
                        'success' => true,
                        'transfer_id' => $payout['batch_header']['payout_batch_id'],
                        'reference' => $payout['batch_header']['payout_batch_id'],
                    ];
                }
            }

            // Implémentation pour 'status'...

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Appelle l'API bancaire directe
     */
    protected function callDirectBankAPI(string $action, array $data): array
    {
        $apiUrl = config('services.bank.direct_api.url');
        $username = config('services.bank.direct_api.username');
        $password = config('services.bank.direct_api.password');

        try {
            // Authentification
            $authResponse = Http::withBasicAuth($username, $password)
                ->post("{$apiUrl}/auth/token");

            $token = $authResponse->json()['token'];

            if ($action === 'initiate') {
                $transferResponse = Http::withToken($token)
                    ->post("{$apiUrl}/transfers", $data);

                if ($transferResponse->successful()) {
                    $transfer = $transferResponse->json();
                    return [
                        'success' => true,
                        'transfer_id' => $transfer['id'],
                        'reference' => $transfer['reference'],
                    ];
                }
            }

            // Implémentation pour 'status'...

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Vérifie la signature du webhook
     */
    protected function verifyWebhookSignature(array $payload): bool
    {
        $webhookSecret = config('services.bank.webhook_secret');

        if (!$webhookSecret) {
            Log::warning('Webhook secret not configured');
            return false;
        }

        // Implémentation selon le provider
        if ($this->provider === 'stripe') {
            // Vérification signature Stripe
            $signature = request()->header('Stripe-Signature');
            try {
                \Stripe\Webhook::constructEvent(
                    request()->getContent(),
                    $signature,
                    $webhookSecret
                );
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }

        // Pour d'autres providers, implémenter la vérification appropriée
        return true; // Temporaire, à implémenter selon le provider
    }
}
```

---

## ÉTAPE 5 : Créer le Contrôleur Webhook

### 5.1 Créer le Contrôleur

```bash
php artisan make:controller BankWebhookController
```

### 5.2 Code du Contrôleur

**Fichier : `app/Http/Controllers/BankWebhookController.php`**

```php
<?php

namespace App\Http\Controllers;

use App\Services\BankTransferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BankWebhookController extends Controller
{
    protected $bankTransferService;

    public function __construct(BankTransferService $bankTransferService)
    {
        $this->bankTransferService = $bankTransferService;
    }

    /**
     * Traite les webhooks de la banque
     */
    public function handle(Request $request)
    {
        try {
            // Logger la requête entrante
            Log::info('Bank webhook received', [
                'headers' => $request->headers->all(),
                'payload' => $request->all(),
            ]);

            // Récupérer le payload
            $payload = $request->all();

            // Traiter le webhook
            $success = $this->bankTransferService->handleWebhook($payload);

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Webhook processed successfully',
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Webhook processing failed',
            ], 400);

        } catch (\Exception $e) {
            Log::error('Webhook handler error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
            ], 500);
        }
    }
}
```

---

## ÉTAPE 6 : Ajouter la Route Webhook

**Fichier : `routes/web.php`**

Ajouter après les autres webhooks (ligne ~530) :

```php
// Bank Webhook (must be outside CSRF protection)
Route::post('/api/bank/webhook', [App\Http\Controllers\BankWebhookController::class, 'handle'])
    ->name('bank.webhook')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
```

---

## ÉTAPE 7 : Mettre à Jour PaymentRequestController

**Fichier : `app/Http/Controllers/Admin/PaymentRequestController.php`**

Modifier la méthode `approve()` :

```php
public function approve(Request $request, $id)
{
    $transaction = Transaction::with('agency')
        ->where('id', $id)
        ->where('type', Transaction::TYPE_WITHDRAWAL_REQUEST)
        ->where('status', Transaction::STATUS_PENDING)
        ->first();

    if (!$transaction) {
        return response()->json(['success' => false, 'message' => 'Demande non trouvée'], 404);
    }

    $request->validate([
        'reference' => 'nullable|string|max:100',
        'notes' => 'nullable|string|max:500',
    ]);

    DB::beginTransaction();

    try {
        $agency = $transaction->agency()->lockForUpdate()->first();

        $metadata = $transaction->metadata ?? [];
        $metadata['approved_at'] = now()->toISOString();
        $metadata['approved_by'] = auth()->id();
        $metadata['approved_by_name'] = auth()->user()->name;
        if ($request->filled('reference')) {
            $metadata['transfer_reference'] = $request->reference;
        }
        if ($request->filled('notes')) {
            $metadata['admin_notes'] = $request->notes;
        }

        // Initier le virement bancaire
        $bankTransferService = new \App\Services\BankTransferService();
        $transferResult = $bankTransferService->initiateTransfer($transaction);

        if (!$transferResult['success']) {
            throw new \Exception('Échec de l\'initiation du virement: ' . ($transferResult['error'] ?? 'Unknown error'));
        }

        // Mettre à jour la transaction
        $transaction->update([
            'status' => Transaction::STATUS_PENDING, // En attente de confirmation bancaire
            'processed_at' => now(),
            'metadata' => $metadata,
            'bank_transfer_id' => $transferResult['bank_transfer_id'],
        ]);

        // Mettre à jour les pending_earnings (le solde reste déduit)
        $agency->pending_earnings = ($agency->pending_earnings ?? 0) + $transaction->amount;
        $agency->last_payout_at = now();
        $agency->save();

        DB::commit();

        // Notifier l'agence que le virement est en cours
        PaymentNotificationService::notifyAgencyPayoutProcessing($transaction);

        return response()->json([
            'success' => true,
            'message' => 'Virement initié avec succès. En attente de confirmation bancaire.',
            'bank_transfer_id' => $transferResult['bank_transfer_id'],
        ]);
    } catch (\Exception $e) {
        DB::rollBack();

        \Log::error('Payment request approval failed', [
            'transaction_id' => $transaction->id,
            'error' => $e->getMessage(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de l\'approbation: ' . $e->getMessage(),
        ], 500);
    }
}
```

---

## ÉTAPE 8 : Mettre à Jour PaymentNotificationService

**Fichier : `app/Services/PaymentNotificationService.php`**

Ajouter les nouvelles méthodes :

```php
/**
 * Notifie l'agence que le virement est en cours de traitement
 */
public static function notifyAgencyPayoutProcessing(Transaction $transaction)
{
    $agency = $transaction->agency;
    $user = $agency->user;

    \App\Models\Notification::create([
        'user_id' => $user->id,
        'type' => 'payout_processing',
        'title' => 'Virement en cours',
        'message' => "Votre demande de retrait de " . number_format($transaction->amount, 2) . " MAD est en cours de traitement. Vous recevrez une notification dès confirmation.",
        'data' => [
            'transaction_id' => $transaction->id,
            'amount' => $transaction->amount,
        ],
        'read_at' => null,
    ]);
}

/**
 * Notifie l'agence que le virement est confirmé
 */
public static function notifyAgencyPayoutConfirmed(Transaction $transaction)
{
    $agency = $transaction->agency;
    $user = $agency->user;

    \App\Models\Notification::create([
        'user_id' => $user->id,
        'type' => 'payout_confirmed',
        'title' => 'Virement confirmé',
        'message' => "Votre virement de " . number_format($transaction->amount, 2) . " MAD a été confirmé et crédité sur votre compte bancaire.",
        'data' => [
            'transaction_id' => $transaction->id,
            'amount' => $transaction->amount,
            'confirmed_at' => $transaction->bank_confirmed_at,
        ],
        'read_at' => null,
    ]);
}

/**
 * Notifie l'agence que le virement a échoué
 */
public static function notifyAgencyPayoutFailed(Transaction $transaction, string $reason)
{
    $agency = $transaction->agency;
    $user = $agency->user;

    \App\Models\Notification::create([
        'user_id' => $user->id,
        'type' => 'payout_failed',
        'title' => 'Virement échoué',
        'message' => "Le virement de " . number_format($transaction->amount, 2) . " MAD a échoué. Raison: {$reason}. Votre solde a été restauré.",
        'data' => [
            'transaction_id' => $transaction->id,
            'amount' => $transaction->amount,
            'failure_reason' => $reason,
        ],
        'read_at' => null,
    ]);
}
```

---

## ÉTAPE 9 : Mettre à Jour le Modèle Transaction

**Fichier : `app/Models/Transaction.php`**

Ajouter dans `$fillable` :

```php
protected $fillable = [
    'agency_id',
    'rental_id',
    'type',
    'amount',
    'balance_before',
    'balance_after',
    'description',
    'metadata',
    'status',
    'processed_at',
    'bank_transfer_id',        // Nouveau
    'bank_confirmed_at',        // Nouveau
    'webhook_received_at',      // Nouveau
    'webhook_data',             // Nouveau
];
```

Ajouter dans `$casts` :

```php
protected $casts = [
    'amount' => 'decimal:2',
    'balance_before' => 'decimal:2',
    'balance_after' => 'decimal:2',
    'metadata' => 'array',
    'processed_at' => 'datetime',
    'bank_confirmed_at' => 'datetime',  // Nouveau
    'webhook_received_at' => 'datetime', // Nouveau
];
```

---

## ÉTAPE 10 : Tests

### 10.1 Tester la Migration

```bash
php artisan migrate:fresh
php artisan migrate:rollback
php artisan migrate
```

### 10.2 Tester le Webhook (Manuellement)

Utiliser Postman ou curl pour simuler un webhook :

```bash
curl -X POST http://localhost:8000/api/bank/webhook \
  -H "Content-Type: application/json" \
  -d '{
    "type": "transfer.completed",
    "transfer_id": "test_transfer_123",
    "amount": 1000,
    "currency": "MAD"
  }'
```

---

## ✅ Checklist Finale

- [ ] Migration exécutée
- [ ] Modèle BankTransfer créé
- [ ] Service BankTransferService créé
- [ ] Contrôleur Webhook créé
- [ ] Route webhook ajoutée
- [ ] PaymentRequestController mis à jour
- [ ] PaymentNotificationService mis à jour
- [ ] Variables d'environnement configurées
- [ ] Tests effectués
- [ ] Webhook configuré dans le compte bancaire/PSP

---

**Note importante :** Cette implémentation est un template. Vous devrez l'adapter selon votre provider bancaire spécifique (Stripe, PayPal, API directe, etc.).

