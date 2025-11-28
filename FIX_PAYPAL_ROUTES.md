# Correction du problème 404 pour les routes PayPal

## Problème
L'URL `/paypal/success` retourne une erreur 404, ce qui signifie que les routes PayPal ne sont pas correctement enregistrées ou que le cache des routes est obsolète.

## Solution : Commandes à exécuter sur votre serveur SSH

### Étape 1 : Vider tous les caches

```bash
# Vider le cache des routes (IMPORTANT)
php artisan route:clear

# Vider le cache de configuration
php artisan config:clear

# Vider le cache général
php artisan cache:clear

# Vider le cache des vues
php artisan view:clear
```

### Étape 2 : Vérifier que les routes sont enregistrées

```bash
# Lister toutes les routes PayPal
php artisan route:list --name=paypal

# Ou voir toutes les routes et chercher PayPal
php artisan route:list | grep -i paypal
```

Vous devriez voir :
- `paypal.success` → GET `/paypal/success`
- `paypal.cancel` → GET `/paypal/cancel`

### Étape 3 : Vérifier que le contrôleur existe

```bash
# Vérifier que le fichier PayPalController existe
ls -la app/Http/Controllers/PayPalController.php
```

### Étape 4 : Vérifier les permissions

```bash
# Vérifier les permissions du répertoire public
ls -la public/.htaccess

# Si le fichier .htaccess n'existe pas ou est mal configuré, vérifiez qu'il est présent
```

### Étape 5 : Vérifier la configuration du serveur web

Assurez-vous que votre serveur web (Apache/Nginx) pointe vers le répertoire `public` de Laravel.

Pour Apache, le DocumentRoot doit pointer vers :
```
/path/to/your/project/public
```

### Étape 6 : Tester les routes

Après avoir vidé le cache, testez à nouveau les URLs :
- `https://votre-domaine.com/paypal/success`
- `https://votre-domaine.com/paypal/cancel`

## Si le problème persiste

### Vérifier les logs

```bash
# Voir les dernières erreurs
tail -n 50 storage/logs/laravel.log
```

### Vérifier que les routes sont bien chargées

```bash
# Tester si Laravel peut charger les routes
php artisan route:list | head -20
```

### Vérifier la structure du projet

Assurez-vous que vous êtes dans le bon répertoire :
```bash
# Vérifier que vous êtes dans le répertoire racine du projet Laravel
pwd
ls -la
# Vous devriez voir : app/, config/, routes/, public/, etc.
```

## Solution alternative : Recréer le cache des routes

Si le problème persiste, vous pouvez recréer le cache :

```bash
# Recréer le cache des routes
php artisan route:cache

# Puis tester à nouveau
```

**Note :** En développement, il est recommandé de ne PAS utiliser `route:cache` car cela peut causer des problèmes. Utilisez plutôt `route:clear`.

## Vérification finale

Une fois les caches vidés, testez :

1. Allez sur votre site
2. Réservez une voiture
3. Choisissez PayPal comme méthode de paiement
4. Complétez le paiement
5. Vérifiez que vous êtes redirigé vers `/paypal/success` sans erreur 404

