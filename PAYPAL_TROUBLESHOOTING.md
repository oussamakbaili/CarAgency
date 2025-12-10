# Guide de Dépannage PayPal

## 🔴 Erreur : "Impossible d'obtenir l'access token PayPal"

Cette erreur se produit lorsque l'application ne peut pas obtenir un token d'accès depuis l'API PayPal. Voici comment la résoudre :

### Étape 1 : Vérifier la configuration

Exécutez la commande de diagnostic :

```bash
php artisan paypal:diagnose
```

Cette commande va :
- ✅ Vérifier si les identifiants PayPal sont configurés
- ✅ Tester la connexion à l'API PayPal
- ✅ Afficher des messages d'erreur détaillés

### Étape 2 : Vérifier le fichier .env

Ouvrez votre fichier `.env` et vérifiez que ces lignes existent :

```env
PAYPAL_CLIENT_ID=votre_client_id_ici
PAYPAL_CLIENT_SECRET=votre_secret_ici
PAYPAL_TEST_MODE=true
```

**Important :**
- Si vous êtes en **production**, mettez `PAYPAL_TEST_MODE=false`
- Si vous êtes en **développement/test**, mettez `PAYPAL_TEST_MODE=true`

### Étape 3 : Vérifier vos identifiants PayPal

#### Pour le mode Test (Sandbox) :
1. Allez sur https://developer.paypal.com/
2. Connectez-vous avec votre compte PayPal
3. Allez dans **"My Apps & Credentials"**
4. Sélectionnez **"Sandbox"** (pas "Live")
5. Créez une nouvelle application ou utilisez une existante
6. Copiez le **Client ID** et le **Secret**

#### Pour le mode Production :
1. Allez sur https://developer.paypal.com/
2. Connectez-vous avec votre compte PayPal **Business**
3. Allez dans **"My Apps & Credentials"**
4. Sélectionnez **"Live"** (pas "Sandbox")
5. Créez une nouvelle application ou utilisez une existante
6. Copiez le **Client ID** et le **Secret**

⚠️ **Attention :** Les identifiants Sandbox et Production sont différents !

### Étape 4 : Vider le cache

Après avoir modifié le fichier `.env`, exécutez :

```bash
php artisan config:clear
php artisan cache:clear
```

### Étape 5 : Vérifier les logs

Consultez les logs Laravel pour plus de détails :

```bash
tail -f storage/logs/laravel.log
```

Ou sur Windows :
```cmd
type storage\logs\laravel.log
```

Les logs contiennent des informations détaillées sur :
- Les identifiants utilisés (masqués pour la sécurité)
- Les erreurs HTTP retournées par PayPal
- Les problèmes de connexion

### Causes courantes de l'erreur

#### 1. Identifiants manquants ou incorrects
**Symptôme :** L'erreur apparaît immédiatement
**Solution :** Vérifiez que `PAYPAL_CLIENT_ID` et `PAYPAL_CLIENT_SECRET` sont bien définis dans `.env`

#### 2. Mauvais mode (Test vs Production)
**Symptôme :** Erreur 401 (Unauthorized)
**Solution :** Assurez-vous que `PAYPAL_TEST_MODE` correspond au type d'identifiants utilisés

#### 3. Compte PayPal non actif
**Symptôme :** Erreur 401 ou 403
**Solution :** Vérifiez que votre compte PayPal Business est actif et vérifié

#### 4. Problème de connexion réseau
**Symptôme :** Timeout ou erreur de connexion
**Solution :** Vérifiez votre connexion internet et les pare-feu

#### 5. Identifiants expirés ou révoqués
**Symptôme :** Erreur 401
**Solution :** Générez de nouveaux identifiants sur le portail développeur PayPal

### Messages d'erreur détaillés

Le système a été amélioré pour afficher des messages d'erreur plus détaillés :

- **"Les identifiants PayPal ne sont pas configurés"** → Ajoutez les variables dans `.env`
- **"Vérifiez que vos identifiants PayPal sont corrects"** → Vérifiez les identifiants sur le portail PayPal
- **"Erreur de connexion"** → Problème réseau ou pare-feu

### Test rapide

Pour tester rapidement si PayPal fonctionne :

```bash
php artisan paypal:diagnose
```

Si la commande affiche ✅, votre configuration est correcte.

### Support supplémentaire

Si le problème persiste :

1. **Vérifiez les logs** : `storage/logs/laravel.log`
2. **Exécutez le diagnostic** : `php artisan paypal:diagnose`
3. **Vérifiez le portail PayPal** : https://developer.paypal.com/
4. **Consultez la documentation PayPal** : https://developer.paypal.com/docs/

### Configuration recommandée

Pour la production, votre `.env` devrait contenir :

```env
# PayPal Production
PAYPAL_CLIENT_ID=your_live_client_id
PAYPAL_CLIENT_SECRET=your_live_secret
PAYPAL_TEST_MODE=false
APP_URL=https://votre-domaine.com
APP_ENV=production
```

Pour le développement/test :

```env
# PayPal Sandbox (Test)
PAYPAL_CLIENT_ID=your_sandbox_client_id
PAYPAL_CLIENT_SECRET=your_sandbox_secret
PAYPAL_TEST_MODE=true
APP_URL=http://localhost:8000
APP_ENV=local
```

---

**Note :** Après chaque modification du fichier `.env`, n'oubliez pas d'exécuter `php artisan config:clear` !

