# Guide de Configuration Stripe + CMI

## Vue d'ensemble

Ce système supporte maintenant **deux passerelles de paiement** :
- **Stripe** : Pour les paiements internationaux (EUR, USD, etc.)
- **CMI** : Pour les paiements au Maroc (MAD)

Les clients peuvent choisir leur méthode de paiement lors de la réservation.

## Configuration Stripe

### 1. Obtenir vos clés Stripe

1. Créez un compte sur [https://stripe.com](https://stripe.com)
2. Allez dans **Developers > API keys**
3. Copiez votre **Publishable key** (clé publique)
4. Copiez votre **Secret key** (clé secrète)

### 2. Ajouter dans `.env`

```env
# Stripe Configuration
STRIPE_KEY=pk_test_...  # Votre clé publique
STRIPE_SECRET=sk_test_...  # Votre clé secrète
STRIPE_WEBHOOK_SECRET=whsec_...  # Pour les webhooks (optionnel en développement)
```

### 3. Configurer les Webhooks Stripe

1. Allez dans **Developers > Webhooks**
2. Cliquez sur **Add endpoint**
3. Entrez l'URL: `https://votre-domaine.com/stripe/webhook`
4. Sélectionnez les événements:
   - `payment_intent.succeeded`
   - `payment_intent.payment_failed`
   - `charge.refunded`
5. Copiez le **Signing secret** dans `.env`

## Configuration CMI

### 1. Obtenir vos identifiants CMI

1. Contactez CMI (Credit Mutuel International) pour ouvrir un compte marchand
2. Obtenez votre **Merchant ID** (identifiant marchand)
3. Obtenez votre **Secret Key** (clé secrète pour la signature)

### 2. Ajouter dans `.env`

```env
# CMI Configuration
CMI_MERCHANT_ID=votre_merchant_id
CMI_SECRET_KEY=votre_secret_key
CMI_TEST_MODE=true  # Mettre à false en production
CMI_TEST_URL=https://testpayment.cmi.co.ma/fim/est3Dgate
CMI_PRODUCTION_URL=https://payment.cmi.co.ma/fim/est3Dgate
```

### 3. URLs de Callback CMI

Dans votre dashboard CMI, configurez les URLs suivantes :

- **URL de succès:** `https://votre-domaine.com/cmi/success`
- **URL d'échec:** `https://votre-domaine.com/cmi/failure`
- **URL de callback:** `https://votre-domaine.com/cmi/callback`

## Test des Paiements

### Stripe (Mode Test)

Cartes de test Stripe :
- **Carte réussie:** `4242 4242 4242 4242`
- **Carte refusée:** `4000 0000 0000 0002`
- **Date:** N'importe quelle date future (ex: 12/25)
- **CVV:** N'importe quel 3 chiffres (ex: 123)

### CMI (Mode Test)

Contactez CMI pour obtenir les informations de test spécifiques à votre compte.

## Fonctionnement

### Stripe
1. Le client choisit Stripe
2. Le formulaire de carte s'affiche (Stripe Elements)
3. Le paiement est traité directement sur votre site
4. Confirmation immédiate

### CMI
1. Le client choisit CMI
2. Une réservation est créée en statut "pending"
3. Le client est redirigé vers la page de paiement CMI
4. Après paiement, CMI redirige vers votre site
5. Le callback CMI met à jour le statut du paiement

## Passage en Production

### Stripe
1. Basculez vers les clés **live** (`pk_live_` et `sk_live_`)
2. Configurez les webhooks avec votre URL de production
3. Testez avec de vraies petites transactions

### CMI
1. Mettez `CMI_TEST_MODE=false` dans `.env`
2. Utilisez l'URL de production CMI
3. Vérifiez que les URLs de callback sont correctes
4. Testez avec de vraies transactions

## Sécurité

- ✅ Toutes les données de carte sont traitées par les passerelles (PCI compliant)
- ✅ Aucune donnée de carte n'est stockée sur votre serveur
- ✅ Validation des signatures pour Stripe et CMI
- ✅ Protection CSRF sur tous les formulaires
- ✅ Transactions sécurisées avec HTTPS

## Support

- **Stripe:** [https://stripe.com/docs](https://stripe.com/docs)
- **CMI:** Contactez votre représentant CMI
- **Documentation Stripe:** [https://stripe.com/docs](https://stripe.com/docs)

## Notes Importantes

1. **CMI nécessite HTTPS** en production
2. **Les callbacks CMI doivent être accessibles publiquement**
3. **Stripe fonctionne en HTTP pour les tests**, mais nécessite HTTPS en production
4. **Les remboursements CMI** doivent être effectués manuellement depuis le dashboard CMI

