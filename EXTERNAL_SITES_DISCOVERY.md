# Guide de Découverte Automatique des Sites Externes

## 🔍 Comment savoir quels sites externes contiennent déjà un véhicule ?

Il existe **3 méthodes** pour découvrir les sites externes qui contiennent un véhicule :

---

## Méthode 1 : Enregistrement automatique par les sites externes (Recommandé)

### Comment ça fonctionne ?

Quand un site externe ajoute un véhicule à son catalogue, il peut **automatiquement s'enregistrer** sur votre site en appelant votre API.

### API Endpoint

**POST** `/api/external-sites/register`

**Body** :
```json
{
  "site_name": "Site A",
  "site_url": "https://site-a.com",
  "api_url": "https://site-a.com/api",
  "api_token": "abc123xyz",
  "registration_number": "ABC-123",
  "external_car_id": "CAR-456"
}
```

**Réponse** :
```json
{
  "success": true,
  "message": "Site externe enregistré avec succès",
  "external_site_id": 1
}
```

### Exemple d'implémentation côté site externe

Quand le site externe ajoute un véhicule, il peut faire cet appel :

```php
// Sur le site externe (site-a.com)
public function addCar(Car $car)
{
    // Ajouter le véhicule dans la base de données locale
    $car->save();
    
    // S'enregistrer automatiquement sur toubcar.com
    Http::post('https://toubcar.com/api/external-sites/register', [
        'site_name' => 'Site A',
        'site_url' => 'https://site-a.com',
        'api_url' => 'https://site-a.com/api',
        'api_token' => config('api.toubcar_token'),
        'registration_number' => $car->registration_number,
        'external_car_id' => $car->id,
    ]);
}
```

**Avantages** :
- ✅ Automatique
- ✅ Pas besoin de configuration manuelle
- ✅ Découverte en temps réel

---

## Méthode 2 : Découverte automatique depuis votre interface

### Comment ça fonctionne ?

Vous pouvez lancer une **recherche automatique** depuis l'interface d'administration pour découvrir les sites qui contiennent un véhicule.

### Utilisation

1. Aller dans la section **Véhicules** de votre agence
2. Sélectionner un véhicule
3. Aller dans **Sites externes**
4. Cliquer sur **"Découvrir automatiquement"**
5. Le système va vérifier une liste de sites connus

### Liste de sites connus

Vous pouvez configurer une liste de sites connus dans le fichier `config/external_sites.php` :

```php
<?php

return [
    'known_sites' => [
        [
            'name' => 'Site A',
            'url' => 'https://site-a.com',
            'api_url' => 'https://site-a.com/api',
            'api_token' => env('SITE_A_API_TOKEN'),
        ],
        [
            'name' => 'Site B',
            'url' => 'https://site-b.com',
            'api_url' => 'https://site-b.com/api',
            'api_token' => env('SITE_B_API_TOKEN'),
        ],
    ],
];
```

### Comment ça fonctionne techniquement ?

Le système :
1. Appelle l'API de chaque site connu
2. Vérifie si le véhicule existe (par `registration_number`)
3. Si le véhicule existe, l'enregistre automatiquement

**Avantages** :
- ✅ Découverte en un clic
- ✅ Pas besoin que les sites externes fassent quelque chose
- ✅ Fonctionne avec une liste de sites connus

**Inconvénients** :
- ⚠️ Nécessite une liste de sites connus
- ⚠️ Peut être plus lent si beaucoup de sites

---

## Méthode 3 : Consultation de l'API de liste

### Comment ça fonctionne ?

Vous pouvez consulter l'API pour voir quels sites externes ont déjà enregistré un véhicule.

### API Endpoint

**GET** `/api/cars/{registration_number}/external-sites`

**Exemple** :
```
GET /api/cars/ABC-123/external-sites
```

**Réponse** :
```json
{
  "success": true,
  "car": {
    "id": 1,
    "brand": "Toyota",
    "model": "Corolla",
    "registration_number": "ABC-123"
  },
  "external_sites": [
    {
      "id": 1,
      "site_name": "Site A",
      "site_url": "https://site-a.com",
      "api_url": "https://site-a.com/api",
      "external_car_id": "CAR-456",
      "created_at": "2024-01-15T10:30:00Z"
    },
    {
      "id": 2,
      "site_name": "Site B",
      "site_url": "https://site-b.com",
      "api_url": "https://site-b.com/api",
      "external_car_id": null,
      "created_at": "2024-01-16T14:20:00Z"
    }
  ],
  "count": 2
}
```

**Avantages** :
- ✅ Consultation rapide
- ✅ Peut être utilisé par d'autres systèmes
- ✅ Format JSON standard

---

## 📋 Comparaison des méthodes

| Méthode | Automatique | Temps réel | Configuration requise |
|---------|-------------|------------|----------------------|
| **Enregistrement par site externe** | ✅ Oui | ✅ Oui | ⚠️ Les sites doivent implémenter |
| **Découverte depuis interface** | ⚠️ Semi | ❌ Non | ✅ Liste de sites connus |
| **Consultation API** | ❌ Non | ✅ Oui | ❌ Aucune |

---

## 🔄 Workflow recommandé

### Pour les sites externes

1. **Quand vous ajoutez un véhicule** → Appelez `/api/external-sites/register`
2. **Quand vous supprimez un véhicule** → (Optionnel) Appelez une API de suppression
3. **Quand vous mettez à jour un véhicule** → (Optionnel) Appelez une API de mise à jour

### Pour votre site (toubcar.com)

1. **Configuration initiale** : Créer une liste de sites connus dans `config/external_sites.php`
2. **Découverte ponctuelle** : Utiliser le bouton "Découvrir automatiquement" dans l'interface
3. **Vérification** : Consulter `/api/cars/{registration_number}/external-sites` pour voir les sites enregistrés

---

## 🛠️ Configuration

### Créer le fichier de configuration

```bash
php artisan config:publish external_sites
```

Ou créer manuellement `config/external_sites.php` :

```php
<?php

return [
    'known_sites' => [
        [
            'name' => 'Site A',
            'url' => env('SITE_A_URL', 'https://site-a.com'),
            'api_url' => env('SITE_A_API_URL', 'https://site-a.com/api'),
            'api_token' => env('SITE_A_API_TOKEN'),
        ],
        // Ajouter d'autres sites...
    ],
];
```

### Variables d'environnement

Ajouter dans votre `.env` :

```env
SITE_A_URL=https://site-a.com
SITE_A_API_URL=https://site-a.com/api
SITE_A_API_TOKEN=abc123xyz

SITE_B_URL=https://site-b.com
SITE_B_API_URL=https://site-b.com/api
SITE_B_API_TOKEN=def456uvw
```

---

## 📝 Exemple complet

### Scénario : Site A ajoute un véhicule

1. **Site A** ajoute le véhicule ABC-123 dans sa base de données
2. **Site A** appelle automatiquement :
   ```
   POST https://toubcar.com/api/external-sites/register
   {
     "site_name": "Site A",
     "site_url": "https://site-a.com",
     "api_url": "https://site-a.com/api",
     "api_token": "abc123",
     "registration_number": "ABC-123",
     "external_car_id": "CAR-456"
   }
   ```
3. **Votre site** enregistre automatiquement Site A comme site externe pour ABC-123
4. **Désormais**, quand quelqu'un consulte ABC-123 sur votre site, le système vérifie automatiquement la disponibilité sur Site A

---

## ❓ Questions Fréquentes

### Q: Que se passe-t-il si un site externe s'enregistre deux fois ?

**R:** Le système détecte les doublons et met à jour l'enregistrement existant au lieu d'en créer un nouveau.

### Q: Puis-je désactiver un site externe découvert automatiquement ?

**R:** Oui, vous pouvez désactiver un site externe depuis l'interface d'administration. Il ne sera plus vérifié mais restera enregistré.

### Q: Comment supprimer un site externe ?

**R:** Vous pouvez supprimer un site externe depuis l'interface. Cela ne supprime pas le véhicule sur le site externe, mais arrête la synchronisation.

### Q: Les sites externes peuvent-ils voir quels autres sites ont le même véhicule ?

**R:** Oui, via l'API `/api/cars/{registration_number}/external-sites`, mais seulement pour les véhicules qu'ils ont enregistrés.

---

## 🔐 Sécurité

- Les tokens d'API sont stockés de manière sécurisée
- Les tokens ne sont pas exposés dans les réponses JSON
- L'authentification peut être ajoutée aux endpoints API si nécessaire

---

## 📞 Support

Pour toute question sur la découverte automatique, contactez le support technique.


