# Guide de Configuration du Système de Paiement

## Vue d'ensemble

Ce guide vous explique comment rendre le système de paiement vraiment fonctionnel en utilisant **Stripe**, l'une des solutions de paiement les plus populaires et sécurisées.

## Étapes de Configuration

### 1. Installation du Package Stripe

```bash
composer require stripe/stripe-php
```

### 2. Configuration des Variables d'Environnement

Ajoutez ces variables dans votre fichier `.env`:

```env
# Stripe Configuration
STRIPE_KEY=pk_test_...  # Votre clé publique Stripe (commence par pk_test_ ou pk_live_)
STRIPE_SECRET=sk_test_...  # Votre clé secrète Stripe (commence par sk_test_ ou sk_live_)
STRIPE_WEBHOOK_SECRET=whsec_...  # Secret pour valider les webhooks (obtenu depuis le dashboard Stripe)
```

**Important:**
- Pour le développement, utilisez les clés de **test** (commencent par `pk_test_` et `sk_test_`)
- Pour la production, utilisez les clés **live** (commencent par `pk_live_` et `sk_live_`)

### 3. Obtenir vos Clés Stripe

1. Créez un compte sur [https://stripe.com](https://stripe.com)
2. Allez dans **Developers > API keys**
3. Copiez votre **Publishable key** (clé publique)
4. Copiez votre **Secret key** (clé secrète)
5. Pour les webhooks, allez dans **Developers > Webhooks** et créez un endpoint pointant vers votre domaine

### 4. Configuration du Fichier services.php

Le fichier `config/services.php` a déjà été mis à jour pour inclure la configuration Stripe.

### 5. Migration de la Base de Données

Exécutez la migration pour créer la table `payments`:

```bash
php artisan migrate
```

### 6. Test du Système

#### En Mode Test

Stripe fournit des numéros de carte de test:

- **Carte réussie:** `4242 4242 4242 4242`
- **Carte refusée:** `4000 0000 0000 0002`
- **Carte nécessitant authentification:** `4000 0025 0000 3155`

**Date d'expiration:** N'importe quelle date future (ex: 12/25)
**CVV:** N'importe quel 3 chiffres (ex: 123)
**Code postal:** N'importe quel code postal valide

### 7. Configuration des Webhooks (Production)

1. Allez dans **Developers > Webhooks** sur votre dashboard Stripe
2. Cliquez sur **Add endpoint**
3. Entrez l'URL: `https://votre-domaine.com/stripe/webhook`
4. Sélectionnez les événements à écouter:
   - `payment_intent.succeeded`
   - `payment_intent.payment_failed`
   - `charge.refunded`
5. Copiez le **Signing secret** et ajoutez-le à votre `.env` comme `STRIPE_WEBHOOK_SECRET`

### 8. Passer en Production

Quand vous êtes prêt pour la production:

1. Basculez vers les clés **live** dans votre `.env`
2. Testez avec de vraies petites transactions
3. Configurez les webhooks avec votre URL de production
4. Activez la protection CSRF et HTTPS

## Méthodes de Paiement Supportées

Le système supporte actuellement:
- **Carte bancaire** (via Stripe)
- **PayPal** (à implémenter si nécessaire)
- **Virement bancaire** (manuel, nécessite validation admin)

## Sécurité

- ✅ Toutes les données de carte sont traitées par Stripe (PCI compliant)
- ✅ Aucune donnée de carte n'est stockée sur votre serveur
- ✅ Validation des webhooks avec signature
- ✅ Protection CSRF sur tous les formulaires

## Support

Pour toute question:
- Documentation Stripe: [https://stripe.com/docs](https://stripe.com/docs)
- Support Stripe: [https://support.stripe.com](https://support.stripe.com)

