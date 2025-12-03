# Guide Complet : Configuration de la Clé API Google Maps

## 📋 Prérequis
- Un compte Google (Gmail)
- Un projet Laravel fonctionnel

---

## 🔑 Étape 1 : Accéder à Google Cloud Console

1. **Ouvrez votre navigateur** et allez sur : https://console.cloud.google.com/
2. **Connectez-vous** avec votre compte Google
3. Si c'est votre première fois, acceptez les conditions d'utilisation

---

## 🆕 Étape 2 : Créer un Nouveau Projet

1. **Cliquez sur le menu déroulant** en haut à gauche (à côté de "Google Cloud")
2. **Cliquez sur "Nouveau projet"** (ou "New Project")
3. **Remplissez les informations** :
   - **Nom du projet** : `CarAgency Maps` (ou un nom de votre choix)
   - **Organisation** : Laissez par défaut si vous n'avez pas d'organisation
4. **Cliquez sur "Créer"** (ou "Create")
5. **Attendez quelques secondes** que le projet soit créé
6. **Sélectionnez votre nouveau projet** dans le menu déroulant en haut

---

## 🗺️ Étape 3 : Activer l'API Maps JavaScript

1. **Dans le menu de gauche**, cliquez sur **"APIs & Services"** > **"Library"** (Bibliothèque)
2. **Dans la barre de recherche**, tapez : `Maps JavaScript API`
3. **Cliquez sur "Maps JavaScript API"** dans les résultats
4. **Cliquez sur le bouton "ENABLE"** (Activer) en haut de la page
5. **Attendez quelques secondes** que l'API soit activée

**Note** : Google peut aussi vous demander d'activer d'autres APIs liées :
   - **Geocoding API** (recommandé pour la recherche d'adresses)
   - **Places API** (recommandé pour l'autocomplete d'adresses)

Pour les activer :
- Retournez à la bibliothèque
- Recherchez "Geocoding API" et activez-la
- Recherchez "Places API" et activez-la

---

## 🔐 Étape 4 : Créer une Clé API

1. **Dans le menu de gauche**, cliquez sur **"APIs & Services"** > **"Credentials"** (Identifiants)
2. **En haut de la page**, cliquez sur **"+ CREATE CREDENTIALS"** (Créer des identifiants)
3. **Sélectionnez "API key"** dans le menu déroulant
4. **Une clé API sera générée automatiquement** et affichée dans une popup
5. **⚠️ IMPORTANT** : **Copiez la clé immédiatement** (elle ressemble à : `AIzaSyBxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx`)
   - Vous ne pourrez plus la voir en entier après avoir fermé cette fenêtre !
6. **Cliquez sur "RESTRICT KEY"** (Restreindre la clé) pour la sécuriser (recommandé)

---

## 🔒 Étape 5 : Restreindre la Clé API (Sécurité - Recommandé)

### 5.1 Restrictions d'application

1. **Dans la section "Application restrictions"**, sélectionnez :
   - **"HTTP referrers (web sites)"** pour une application web
2. **Cliquez sur "+ ADD AN ITEM"**
3. **Ajoutez vos domaines** :
   - Pour le développement local : `http://localhost/*`
   - Pour votre domaine de production : `https://votre-domaine.com/*`
   - Exemple : `https://toubcar.com/*`
   - Exemple : `http://localhost:8000/*`

### 5.2 Restrictions d'API

1. **Dans la section "API restrictions"**, sélectionnez :
   - **"Restrict key"**
2. **Sélectionnez les APIs** que vous voulez autoriser :
   - ✅ **Maps JavaScript API**
   - ✅ **Geocoding API** (si activée)
   - ✅ **Places API** (si activée)
3. **Cliquez sur "SAVE"** (Enregistrer)

---

## 💾 Étape 6 : Ajouter la Clé dans votre Projet Laravel

### 6.1 Ajouter dans le fichier .env

1. **Ouvrez votre fichier `.env`** à la racine de votre projet
2. **Ajoutez cette ligne** (ou modifiez-la si elle existe déjà) :

```env
GOOGLE_MAPS_KEY=AIzaSyBxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

**Remplacez** `AIzaSyBxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx` par votre vraie clé API copiée à l'étape 4.

3. **Sauvegardez le fichier**

### 6.2 Vérifier la configuration dans config/services.php

Le fichier `config/services.php` devrait déjà contenir :

```php
'google' => [
    'maps_key' => env('GOOGLE_MAPS_KEY'),
],
```

✅ **C'est déjà fait !** (Nous l'avons ajouté précédemment)

---

## 🔄 Étape 7 : Vider le Cache Laravel

**Ouvrez votre terminal** dans le dossier de votre projet et exécutez :

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

---

## ✅ Étape 8 : Tester la Configuration

### Test 1 : Page de détails d'un véhicule

1. **Allez sur votre site** : `http://localhost:8000` (ou votre URL)
2. **Cliquez sur une carte de véhicule**
3. **Faites défiler jusqu'en bas de la page**
4. **Vous devriez voir une carte Google Maps** avec la localisation de l'agence

### Test 2 : Formulaire d'enregistrement d'agence

1. **Allez sur** : `http://localhost:8000/register/agency`
2. **Faites défiler jusqu'au champ "Adresse"**
3. **Vous devriez voir une carte Google Maps** en dessous du champ
4. **Testez** :
   - Tapez une adresse dans le champ (l'autocomplete devrait fonctionner)
   - Cliquez sur la carte pour placer un marqueur
   - Déplacez le marqueur

---

## 🐛 Dépannage

### Problème : "Cette page ne peut pas charger Google Maps correctement"

**Solutions** :
1. Vérifiez que votre clé API est correcte dans le `.env`
2. Vérifiez que vous avez vidé le cache : `php artisan config:clear`
3. Vérifiez que l'API "Maps JavaScript API" est bien activée
4. Vérifiez les restrictions de votre clé API (domaines autorisés)

### Problème : "RefererNotAllowedMapError"

**Solution** : 
- Ajoutez votre domaine dans les restrictions HTTP referrers de votre clé API
- Pour le développement local, ajoutez : `http://localhost/*`

### Problème : La carte ne s'affiche pas du tout

**Solutions** :
1. Ouvrez la console du navigateur (F12) et vérifiez les erreurs
2. Vérifiez que la clé API est bien définie dans `.env`
3. Vérifiez que vous avez bien exécuté `php artisan config:clear`
4. Vérifiez que votre clé API n'a pas de restrictions trop strictes

### Problème : L'autocomplete ne fonctionne pas

**Solution** :
- Activez l'API "Places API" dans Google Cloud Console
- Ajoutez-la aux restrictions de votre clé API

---

## 💰 Coûts et Limites

### Gratuit (Free Tier)
- **28 500 chargements de carte par mois** (gratuit)
- **40 000 requêtes Geocoding par mois** (gratuit)
- **17 000 requêtes Places Autocomplete par mois** (gratuit)

### Au-delà du gratuit
- Maps JavaScript API : **$7 par 1000 chargements**
- Geocoding API : **$5 par 1000 requêtes**
- Places API : **$17 par 1000 requêtes**

**Note** : Pour la plupart des applications, le quota gratuit est largement suffisant.

---

## 🔐 Sécurité - Bonnes Pratiques

1. ✅ **Toujours restreindre votre clé API** par domaine
2. ✅ **Ne jamais commiter votre fichier `.env`** dans Git
3. ✅ **Utilisez des clés différentes** pour le développement et la production
4. ✅ **Surveillez l'utilisation** dans Google Cloud Console
5. ✅ **Activez les alertes** si vous dépassez le quota gratuit

---

## 📝 Résumé des Étapes Rapides

1. ✅ Aller sur https://console.cloud.google.com/
2. ✅ Créer un nouveau projet
3. ✅ Activer "Maps JavaScript API"
4. ✅ Créer une clé API
5. ✅ Restreindre la clé (domaines + APIs)
6. ✅ Ajouter `GOOGLE_MAPS_KEY=votre_cle` dans `.env`
7. ✅ Exécuter `php artisan config:clear`
8. ✅ Tester sur votre site

---

## 🆘 Besoin d'Aide ?

Si vous rencontrez des problèmes :
1. Vérifiez la console du navigateur (F12) pour les erreurs
2. Vérifiez les logs Laravel : `storage/logs/laravel.log`
3. Vérifiez que toutes les APIs sont activées dans Google Cloud Console
4. Vérifiez que votre clé API n'est pas expirée ou désactivée

---

**Félicitations !** 🎉 Votre configuration Google Maps est maintenant prête !

