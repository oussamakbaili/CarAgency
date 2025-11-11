# 🚀 Guide Rapide - Intégration Bancaire Temps Réel

## Résumé en 11 Étapes

### ✅ Étape 1 : Préparation
1. Choisir votre provider bancaire (Stripe, PayPal, API directe, etc.)
2. Obtenir les clés API et le secret webhook
3. Configurer le compte bancaire/PSP

### ✅ Étape 2 : Base de Données
```bash
php artisan make:migration add_bank_transfer_fields_to_transactions_table
# Éditer la migration (voir BANK_INTEGRATION_IMPLEMENTATION.md)
php artisan migrate
```

### ✅ Étape 3 : Configuration
1. Ajouter les variables dans `.env` :
   ```env
   BANK_PROVIDER=stripe
   BANK_API_KEY=sk_...
   BANK_API_SECRET=sk_...
   BANK_WEBHOOK_SECRET=whsec_...
   BANK_TEST_MODE=true
   ```

2. Mettre à jour `config/services.php` (voir guide détaillé)

### ✅ Étape 4 : Créer les Modèles et Services
```bash
php artisan make:model BankTransfer
php artisan make:service BankTransferService
php artisan make:controller BankWebhookController
```

### ✅ Étape 5 : Implémenter le Code
- Modèle `BankTransfer` (voir guide détaillé)
- Service `BankTransferService` (voir guide détaillé)
- Contrôleur `BankWebhookController` (voir guide détaillé)

### ✅ Étape 6 : Ajouter la Route Webhook
Dans `routes/web.php` :
```php
Route::post('/api/bank/webhook', [BankWebhookController::class, 'handle'])
    ->name('bank.webhook')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
```

### ✅ Étape 7 : Mettre à Jour PaymentRequestController
Modifier la méthode `approve()` pour appeler `BankTransferService::initiateTransfer()`

### ✅ Étape 8 : Mettre à Jour les Notifications
Ajouter dans `PaymentNotificationService` :
- `notifyAgencyPayoutProcessing()`
- `notifyAgencyPayoutConfirmed()`
- `notifyAgencyPayoutFailed()`

### ✅ Étape 9 : Configurer le Webhook dans le Compte Bancaire
1. Se connecter au compte bancaire/PSP
2. Aller dans "Webhooks" ou "Callbacks"
3. Ajouter l'URL : `https://votre-domaine.com/api/bank/webhook`
4. Sélectionner les événements :
   - `transfer.completed`
   - `transfer.failed`
5. Copier le secret et l'ajouter dans `.env`

### ✅ Étape 10 : Tests
1. Créer une demande de paiement (agence)
2. Approuver la demande (admin)
3. Vérifier que le virement est initié
4. Simuler un webhook de confirmation
5. Vérifier les notifications et la mise à jour du statut

### ✅ Étape 11 : Mise en Production
1. Passer `BANK_TEST_MODE=false`
2. Utiliser les clés API de production
3. Configurer le webhook en production
4. Monitorer les logs

---

## 📋 Checklist Complète

### Avant de Commencer
- [ ] Provider bancaire choisi
- [ ] Compte bancaire/PSP créé
- [ ] Clés API obtenues
- [ ] Documentation API lue

### Développement
- [ ] Migration créée et exécutée
- [ ] Modèle BankTransfer créé
- [ ] Service BankTransferService créé
- [ ] Contrôleur Webhook créé
- [ ] Route webhook ajoutée
- [ ] PaymentRequestController mis à jour
- [ ] Notifications mises à jour
- [ ] Variables d'environnement configurées

### Tests
- [ ] Tests en mode sandbox/test
- [ ] Webhook testé manuellement
- [ ] Notifications vérifiées
- [ ] Gestion d'erreurs testée

### Production
- [ ] Mode test désactivé
- [ ] Clés API de production configurées
- [ ] Webhook configuré en production
- [ ] Monitoring activé
- [ ] Documentation utilisateur créée

---

## 🔧 Commandes Utiles

### Créer les fichiers
```bash
php artisan make:migration add_bank_transfer_fields_to_transactions_table
php artisan make:model BankTransfer
php artisan make:service BankTransferService
php artisan make:controller BankWebhookController
```

### Exécuter les migrations
```bash
php artisan migrate
php artisan migrate:rollback
php artisan migrate:fresh
```

### Tester le webhook (curl)
```bash
curl -X POST http://localhost:8000/api/bank/webhook \
  -H "Content-Type: application/json" \
  -d '{"type":"transfer.completed","transfer_id":"test_123"}'
```

### Vérifier les logs
```bash
tail -f storage/logs/laravel.log
```

---

## 📚 Documentation Complète

- **Guide Complet** : `BANK_INTEGRATION_GUIDE.md`
- **Code Détaillé** : `BANK_INTEGRATION_IMPLEMENTATION.md`
- **Ce Guide Rapide** : `BANK_INTEGRATION_QUICK_START.md`

---

## ⚠️ Points Importants

1. **Sécurité** : Toujours vérifier la signature du webhook
2. **HTTPS** : Le webhook doit être en HTTPS en production
3. **Logging** : Logger toutes les interactions avec la banque
4. **Tests** : Toujours tester en mode sandbox/test avant la production
5. **Monitoring** : Surveiller les webhooks et les transactions

---

## 🆘 Support

En cas de problème :
1. Vérifier les logs : `storage/logs/laravel.log`
2. Vérifier la configuration dans `.env`
3. Tester le webhook manuellement
4. Consulter la documentation de votre provider bancaire

---

**Temps estimé d'implémentation :** 2-3 jours (selon le provider choisi)

