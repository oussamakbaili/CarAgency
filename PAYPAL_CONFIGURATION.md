# Guide de Configuration PayPal pour la Production

Ce guide vous explique comment configurer PayPal pour recevoir des paiements réels sur votre application de location de voitures.

## 📋 Prérequis

1. **Compte PayPal Business** : Vous devez avoir un compte PayPal Business (pas un compte personnel)
2. **Compte vérifié** : Votre compte PayPal doit être vérifié et activé
3. **Accès au serveur** : Accès pour modifier le fichier `.env`

## 🔑 Étape 1 : Créer une Application PayPal

1. Connectez-vous à votre compte PayPal Business : https://www.paypal.com/businessmanage/account
2. Allez dans **"Paramètres"** > **"Comptes développeur"** (ou directement : https://developer.paypal.com/)
3. Cliquez sur **"Créer une application"** ou **"My Apps & Credentials"**
4. Choisissez **"Live"** (Production) et non "Sandbox" (Test)
5. Remplissez les informations :
   - **Nom de l'application** : Ex: "CarAgency - Location de Voitures"
   - **Type** : REST API apps
6. Cliquez sur **"Créer l'application"**
7. **Copiez et sauvegardez** :
   - **Client ID** (ex: `AeA1QIZXiflr1_-MoAz5f5p4ZgTVR1G_RBnmw`)
   - **Secret** (ex: `ELg1MHDxqyB8d3bGkFjA7BcC9DdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz`)

⚠️ **IMPORTANT** : 
- Le compte PayPal utilisé pour créer cette application recevra **automatiquement tous les paiements**
- Assurez-vous que c'est le compte où vous voulez recevoir l'argent

## ⚙️ Étape 2 : Configurer l'Application

### Sur votre serveur de production

1. Éditez le fichier `.env` sur votre serveur
2. Ajoutez ou modifiez ces lignes :

```env
# Configuration PayPal Production
PAYPAL_CLIENT_ID=votre_client_id_ici
PAYPAL_CLIENT_SECRET=votre_secret_ici
PAYPAL_TEST_MODE=false

# URL de l'application (IMPORTANT pour les callbacks)
APP_URL=https://votre-domaine.com
APP_ENV=production
```

**Remplacez** :
- `votre_client_id_ici` par votre Client ID PayPal
- `votre_secret_ici` par votre Secret PayPal
- `https://votre-domaine.com` par l'URL réelle de votre site (avec https://)

### Exemple de configuration complète :

```env
PAYPAL_CLIENT_ID=AeA1QIZXiflr1_-MoAz5f5p4ZgTVR1G_RBnmw
PAYPAL_CLIENT_SECRET=ELg1MHDxqyB8d3bGkFjA7BcC9DdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz
PAYPAL_TEST_MODE=false
APP_URL=https://mon-agence-voitures.com
APP_ENV=production
```

## 🔄 Étape 3 : Vider le Cache

Après avoir modifié le fichier `.env`, exécutez ces commandes :

```bash
php artisan config:clear
php artisan cache:clear
```

## ✅ Étape 4 : Vérifier la Configuration

### Vérification automatique

Le système vérifie automatiquement :
- ✅ Si `APP_ENV=production`, le mode test est désactivé par défaut
- ✅ Les URLs de callback sont générées automatiquement :
  - Succès : `https://votre-domaine.com/paypal/success`
  - Annulation : `https://votre-domaine.com/paypal/cancel`

### Test en production

1. Effectuez une réservation de test avec un petit montant
2. Choisissez PayPal comme méthode de paiement
3. Connectez-vous avec un compte PayPal réel (pas un compte de test)
4. Complétez le paiement
5. Vérifiez que l'argent arrive sur votre compte PayPal Business

## 🔍 URLs de Callback PayPal

Les URLs suivantes sont automatiquement configurées et doivent être accessibles publiquement :

- **Succès** : `https://votre-domaine.com/paypal/success`
- **Annulation** : `https://votre-domaine.com/paypal/cancel`

Ces routes sont déjà configurées dans `routes/web.php` et ne nécessitent pas d'authentification CSRF.

## 💰 Comment fonctionne le paiement

1. **Client réserve une voiture** → Complète les étapes de réservation
2. **Client choisit PayPal** → Clique sur le bouton PayPal
3. **Redirection vers PayPal** → Le client est redirigé vers PayPal pour se connecter
4. **Client paie** → Le client confirme le paiement sur PayPal
5. **Retour sur votre site** → PayPal redirige vers `/paypal/success`
6. **Paiement capturé** → L'argent est automatiquement transféré sur votre compte PayPal Business
7. **Réservation confirmée** → Le statut de la réservation passe à "confirmed"

## ⚠️ Points Importants

### Sécurité
- ⚠️ **Ne jamais** partager votre `PAYPAL_CLIENT_SECRET`
- ⚠️ **Ne jamais** commiter le fichier `.env` dans Git
- ⚠️ Utilisez toujours `https://` en production

### Compte PayPal
- ✅ Le compte utilisé pour créer l'application recevra **tous les paiements**
- ✅ Vous devez avoir un **compte PayPal Business** (pas personnel)
- ✅ Le compte doit être **vérifié** et **activé**

### Mode Test vs Production
- **Sandbox (Test)** : `PAYPAL_TEST_MODE=true` → Utilise `https://api.sandbox.paypal.com`
- **Production (Live)** : `PAYPAL_TEST_MODE=false` → Utilise `https://api.paypal.com`

### Devise
- La devise par défaut est **EUR** (Euro)
- Vous pouvez modifier la devise dans `app/Http/Controllers/Client/BookingController.php` ligne 312

## 🐛 Dépannage

### Erreur : "PayPal n'est pas configuré"
- Vérifiez que `PAYPAL_CLIENT_ID` et `PAYPAL_CLIENT_SECRET` sont définis dans `.env`
- Exécutez `php artisan config:clear`

### Erreur : "Impossible d'obtenir l'access token"
- Vérifiez que vos identifiants PayPal sont corrects
- Vérifiez que votre compte PayPal Business est actif
- Consultez les logs : `storage/logs/laravel.log`

### Le paiement ne se complète pas
- Vérifiez que `APP_URL` est correct et accessible
- Vérifiez que les routes `/paypal/success` et `/paypal/cancel` sont accessibles
- Consultez les logs PayPal dans votre compte développeur

### L'argent n'arrive pas sur mon compte
- Vérifiez que vous utilisez le bon compte PayPal Business
- Vérifiez les transactions dans votre compte PayPal
- Vérifiez que le paiement est bien en statut "COMPLETED" dans les logs

## 📞 Support

En cas de problème :
1. Consultez les logs : `storage/logs/laravel.log`
2. Vérifiez votre compte PayPal Developer : https://developer.paypal.com/
3. Consultez la documentation PayPal : https://developer.paypal.com/docs/

## 📝 Notes

- Les paiements PayPal sont traités en temps réel
- Les remboursements peuvent être effectués depuis l'interface d'administration
- Tous les paiements sont enregistrés dans la table `payments` de votre base de données

