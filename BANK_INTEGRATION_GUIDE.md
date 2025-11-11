# Guide d'Intégration Bancaire Temps Réel - Confirmation Automatique des Virements

## 📋 Table des Matières
1. [Vue d'ensemble](#vue-densemble)
2. [Étape 1 : Choix de la Solution Bancaire](#étape-1--choix-de-la-solution-bancaire)
3. [Étape 2 : Préparation de l'Infrastructure](#étape-2--préparation-de-linfrastructure)
4. [Étape 3 : Configuration de l'Environnement](#étape-3--configuration-de-lenvironnement)
5. [Étape 4 : Création du Service Bancaire](#étape-4--création-du-service-bancaire)
6. [Étape 5 : Implémentation du Webhook](#étape-5--implémentation-du-webhook)
7. [Étape 6 : Mise à Jour du Contrôleur de Paiement](#étape-6--mise-à-jour-du-contrôleur-de-paiement)
8. [Étape 7 : Gestion des Statuts de Virement](#étape-7--gestion-des-statuts-de-virement)
9. [Étape 8 : Notifications Automatiques](#étape-8--notifications-automatiques)
10. [Étape 9 : Interface Admin Améliorée](#étape-9--interface-admin-améliorée)
11. [Étape 10 : Tests et Validation](#étape-10--tests-et-validation)
12. [Étape 11 : Mise en Production](#étape-11--mise-en-production)

---

## Vue d'ensemble

Ce guide vous permettra d'intégrer un système de confirmation automatique des virements bancaires en temps réel. Le système fonctionnera avec des webhooks pour recevoir les confirmations de la banque et mettre à jour automatiquement le statut des transactions.

### Architecture Proposée

```
┌─────────────┐         ┌──────────────┐         ┌─────────────┐
│   Agence    │  ────>  │   Admin      │  ────>  │   Banque    │
│  Demande    │         │  Approuve    │         │  API/PSP    │
│  Paiement   │         │  Virement    │         │             │
└─────────────┘         └──────────────┘         └──────┬──────┘
                                                        │
                                                        │ Webhook
                                                        │ (Confirmation)
                                                        ▼
                                                ┌──────────────┐
                                                │  Application │
                                                │  Laravel     │
                                                └──────────────┘
```

---

## Étape 1 : Choix de la Solution Bancaire

### Option A : API Bancaire Directe (Maroc)
- **Banques avec API disponibles :**
  - Attijariwafa Bank (API Corporate)
  - Bank of Africa (BOA Connect)
  - BMCE Bank (API Banking)
  - Crédit du Maroc
  - CIH Bank

**Avantages :**
- Intégration directe
- Pas de frais intermédiaires
- Contrôle total

**Inconvénients :**
- Processus d'approbation long (2-4 semaines)
- Documentation technique parfois limitée
- Nécessite un compte professionnel

### Option B : Prestataire de Services de Paiement (PSP)
- **Options recommandées :**
  - **Stripe Connect** (Payouts) - International
  - **PayPal Payouts** - International
  - **M2T (Maroc Telecom Money)** - Maroc
  - **HPS (Payment Solutions)** - Maroc
  - **Payzone** - Maroc

**Avantages :**
- Intégration rapide (1-2 semaines)
- Documentation complète
- Support technique
- Gestion des erreurs automatique

**Inconvénients :**
- Frais de transaction (0.5% - 2%)
- Moins de contrôle sur le processus

### Option C : Solution Hybride (Recommandée pour le Maroc)
Utiliser un PSP pour les virements automatiques + API bancaire pour la réconciliation.

---

## Étape 2 : Préparation de l'Infrastructure

### 2.1 Créer la Migration pour les Virements Bancaires

```bash
php artisan make:migration add_bank_transfer_fields_to_transactions_table
```

### 2.2 Structure de Base de Données Nécessaire

**Table `transactions` (déjà existante) :**
- `id` - ID de la transaction
- `agency_id` - ID de l'agence
- `type` - Type (withdrawal_request)
- `amount` - Montant
- `status` - Statut (pending, processing, completed, failed)
- `metadata` - JSON avec détails bancaires
- `bank_transfer_id` - ID du virement côté banque (nouveau)
- `bank_confirmed_at` - Date de confirmation bancaire (nouveau)
- `webhook_received_at` - Date de réception du webhook (nouveau)

**Nouvelle table `bank_transfers` (optionnelle mais recommandée) :**
- `id`
- `transaction_id`
- `bank_reference` - Référence bancaire
- `bank_transfer_id` - ID unique du virement
- `status` - Statut du virement
- `initiated_at` - Date d'initiation
- `confirmed_at` - Date de confirmation
- `failure_reason` - Raison d'échec
- `webhook_data` - Données brutes du webhook
- `created_at`, `updated_at`

---

## Étape 3 : Configuration de l'Environnement

### 3.1 Variables d'Environnement (.env)

```env
# Configuration Bancaire
BANK_PROVIDER=stripe  # ou 'paypal', 'm2t', 'hps', 'direct_api'
BANK_API_KEY=sk_live_...
BANK_API_SECRET=sk_live_...
BANK_WEBHOOK_SECRET=whsec_...
BANK_TEST_MODE=false

# URL du Webhook (sera générée automatiquement)
BANK_WEBHOOK_URL=https://votre-domaine.com/api/bank/webhook

# Configuration pour API Bancaire Directe (si applicable)
BANK_API_URL=https://api.banque.ma/v1
BANK_ACCOUNT_NUMBER=...
BANK_ACCOUNT_IBAN=...
BANK_API_USERNAME=...
BANK_API_PASSWORD=...
```

### 3.2 Mise à Jour de config/services.php

```php
'bank' => [
    'provider' => env('BANK_PROVIDER', 'stripe'),
    'api_key' => env('BANK_API_KEY'),
    'api_secret' => env('BANK_API_SECRET'),
    'webhook_secret' => env('BANK_WEBHOOK_SECRET'),
    'test_mode' => env('BANK_TEST_MODE', false),
    'webhook_url' => env('BANK_WEBHOOK_URL'),
    // Pour API directe
    'api_url' => env('BANK_API_URL'),
    'account_number' => env('BANK_ACCOUNT_NUMBER'),
    'account_iban' => env('BANK_ACCOUNT_IBAN'),
    'api_username' => env('BANK_API_USERNAME'),
    'api_password' => env('BANK_API_PASSWORD'),
],
```

---

## Étape 4 : Création du Service Bancaire

### 4.1 Créer le Service BankTransferService

```bash
php artisan make:service BankTransferService
```

**Fichier : `app/Services/BankTransferService.php`**

Ce service gérera :
- L'initiation des virements
- La réception des webhooks
- La mise à jour des statuts
- La gestion des erreurs

### 4.2 Méthodes Principales

1. **`initiateTransfer($transaction)`** - Initie un virement bancaire
2. **`handleWebhook($payload)`** - Traite les webhooks de la banque
3. **`checkTransferStatus($transferId)`** - Vérifie le statut d'un virement
4. **`confirmTransfer($transaction, $webhookData)`** - Confirme un virement
5. **`handleTransferFailure($transaction, $reason)`** - Gère les échecs

---

## Étape 5 : Implémentation du Webhook

### 5.1 Créer le Contrôleur Webhook

```bash
php artisan make:controller BankWebhookController
```

**Fichier : `app/Http/Controllers/BankWebhookController.php`**

### 5.2 Route Webhook (dans routes/web.php)

```php
// Webhook bancaire (doit être en dehors de la protection CSRF)
Route::post('/api/bank/webhook', [BankWebhookController::class, 'handle'])
    ->name('bank.webhook')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
```

### 5.3 Sécurité du Webhook

- Vérification de la signature
- Validation de l'IP source (whitelist)
- Logging de toutes les requêtes
- Protection contre les attaques de replay

---

## Étape 6 : Mise à Jour du Contrôleur de Paiement

### 6.1 Modifier `PaymentRequestController::approve()`

Quand l'admin approuve une demande :
1. Appeler `BankTransferService::initiateTransfer()`
2. Mettre le statut à `processing`
3. Stocker l'ID du virement bancaire
4. Envoyer une notification à l'agence

### 6.2 Flux de Traitement

```
Admin approuve → Initie virement → Statut: processing
                                    ↓
                            Banque traite virement
                                    ↓
                            Webhook reçu → Statut: completed
                                    ↓
                            Notification agence
```

---

## Étape 7 : Gestion des Statuts de Virement

### 7.1 Nouveaux Statuts

- `pending` - En attente d'approbation admin
- `processing` - Virement initié, en cours de traitement
- `completed` - Virement confirmé par la banque
- `failed` - Échec du virement
- `cancelled` - Annulé

### 7.2 Mise à Jour du Modèle Transaction

Ajouter les champs :
- `bank_transfer_id`
- `bank_confirmed_at`
- `webhook_received_at`

---

## Étape 8 : Notifications Automatiques

### 8.1 Notifications à Implémenter

1. **Agence - Virement initié**
   - "Votre demande de retrait a été approuvée. Le virement est en cours de traitement."

2. **Agence - Virement confirmé**
   - "Votre virement de {montant} MAD a été confirmé et crédité sur votre compte."

3. **Agence - Virement échoué**
   - "Le virement a échoué. Raison : {raison}. Votre solde a été restauré."

4. **Admin - Virement confirmé**
   - "Le virement #{id} a été confirmé par la banque."

### 8.2 Utiliser le Service de Notification Existant

Mettre à jour `PaymentNotificationService` pour inclure ces nouveaux types.

---

## Étape 9 : Interface Admin Améliorée

### 9.1 Tableau des Demandes de Paiement

Afficher :
- Statut en temps réel (avec badge coloré)
- Date d'initiation du virement
- Date de confirmation (si disponible)
- Référence bancaire
- Bouton "Vérifier le statut" (polling manuel)

### 9.2 Dashboard Admin

Ajouter une carte :
- Virements en cours : X
- Virements confirmés aujourd'hui : Y MAD
- Virements échoués (7 derniers jours) : Z

---

## Étape 10 : Tests et Validation

### 10.1 Tests Unitaires

```bash
php artisan make:test BankTransferServiceTest
```

Tester :
- Initiation de virement
- Traitement de webhook
- Gestion des erreurs
- Mise à jour des statuts

### 10.2 Tests d'Intégration

1. **Test avec Sandbox/Test Mode**
   - Créer une demande de paiement
   - Approuver la demande
   - Simuler un webhook de confirmation
   - Vérifier la mise à jour du statut

2. **Test de Scénarios d'Erreur**
   - Virement échoué
   - Webhook invalide
   - Timeout de la banque
   - Doublon de webhook

### 10.3 Tests Manuels

1. Créer une demande de paiement (agence)
2. Approuver la demande (admin)
3. Vérifier que le virement est initié
4. Simuler un webhook de confirmation
5. Vérifier les notifications
6. Vérifier la mise à jour du solde

---

## Étape 11 : Mise en Production

### 11.1 Checklist Pré-Production

- [ ] Variables d'environnement configurées
- [ ] Webhook URL configurée dans le compte bancaire/PSP
- [ ] Certificat SSL valide (HTTPS requis)
- [ ] Logging activé
- [ ] Monitoring configuré
- [ ] Backup de la base de données
- [ ] Documentation utilisateur créée

### 11.2 Configuration du Webhook dans le Compte Bancaire

1. Se connecter au compte bancaire/PSP
2. Aller dans "Webhooks" ou "Callbacks"
3. Ajouter l'URL : `https://votre-domaine.com/api/bank/webhook`
4. Sélectionner les événements :
   - `transfer.initiated`
   - `transfer.completed`
   - `transfer.failed`
5. Copier le secret du webhook
6. Ajouter dans `.env` : `BANK_WEBHOOK_SECRET=...`

### 11.3 Monitoring et Alertes

Configurer des alertes pour :
- Webhooks non reçus après 24h
- Taux d'échec élevé (>5%)
- Erreurs de traitement de webhook
- Transactions en statut `processing` > 48h

### 11.4 Plan de Rollback

En cas de problème :
1. Désactiver les webhooks automatiques
2. Revenir au mode manuel (admin confirme manuellement)
3. Corriger les bugs
4. Réactiver progressivement

---

## 📝 Notes Importantes

### Sécurité
- **Toujours** vérifier la signature du webhook
- **Ne jamais** faire confiance aux données du webhook sans validation
- **Logger** toutes les interactions avec la banque
- **Chiffrer** les données sensibles (RIB, IBAN) en base

### Performance
- Traiter les webhooks de manière asynchrone (queues)
- Mettre en cache les statuts fréquemment consultés
- Optimiser les requêtes de base de données

### Conformité
- Respecter le RGPD pour les données bancaires
- Conserver les logs de transactions (7 ans minimum)
- Documenter tous les virements pour audit

---

## 🔄 Prochaines Étapes

Une fois ce guide implémenté, vous pourrez :
1. Automatiser complètement le processus de virement
2. Réduire le temps de traitement de 2-3 jours à quelques heures
3. Améliorer la traçabilité des transactions
4. Réduire les erreurs manuelles

---

## 📞 Support

Pour toute question ou problème :
1. Consulter la documentation de votre banque/PSP
2. Vérifier les logs : `storage/logs/laravel.log`
3. Tester en mode sandbox/test avant la production

---

**Date de création :** {{ date('Y-m-d') }}
**Version :** 1.0
**Auteur :** Système CarAgency

