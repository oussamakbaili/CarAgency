<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DiagnosePayPal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paypal:diagnose';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Diagnostiquer la configuration PayPal et tester la connexion';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Diagnostic de la configuration PayPal...');
        $this->newLine();

        // Vérifier les variables d'environnement
        $clientId = config('services.paypal.client_id');
        $clientSecret = config('services.paypal.client_secret');
        $testMode = config('services.paypal.test_mode', true);
        $appUrl = config('app.url');

        $this->info('📋 Configuration actuelle:');
        $this->line('   Mode test: ' . ($testMode ? '✅ Activé (Sandbox)' : '❌ Désactivé (Production)'));
        $this->line('   Client ID: ' . ($clientId ? substr($clientId, 0, 15) . '...' : '❌ Non configuré'));
        $this->line('   Client Secret: ' . ($clientSecret ? '✅ Configuré (' . strlen($clientSecret) . ' caractères)' : '❌ Non configuré'));
        $this->line('   APP_URL: ' . ($appUrl ?: '❌ Non configuré'));
        $this->newLine();

        // Vérifier si les credentials sont configurés
        if (empty($clientId) || empty($clientSecret)) {
            $this->error('❌ Les identifiants PayPal ne sont pas configurés!');
            $this->newLine();
            $this->warn('Pour résoudre ce problème:');
            $this->line('1. Ouvrez votre fichier .env');
            $this->line('2. Ajoutez ou modifiez ces lignes:');
            $this->line('   PAYPAL_CLIENT_ID=votre_client_id');
            $this->line('   PAYPAL_CLIENT_SECRET=votre_client_secret');
            $this->line('   PAYPAL_TEST_MODE=true  (ou false pour la production)');
            $this->line('3. Exécutez: php artisan config:clear');
            $this->newLine();
            return 1;
        }

        // Tester la connexion à l'API PayPal
        $this->info('🔌 Test de connexion à l\'API PayPal...');
        
        $baseUrl = $testMode
            ? 'https://api.sandbox.paypal.com'
            : 'https://api.paypal.com';

        $this->line('   URL: ' . $baseUrl . '/v1/oauth2/token');
        
        try {
            $response = Http::timeout(30)
                ->asForm()
                ->withBasicAuth($clientId, $clientSecret)
                ->post($baseUrl . '/v1/oauth2/token', [
                    'grant_type' => 'client_credentials',
                ]);

            if ($response->successful()) {
                $responseData = $response->json();
                if (isset($responseData['access_token'])) {
                    $this->info('✅ Connexion réussie!');
                    $this->line('   Access token obtenu avec succès');
                    $this->line('   Token type: ' . ($responseData['token_type'] ?? 'N/A'));
                    $this->line('   Expires in: ' . ($responseData['expires_in'] ?? 'N/A') . ' secondes');
                    $this->newLine();
                    $this->info('✅ Votre configuration PayPal est correcte!');
                    return 0;
                } else {
                    $this->error('❌ La réponse PayPal ne contient pas d\'access token');
                    $this->line('   Réponse: ' . json_encode($responseData, JSON_PRETTY_PRINT));
                    return 1;
                }
            } else {
                $status = $response->status();
                $body = $response->body();
                $errorJson = $response->json();
                
                $this->error('❌ Échec de la connexion à PayPal');
                $this->line('   Status HTTP: ' . $status);
                
                if (isset($errorJson['error'])) {
                    $this->line('   Erreur: ' . $errorJson['error']);
                }
                
                if (isset($errorJson['error_description'])) {
                    $this->line('   Description: ' . $errorJson['error_description']);
                }
                
                $this->newLine();
                $this->warn('Causes possibles:');
                $this->line('1. Les identifiants PayPal sont incorrects');
                $this->line('2. Le compte PayPal Business n\'est pas actif');
                $this->line('3. Le mode test/production ne correspond pas aux identifiants');
                $this->line('4. Problème de connexion internet');
                
                if ($status === 401) {
                    $this->newLine();
                    $this->error('⚠️  Erreur 401: Identifiants invalides');
                    $this->line('   Vérifiez que PAYPAL_CLIENT_ID et PAYPAL_CLIENT_SECRET sont corrects');
                }
                
                return 1;
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $this->error('❌ Erreur de connexion');
            $this->line('   Impossible de se connecter à l\'API PayPal');
            $this->line('   Vérifiez votre connexion internet');
            $this->line('   Erreur: ' . $e->getMessage());
            return 1;
        } catch (\Exception $e) {
            $this->error('❌ Erreur inattendue');
            $this->line('   ' . $e->getMessage());
            return 1;
        }
    }
}

