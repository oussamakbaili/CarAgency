# Guide de Déploiement - CarAgency

## Résolution du problème de divergence de branches

Après un force push sur GitHub, votre serveur peut avoir des branches divergentes. Utilisez le script de déploiement automatique pour résoudre ce problème.

### Solution Rapide (Recommandée)

Sur votre serveur SSH, exécutez simplement :

```bash
cd public_html
chmod +x deploy.sh
./deploy.sh
```

### Solution Manuelle

Si vous préférez faire manuellement :

```bash
cd public_html
git fetch origin
git reset --hard origin/master
git clean -fd
```

### Détails du Script de Déploiement

Le script `deploy.sh` effectue automatiquement :
1. ✅ Vérification de l'état du dépôt
2. ✅ Récupération des dernières modifications depuis GitHub
3. ✅ Sauvegarde des modifications locales non commitées (dans stash)
4. ✅ Synchronisation complète avec origin/master
5. ✅ Nettoyage des fichiers non trackés
6. ✅ Affichage du statut final

### Notes Importantes

- Le script sauvegarde automatiquement vos modifications locales avant de réinitialiser
- Si vous avez des modifications importantes, elles seront dans le stash Git
- Utilisez `git stash list` pour voir les sauvegardes
- Utilisez `git stash pop` pour réappliquer une sauvegarde

### En cas de problème

Si le script échoue, vérifiez :
1. Les permissions d'exécution : `chmod +x deploy.sh`
2. Que vous êtes dans le bon répertoire : `cd public_html`
3. La connexion à GitHub : `git fetch origin`

## Configuration PayPal pour la Production

Pour que PayPal fonctionne correctement en production, vous devez configurer les variables d'environnement suivantes dans votre fichier `.env` :

### Variables d'environnement requises

```env
# Configuration PayPal
PAYPAL_CLIENT_ID=votre_client_id_paypal_production
PAYPAL_CLIENT_SECRET=votre_client_secret_paypal_production
PAYPAL_TEST_MODE=false

# URL de l'application (IMPORTANT pour les callbacks PayPal)
APP_URL=https://votre-domaine.com
APP_ENV=production
```

### Compte PayPal qui reçoit les paiements

**Oui, c'est le même compte !** 

Le compte PayPal Business que vous utilisez pour :
- ✅ Créer l'application et obtenir le `Client ID` et le `Secret`
- ✅ Recevoir les paiements des clients lors des réservations

**Important :** 
- L'argent des paiements sera automatiquement crédité sur le compte PayPal associé à l'application
- Si vous créez l'application avec le compte `votre-email@example.com`, c'est ce compte qui recevra tous les paiements
- Assurez-vous que ce compte est bien un compte PayPal Business et qu'il est vérifié

### Étapes de configuration

1. **Obtenir les identifiants PayPal de production** :
   - Connectez-vous à votre compte PayPal Business (c'est ce compte qui recevra l'argent)
   - Allez dans "Paramètres" > "Comptes développeur"
   - Créez une application si nécessaire
   - Copiez le `Client ID` et le `Secret`
   - ⚠️ **Note** : Utilisez le compte PayPal Business où vous voulez recevoir les paiements

2. **Configurer les variables d'environnement** :
   - Éditez votre fichier `.env` sur le serveur
   - Ajoutez les variables ci-dessus avec vos identifiants réels
   - **Important** : Assurez-vous que `APP_URL` correspond exactement à l'URL de votre site en production (avec https://)

3. **Désactiver le mode test** :
   - Définissez `PAYPAL_TEST_MODE=false` en production
   - Le système détectera automatiquement si `APP_ENV=production` pour désactiver le mode test par défaut

4. **Vérifier la configuration** :
   - Après avoir configuré les variables, videz le cache de configuration :
     ```bash
     php artisan config:clear
     php artisan cache:clear
     ```

### URLs de callback PayPal

Les URLs de retour PayPal sont automatiquement générées en fonction de `APP_URL` :
- URL de succès : `https://votre-domaine.com/paypal/success`
- URL d'annulation : `https://votre-domaine.com/paypal/cancel`

Ces URLs doivent être accessibles publiquement (sans authentification) pour que PayPal puisse rediriger les utilisateurs après le paiement.

### Vérification en production

1. Testez un paiement PayPal avec un compte PayPal de test
2. Vérifiez que les callbacks fonctionnent correctement
3. Consultez les logs (`storage/logs/laravel.log`) en cas d'erreur

### Notes importantes

- ⚠️ **Ne jamais** utiliser les identifiants de test (sandbox) en production
- ⚠️ Assurez-vous que `APP_URL` utilise `https://` en production
- ⚠️ Les routes `/paypal/success` et `/paypal/cancel` sont publiques et ne nécessitent pas d'authentification CSRF
- ⚠️ **Compte PayPal** : Le compte utilisé pour créer l'application recevra automatiquement tous les paiements. Vérifiez que c'est le bon compte Business
- ⚠️ **Compte Business requis** : Vous devez avoir un compte PayPal Business (pas un compte personnel) pour recevoir des paiements commerciaux

