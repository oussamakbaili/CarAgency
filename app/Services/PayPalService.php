<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Rental;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PayPalService
{
    private $clientId;
    private $clientSecret;
    private $baseUrl;
    private $isTestMode;
    private $accessToken;

    public function __construct()
    {
        $this->clientId = config('services.paypal.client_id');
        $this->clientSecret = config('services.paypal.client_secret');
        $this->isTestMode = config('services.paypal.test_mode', true);
        $this->baseUrl = $this->isTestMode
            ? 'https://api.sandbox.paypal.com'
            : 'https://api.paypal.com';
        
        // Valider que les credentials sont configurés
        if (empty($this->clientId) || empty($this->clientSecret)) {
            Log::warning('PayPal credentials not configured', [
                'client_id_set' => !empty($this->clientId),
                'client_secret_set' => !empty($this->clientSecret),
            ]);
        }
    }

    /**
     * Obtenir un access token PayPal
     */
    private function getAccessToken(): ?string
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        // Vérifier que les credentials sont configurés
        if (empty($this->clientId) || empty($this->clientSecret)) {
            Log::error('PayPal credentials not configured', [
                'client_id_set' => !empty($this->clientId),
                'client_secret_set' => !empty($this->clientSecret),
                'client_id_length' => $this->clientId ? strlen($this->clientId) : 0,
                'client_secret_length' => $this->clientSecret ? strlen($this->clientSecret) : 0,
                'base_url' => $this->baseUrl,
                'test_mode' => $this->isTestMode,
            ]);
            return null;
        }

        try {
            $tokenUrl = $this->baseUrl . '/v1/oauth2/token';
            
            Log::info('PayPal: Attempting to get access token', [
                'url' => $tokenUrl,
                'test_mode' => $this->isTestMode,
                'client_id_prefix' => substr($this->clientId, 0, 10) . '...',
            ]);

            $response = Http::timeout(30)
                ->asForm()
                ->withBasicAuth($this->clientId, $this->clientSecret)
                ->post($tokenUrl, [
                    'grant_type' => 'client_credentials',
                ]);

            if ($response->successful()) {
                $responseData = $response->json();
                if (isset($responseData['access_token'])) {
                    $this->accessToken = $responseData['access_token'];
                    Log::info('PayPal: Access token obtained successfully');
                    return $this->accessToken;
                } else {
                    Log::error('PayPal: Access token missing in response', [
                        'response' => $responseData,
                    ]);
                    return null;
                }
            }

            $errorBody = $response->body();
            $errorJson = $response->json();
            
            Log::error('PayPal access token failed', [
                'status' => $response->status(),
                'response_body' => $errorBody,
                'response_json' => $errorJson,
                'url' => $tokenUrl,
                'test_mode' => $this->isTestMode,
                'error_message' => $errorJson['error_description'] ?? $errorJson['error'] ?? 'Unknown error',
            ]);

            return null;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('PayPal: Connection error when getting access token', [
                'error' => $e->getMessage(),
                'url' => $this->baseUrl . '/v1/oauth2/token',
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('PayPal access token error', [
                'error' => $e->getMessage(),
                'error_class' => get_class($e),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    /**
     * Créer une commande PayPal
     */
    public function createOrder(array $data): array
    {
        try {
            // Vérifier que les credentials sont configurés
            if (empty($this->clientId) || empty($this->clientSecret)) {
                return [
                    'success' => false,
                    'error' => 'PayPal n\'est pas configuré. Veuillez configurer PAYPAL_CLIENT_ID et PAYPAL_CLIENT_SECRET dans votre fichier .env',
                ];
            }

            $accessToken = $this->getAccessToken();

            if (!$accessToken) {
                // Vérifier la cause spécifique de l'échec
                $errorMessage = 'Impossible d\'obtenir l\'access token PayPal.';
                
                if (empty($this->clientId) || empty($this->clientSecret)) {
                    $errorMessage .= ' Les identifiants PayPal ne sont pas configurés dans le fichier .env.';
                } else {
                    $errorMessage .= ' Vérifiez que vos identifiants PayPal (PAYPAL_CLIENT_ID et PAYPAL_CLIENT_SECRET) sont corrects et que votre compte PayPal Business est actif.';
                    $errorMessage .= ' Consultez les logs pour plus de détails.';
                }
                
                return [
                    'success' => false,
                    'error' => $errorMessage,
                ];
            }

            $amount = number_format($data['amount'], 2, '.', '');
            $currency = $data['currency'] ?? 'EUR';
            $orderId = $data['order_id'] ?? 'PAYPAL_' . uniqid();

            $orderData = [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'reference_id' => $orderId,
                        'description' => $data['description'] ?? 'Réservation de véhicule',
                        'amount' => [
                            'currency_code' => $currency,
                            'value' => $amount,
                        ],
                    ],
                ],
                'application_context' => [
                    'brand_name' => config('app.name', 'Toubcar'),
                    'locale' => 'fr-FR',
                    'landing_page' => 'BILLING',
                    'shipping_preference' => 'NO_SHIPPING',
                    'user_action' => 'PAY_NOW',
                    'return_url' => $this->getAbsoluteUrl('/paypal/success'),
                    'cancel_url' => $this->getAbsoluteUrl('/paypal/cancel'),
                ],
            ];

            $response = Http::withToken($accessToken)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($this->baseUrl . '/v2/checkout/orders', $orderData);

            if ($response->successful()) {
                $order = $response->json();
                
                $approveUrl = collect($order['links'])->firstWhere('rel', 'approve')['href'] ?? null;
                
                return [
                    'success' => true,
                    'order_id' => $order['id'],
                    'order_data' => $order,
                    'approve_url' => $approveUrl,
                    'token' => $order['id'], // L'order ID peut être utilisé comme token
                ];
            }

            Log::error('PayPal order creation failed', [
                'response' => $response->body(),
                'status' => $response->status(),
            ]);

            return [
                'success' => false,
                'error' => 'Erreur lors de la création de la commande PayPal',
                'details' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('PayPal order creation error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Obtenir les détails d'une commande PayPal
     */
    public function getOrder(string $orderId): ?array
    {
        try {
            $accessToken = $this->getAccessToken();

            if (!$accessToken) {
                return null;
            }

            $response = Http::withToken($accessToken)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->get($this->baseUrl . '/v2/checkout/orders/' . $orderId);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('PayPal get order error', [
                'error' => $e->getMessage(),
                'order_id' => $orderId,
            ]);

            return null;
        }
    }

    /**
     * Capturer un paiement PayPal
     */
    public function captureOrder(string $orderId): array
    {
        try {
            $accessToken = $this->getAccessToken();

            if (!$accessToken) {
                return [
                    'success' => false,
                    'error' => 'Impossible d\'obtenir l\'access token PayPal',
                ];
            }

            $response = Http::withToken($accessToken)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($this->baseUrl . '/v2/checkout/orders/' . $orderId . '/capture');

            if ($response->successful()) {
                $order = $response->json();
                
                $status = $order['status'] ?? null;
                $isCompleted = $status === 'COMPLETED';

                return [
                    'success' => $isCompleted,
                    'order' => $order,
                    'status' => $status,
                    'payment_id' => $order['purchase_units'][0]['payments']['captures'][0]['id'] ?? null,
                    'amount' => $order['purchase_units'][0]['payments']['captures'][0]['amount']['value'] ?? null,
                    'currency' => $order['purchase_units'][0]['payments']['captures'][0]['amount']['currency_code'] ?? null,
                ];
            }

            Log::error('PayPal capture failed', [
                'order_id' => $orderId,
                'response' => $response->body(),
            ]);

            return [
                'success' => false,
                'error' => 'Erreur lors de la capture du paiement',
                'details' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('PayPal capture error', [
                'error' => $e->getMessage(),
                'order_id' => $orderId,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Traiter un paiement PayPal et créer/mettre à jour le paiement
     */
    public function processPayment(string $orderId, Rental $rental = null): array
    {
        try {
            $captureResult = $this->captureOrder($orderId);

            if (!$captureResult['success']) {
                return $captureResult;
            }

            // Trouver ou créer le paiement
            $payment = Payment::where('payment_intent_id', $orderId)->first();

            if (!$payment && $rental) {
                // Créer le paiement
                $payment = Payment::create([
                    'rental_id' => $rental->id,
                    'user_id' => $rental->user_id,
                    'amount' => $captureResult['amount'],
                    'currency' => $captureResult['currency'],
                    'payment_method' => Payment::PAYMENT_METHOD_PAYPAL,
                    'payment_intent_id' => $orderId,
                    'status' => Payment::STATUS_COMPLETED,
                    'paid_at' => now(),
                    'metadata' => [
                        'paypal_order_id' => $orderId,
                        'paypal_payment_id' => $captureResult['payment_id'],
                        'paypal_status' => $captureResult['status'],
                        'paypal_order_data' => $captureResult['order'],
                    ],
                ]);

                // Mettre à jour le statut de la réservation à 'active' (confirmée)
                if ($rental) {
                    $rental->update(['status' => 'active']);
                }
            } elseif ($payment) {
                // Mettre à jour le paiement existant
                $payment->update([
                    'status' => Payment::STATUS_COMPLETED,
                    'paid_at' => now(),
                    'metadata' => array_merge($payment->metadata ?? [], [
                        'paypal_order_id' => $orderId,
                        'paypal_payment_id' => $captureResult['payment_id'],
                        'paypal_status' => $captureResult['status'],
                        'paypal_order_data' => $captureResult['order'],
                    ]),
                ]);

                // Mettre à jour le statut de la réservation à 'active' (confirmée)
                if ($rental && $rental->status === 'pending') {
                    $rental->update(['status' => 'active']);
                }
            }

            return [
                'success' => true,
                'payment' => $payment,
                'rental' => $rental,
            ];
        } catch (\Exception $e) {
            Log::error('PayPal payment processing failed', [
                'error' => $e->getMessage(),
                'order_id' => $orderId,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Obtenir une URL absolue pour les callbacks PayPal
     */
    private function getAbsoluteUrl(string $path): string
    {
        $appUrl = config('app.url');
        
        // S'assurer que l'URL de base se termine par /
        $appUrl = rtrim($appUrl, '/');
        
        // S'assurer que le chemin commence par /
        $path = '/' . ltrim($path, '/');
        
        return $appUrl . $path;
    }

    /**
     * Rembourser un paiement PayPal
     */
    public function refundPayment(Payment $payment, float $amount = null): array
    {
        try {
            $accessToken = $this->getAccessToken();

            if (!$accessToken) {
                return [
                    'success' => false,
                    'error' => 'Impossible d\'obtenir l\'access token PayPal',
                ];
            }

            $captureId = $payment->metadata['paypal_payment_id'] ?? null;

            if (!$captureId) {
                return [
                    'success' => false,
                    'error' => 'Aucun ID de capture PayPal associé à ce paiement',
                ];
            }

            $refundData = [];
            if ($amount) {
                $refundData['amount'] = [
                    'value' => number_format($amount, 2, '.', ''),
                    'currency_code' => $payment->currency,
                ];
            }

            $response = Http::withToken($accessToken)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($this->baseUrl . '/v2/payments/captures/' . $captureId . '/refund', $refundData);

            if ($response->successful()) {
                $refund = $response->json();

                // Mettre à jour le paiement
                $payment->update([
                    'status' => Payment::STATUS_REFUNDED,
                    'metadata' => array_merge($payment->metadata ?? [], [
                        'refund_id' => $refund['id'] ?? null,
                        'refund_amount' => $refund['amount']['value'] ?? null,
                        'refunded_at' => now()->toISOString(),
                    ]),
                ]);

                return [
                    'success' => true,
                    'refund' => $refund,
                ];
            }

            Log::error('PayPal refund failed', [
                'payment_id' => $payment->id,
                'capture_id' => $captureId,
                'response' => $response->body(),
            ]);

            return [
                'success' => false,
                'error' => 'Erreur lors du remboursement',
                'details' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('PayPal refund error', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}

