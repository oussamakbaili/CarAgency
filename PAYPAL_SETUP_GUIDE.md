# Guide de Configuration PayPal

## Vue d'ensemble

PayPal a été ajouté comme troisième option de paiement, en plus de Stripe et CMI.

## Configuration PayPal

### 1. Créer un compte PayPal Developer

1. Allez sur [https://developer.paypal.com](https://developer.paypal.com)
2. Créez un compte ou connectez-vous avec votre compte PayPal
3. Allez dans **Dashboard > My Apps & Credentials**

### 2. Créer une Application

1. Cliquez sur **Create App**
2. Donnez un nom à votre application (ex: "Toubcar")
3. Sélectionnez **Merchant** comme type
4. Cliquez sur **Create App**

### 3. Obtenir vos identifiants

Après la création, vous verrez :
- **Client ID** (clé publique)
- **Secret** (clé secrète)

**Important:** 
- Pour les tests, utilisez les identifiants **Sandbox**
- Pour la production, utilisez les identifiants **Live**

### 4. Ajouter dans `.env`

```env
# --- Configuration PayPal ---
PAYPAL_CLIENT_ID=your_paypal_client_id_here
PAYPAL_CLIENT_SECRET=your_paypal_client_secret_here
PAYPAL_TEST_MODE=true
```

### 5. URLs de retour PayPal

Dans votre application PayPal (Dashboard), configurez les URLs de retour :

- **Return URL:** `https://toubcar.com/paypal/success`
- **Cancel URL:** `https://toubcar.com/paypal/cancel`

## Test des Paiements

### Mode Sandbox (Test)

1. Allez dans **Dashboard > Sandbox > Accounts**
2. Créez des comptes de test (acheteur et vendeur)
3. Utilisez ces comptes pour tester les paiements

**Comptes de test PayPal:**
- Email: `sb-...@business.example.com` (vendeur)
- Email: `sb-...@personal.example.com` (acheteur)
- Mot de passe: celui que vous avez défini lors de la création

## Passage en Production

1. Basculez vers les identifiants **Live** dans `.env`
2. Mettez `PAYPAL_TEST_MODE=false`
3. Testez avec de vraies petites transactions
4. Vérifiez que les URLs de retour sont correctes

## Fonctionnement

1. Le client choisit PayPal
2. Une réservation est créée en statut "pending"
3. Le client est redirigé vers PayPal
4. Après paiement, PayPal redirige vers votre site
5. Le système capture automatiquement le paiement
6. La réservation est confirmée

## Sécurité

- ✅ Toutes les transactions sont sécurisées par PayPal
- ✅ Validation des signatures PayPal
- ✅ Protection CSRF sur tous les formulaires
- ✅ Transactions sécurisées avec HTTPS

## Support

- **Documentation PayPal:** [https://developer.paypal.com/docs](https://developer.paypal.com/docs)
- **Support PayPal:** [https://www.paypal.com/support](https://www.paypal.com/support)

## Notes Importantes

1. **PayPal nécessite HTTPS** en production
2. **Les URLs de retour doivent être accessibles publiquement**
3. **Les remboursements PayPal** peuvent être effectués via l'API
4. **PayPal supporte plusieurs devises** (EUR, USD, etc.)

