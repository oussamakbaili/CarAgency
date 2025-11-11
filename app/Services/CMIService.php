<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Rental;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class CMIService
{
    private $merchantId;
    private $secretKey;
    private $baseUrl;
    private $isTestMode;

    public function __construct()
    {
        $this->merchantId = config('services.cmi.merchant_id');
        $this->secretKey = config('services.cmi.secret_key');
        $this->baseUrl = config('services.cmi.test_mode') 
            ? config('services.cmi.test_url', 'https://testpayment.cmi.co.ma/fim/est3Dgate')
            : config('services.cmi.production_url', 'https://payment.cmi.co.ma/fim/est3Dgate');
        $this->isTestMode = config('services.cmi.test_mode', true);
    }

    /**
     * Générer la signature HMAC pour CMI
     */
    private function generateHash(array $params): string
    {
        // CMI utilise un format spécifique pour la signature
        $hashString = '';
        
        // Ordre spécifique des paramètres pour CMI
        $hashParams = [
            'amount',
            'currency',
            'oid',
            'email',
            'BillToName',
            'BillToCompany',
            'BillToStreet1',
            'BillToStreet2',
            'BillToCity',
            'BillToState',
            'BillToPostalCode',
            'BillToCountry',
            'tel',
            'fax',
        ];

        foreach ($hashParams as $param) {
            if (isset($params[$param])) {
                $hashString .= $params[$param];
            }
        }

        // Ajouter la clé secrète
        $hashString .= $this->secretKey;

        // Générer le hash SHA256
        return hash('sha256', $hashString);
    }

    /**
     * Créer une transaction CMI et retourner l'URL de redirection
     */
    public function createPayment(array $data): array
    {
        try {
            $amount = number_format($data['amount'], 2, '.', '');
            $currency = $data['currency'] ?? 'MAD';
            $orderId = $data['order_id'] ?? uniqid('CMI_', true);
            $email = $data['email'];
            $name = $data['name'] ?? '';
            $phone = $data['phone'] ?? '';
            $address = $data['address'] ?? '';
            $city = $data['city'] ?? '';
            $country = $data['country'] ?? 'MA';
            $postalCode = $data['postal_code'] ?? '';

            // Paramètres CMI
            $params = [
                'amount' => $amount,
                'currency' => $currency,
                'oid' => $orderId,
                'email' => $email,
                'BillToName' => $name,
                'BillToCompany' => '',
                'BillToStreet1' => $address,
                'BillToStreet2' => '',
                'BillToCity' => $city,
                'BillToState' => '',
                'BillToPostalCode' => $postalCode,
                'BillToCountry' => $country,
                'tel' => $phone,
                'fax' => '',
                'storetype' => '3D_PAY_HOSTING',
                'hashAlgorithm' => 'ver3',
                'refreshtime' => '0',
                'lang' => 'fr',
                'rnd' => time(),
                'TranType' => 'Auth',
                'callbackUrl' => route('cmi.callback'),
                'okUrl' => route('cmi.success'),
                'failUrl' => route('cmi.failure'),
            ];

            // Générer la signature
            $hash = $this->generateHash($params);
            $params['hash'] = $hash;
            $params['storetype'] = '3D_PAY_HOSTING';
            $params['hashAlgorithm'] = 'ver3';

            // Ajouter le merchant ID
            $params['clientid'] = $this->merchantId;

            return [
                'success' => true,
                'redirect_url' => $this->baseUrl,
                'params' => $params,
                'order_id' => $orderId,
            ];
        } catch (\Exception $e) {
            Log::error('CMI payment creation failed', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Vérifier la signature de la réponse CMI
     */
    public function verifyCallback(array $params): bool
    {
        try {
            // Récupérer le hash reçu
            $receivedHash = $params['HASH'] ?? $params['hash'] ?? '';

            // Reconstruire le hash avec les paramètres reçus
            $hashString = '';
            
            // Ordre des paramètres pour la vérification (selon documentation CMI)
            $hashParams = [
                'oid',
                'Response',
                'ProcReturnCode',
                'mdStatus',
                'cavv',
                'eci',
                'md',
                'rnd',
                'amount',
                'currency',
            ];

            foreach ($hashParams as $param) {
                if (isset($params[$param])) {
                    $hashString .= $params[$param];
                }
            }

            // Ajouter la clé secrète
            $hashString .= $this->secretKey;

            // Générer le hash
            $calculatedHash = hash('sha256', $hashString);

            // Comparer les hashs
            return strtoupper($calculatedHash) === strtoupper($receivedHash);
        } catch (\Exception $e) {
            Log::error('CMI hash verification failed', [
                'error' => $e->getMessage(),
                'params' => $params,
            ]);

            return false;
        }
    }

    /**
     * Traiter la réponse CMI et créer/mettre à jour le paiement
     */
    public function processCallback(array $params, Rental $rental = null): array
    {
        try {
            // Vérifier la signature
            if (!$this->verifyCallback($params)) {
                return [
                    'success' => false,
                    'error' => 'Signature invalide',
                ];
            }

            $orderId = $params['oid'] ?? '';
            $response = $params['Response'] ?? '';
            $procReturnCode = $params['ProcReturnCode'] ?? '';
            $amount = $params['amount'] ?? 0;
            $currency = $params['currency'] ?? 'MAD';

            // Convertir le montant (CMI envoie le montant avec 2 décimales)
            $amount = floatval($amount);

            // Vérifier si le paiement a réussi
            $isSuccess = ($response === 'Approved' || $response === 'Success') && 
                        ($procReturnCode === '00' || $procReturnCode === '');

            if ($isSuccess) {
                // Trouver ou créer le paiement
                $payment = Payment::where('payment_intent_id', $orderId)->first();

                if (!$payment && $rental) {
                    // Créer le paiement
                    $payment = Payment::create([
                        'rental_id' => $rental->id,
                        'user_id' => $rental->user_id,
                        'amount' => $amount,
                        'currency' => $currency,
                        'payment_method' => Payment::PAYMENT_METHOD_BANK_TRANSFER, // CMI est considéré comme virement
                        'payment_intent_id' => $orderId,
                        'status' => Payment::STATUS_COMPLETED,
                        'paid_at' => now(),
                        'metadata' => [
                            'cmi_response' => $response,
                            'cmi_proc_return_code' => $procReturnCode,
                            'cmi_md_status' => $params['mdStatus'] ?? null,
                            'cmi_transaction_id' => $params['TransId'] ?? null,
                            'cmi_host_ref_num' => $params['HostRefNum'] ?? null,
                            'cmi_rnd' => $params['rnd'] ?? null,
                        ],
                    ]);

                    // Mettre à jour le statut de la réservation
                    if ($rental) {
                        $rental->update(['status' => 'confirmed']);
                    }
                } elseif ($payment) {
                    // Mettre à jour le paiement existant
                    $payment->update([
                        'status' => Payment::STATUS_COMPLETED,
                        'paid_at' => now(),
                        'metadata' => array_merge($payment->metadata ?? [], [
                            'cmi_response' => $response,
                            'cmi_proc_return_code' => $procReturnCode,
                            'cmi_md_status' => $params['mdStatus'] ?? null,
                            'cmi_transaction_id' => $params['TransId'] ?? null,
                            'cmi_host_ref_num' => $params['HostRefNum'] ?? null,
                            'cmi_rnd' => $params['rnd'] ?? null,
                        ]),
                    ]);
                }

                return [
                    'success' => true,
                    'payment' => $payment,
                    'rental' => $rental,
                ];
            } else {
                // Paiement échoué
                $payment = Payment::where('payment_intent_id', $orderId)->first();
                
                if ($payment) {
                    $payment->update([
                        'status' => Payment::STATUS_FAILED,
                        'metadata' => array_merge($payment->metadata ?? [], [
                            'cmi_response' => $response,
                            'cmi_proc_return_code' => $procReturnCode,
                            'cmi_error_message' => $params['ErrMsg'] ?? 'Paiement refusé',
                        ]),
                    ]);
                }

                return [
                    'success' => false,
                    'error' => $params['ErrMsg'] ?? 'Paiement refusé',
                    'response' => $response,
                    'proc_return_code' => $procReturnCode,
                ];
            }
        } catch (\Exception $e) {
            Log::error('CMI callback processing failed', [
                'error' => $e->getMessage(),
                'params' => $params,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Rembourser un paiement CMI (si supporté)
     */
    public function refundPayment(Payment $payment, float $amount = null)
    {
        // CMI ne supporte pas toujours les remboursements via API
        // Cela doit être fait manuellement depuis le dashboard CMI
        Log::info('CMI refund requested', [
            'payment_id' => $payment->id,
            'amount' => $amount,
        ]);

        return [
            'success' => false,
            'error' => 'Les remboursements CMI doivent être effectués manuellement depuis le dashboard CMI',
        ];
    }
}

