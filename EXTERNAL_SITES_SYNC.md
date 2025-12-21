# Guide de Synchronisation de Disponibilité entre Sites

## 🔄 Comment fonctionne la synchronisation ?

La synchronisation fonctionne par **appels HTTP entre les sites**. Chaque site expose une API que les autres sites peuvent appeler pour vérifier la disponibilité d'un véhicule.

---

## 📡 Architecture de Communication

```
┌─────────────────┐                    ┌─────────────────┐
│   Mon Site      │                    │  Site Externe A │
│  (toubcar.com)  │                    │  (site-a.com)   │
│                 │                    │                 │
│  ┌───────────┐  │  HTTP POST         │  ┌───────────┐  │
│  │ Service   │──┼───────────────────>│  │   API     │  │
│  │ External  │  │  Vérifier          │  │ Endpoint  │  │
│  │ Sites     │  │  disponibilité     │  │           │  │
│  └───────────┘  │<───────────────────┼  └───────────┘  │
│                 │  Réponse JSON      │                 │
│                 │  {"available":true} │                 │
└─────────────────┘                    └─────────────────┘
         │                                       │
         │                                       │
         ▼                                       ▼
    ┌─────────────────┐                    ┌─────────────────┐
    │  Site Externe B │                    │  Site Externe C │
    │  (site-b.com)   │                    │  (site-c.com)   │
    └─────────────────┘                    └─────────────────┘
```

---

## 🔍 Comment MON site vérifie la disponibilité sur les autres sites

### Étape 1 : Configuration

Quand une agence partage un véhicule sur un site externe, elle configure :
- **Nom du site** : "Site A"
- **URL du site** : `https://site-a.com`
- **URL de l'API** : `https://site-a.com/api`
- **Token d'authentification** : `abc123xyz` (optionnel)
- **ID du véhicule sur le site externe** : `CAR-456` (optionnel)

### Étape 2 : Vérification automatique

Quand un client veut réserver un véhicule sur **mon site**, le système :

1. **Vérifie la disponibilité locale** (mon site)
2. **Appelle l'API de chaque site externe** pour vérifier la disponibilité
3. **Combine les résultats** :
   - ✅ Disponible si disponible **partout** (local + tous les sites externes)
   - ❌ Indisponible si indisponible **n'importe où**

### Exemple de requête HTTP

Mon site envoie cette requête au site externe :

```http
POST https://site-a.com/api/cars/check-availability
Authorization: Bearer abc123xyz
Content-Type: application/json

{
  "registration_number": "ABC-123",
  "car_identifier": "CAR-456",
  "start_date": "2024-01-15",
  "end_date": "2024-01-20"
}
```

### Réponse attendue du site externe

```json
{
  "success": true,
  "available": false,
  "car": {
    "id": 123,
    "brand": "Toyota",
    "model": "Corolla",
    "registration_number": "ABC-123"
  }
}
```

---

## 🌐 Comment les AUTRES sites doivent implémenter leur API

### 1. Créer l'endpoint API

Les autres sites doivent créer un endpoint qui accepte les requêtes de vérification de disponibilité.

#### Exemple en PHP (Laravel)

```php
<?php
// routes/api.php
Route::post('/cars/check-availability', [CarController::class, 'checkAvailability'])
    ->middleware('api'); // ou un middleware d'authentification personnalisé
```

```php
<?php
// app/Http/Controllers/CarController.php
public function checkAvailability(Request $request)
{
    // Valider les données
    $request->validate([
        'registration_number' => 'required|string',
        'car_identifier' => 'nullable|string',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
    ]);

    // Trouver le véhicule
    $identifier = $request->input('car_identifier') ?? $request->input('registration_number');
    $car = Car::where('registration_number', $identifier)
              ->orWhere('external_id', $identifier)
              ->first();

    if (!$car) {
        return response()->json([
            'success' => false,
            'error' => 'Véhicule non trouvé',
            'available' => false,
        ], 404);
    }

    // Vérifier la disponibilité
    $isAvailable = $car->is_available;

    // Si des dates sont fournies, vérifier aussi pour ces dates
    if ($request->has('start_date') && $request->has('end_date')) {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        // Vérifier s'il y a des réservations conflictuelles
        $hasConflict = Rental::where('car_id', $car->id)
            ->whereIn('status', ['pending', 'active'])
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                      });
            })
            ->exists();

        $isAvailable = $isAvailable && !$hasConflict;
    }

    return response()->json([
        'success' => true,
        'available' => $isAvailable,
        'car' => [
            'id' => $car->id,
            'brand' => $car->brand,
            'model' => $car->model,
            'registration_number' => $car->registration_number,
        ],
    ]);
}
```

#### Exemple en Node.js (Express)

```javascript
// routes/api.js
router.post('/cars/check-availability', async (req, res) => {
  try {
    const { registration_number, car_identifier, start_date, end_date } = req.body;

    // Trouver le véhicule
    const identifier = car_identifier || registration_number;
    const car = await Car.findOne({
      $or: [
        { registration_number: identifier },
        { external_id: identifier }
      ]
    });

    if (!car) {
      return res.status(404).json({
        success: false,
        error: 'Véhicule non trouvé',
        available: false
      });
    }

    // Vérifier la disponibilité de base
    let isAvailable = car.is_available;

    // Si des dates sont fournies, vérifier les réservations
    if (start_date && end_date) {
      const hasConflict = await Rental.exists({
        car_id: car._id,
        status: { $in: ['pending', 'active'] },
        $or: [
          { start_date: { $gte: start_date, $lte: end_date } },
          { end_date: { $gte: start_date, $lte: end_date } },
          { 
            start_date: { $lte: start_date },
            end_date: { $gte: end_date }
          }
        ]
      });

      isAvailable = isAvailable && !hasConflict;
    }

    res.json({
      success: true,
      available: isAvailable,
      car: {
        id: car._id,
        brand: car.brand,
        model: car.model,
        registration_number: car.registration_number
      }
    });
  } catch (error) {
    res.status(500).json({
      success: false,
      error: error.message
    });
  }
});
```

---

## 🔐 Authentification (Optionnel mais Recommandé)

Pour sécuriser l'API, les sites peuvent utiliser un token d'authentification.

### Côté serveur (site externe)

```php
// Middleware d'authentification
public function handle($request, Closure $next)
{
    $token = $request->bearerToken();
    
    if (!$token || $token !== config('api.external_sites_token')) {
        return response()->json([
            'success' => false,
            'error' => 'Non autorisé'
        ], 401);
    }
    
    return $next($request);
}
```

### Côté client (mon site)

Le token est automatiquement envoyé dans le header `Authorization: Bearer {token}`.

---

## 📋 Format des Requêtes et Réponses

### Requête POST

**URL** : `{api_url}/api/cars/check-availability`

**Headers** :
```
Authorization: Bearer {api_token}
Content-Type: application/json
Accept: application/json
```

**Body** :
```json
{
  "registration_number": "ABC-123",      // Requis (ou car_identifier)
  "car_identifier": "CAR-456",          // Optionnel (ID sur le site externe)
  "start_date": "2024-01-15",           // Optionnel
  "end_date": "2024-01-20"              // Optionnel
}
```

### Réponse Succès (200)

```json
{
  "success": true,
  "available": true,
  "car": {
    "id": 123,
    "brand": "Toyota",
    "model": "Corolla",
    "registration_number": "ABC-123"
  }
}
```

### Réponse Erreur (404)

```json
{
  "success": false,
  "error": "Véhicule non trouvé",
  "available": false
}
```

### Réponse Erreur (400)

```json
{
  "success": false,
  "error": "Données invalides",
  "details": {
    "registration_number": ["Le champ registration_number est requis."]
  }
}
```

---

## 🔄 Flux de Synchronisation

### Scénario 1 : Vérification lors de l'affichage d'un véhicule

```
1. Client visite la page d'un véhicule
   ↓
2. Mon site vérifie la disponibilité locale
   ↓
3. Mon site appelle les APIs des sites externes (en parallèle)
   ↓
4. Mon site combine les résultats
   ↓
5. Affiche "Disponible" ou "Indisponible"
```

### Scénario 2 : Vérification lors d'une réservation

```
1. Client sélectionne des dates et clique sur "Réserver"
   ↓
2. Mon site vérifie la disponibilité locale pour ces dates
   ↓
3. Mon site appelle les APIs des sites externes avec les dates
   ↓
4. Si disponible partout → Créer la réservation
   ↓
5. Si indisponible quelque part → Afficher une erreur
```

### Scénario 3 : Vérification en temps réel

```
1. Un client réserve un véhicule sur le Site A
   ↓
2. Le Site A met à jour sa base de données
   ↓
3. Mon site vérifie la disponibilité (avec cache de 60 secondes)
   ↓
4. Mon site détecte que le véhicule n'est plus disponible
   ↓
5. Mon site affiche le véhicule comme "Indisponible"
```

---

## ⚡ Optimisations

### Cache

Mon site utilise un cache de **60 secondes** pour éviter trop de requêtes :

```php
Cache::remember($cacheKey, 60, function () {
    // Appel HTTP vers le site externe
});
```

### Timeout

Les requêtes ont un timeout de **10 secondes** pour éviter les blocages.

### Gestion d'erreurs

En cas d'erreur (site externe inaccessible, timeout, etc.), le véhicule est considéré comme **indisponible par sécurité**.

---

## 📝 Checklist pour les Sites Externes

Pour que la synchronisation fonctionne, chaque site externe doit :

- [ ] Exposer un endpoint API : `POST /api/cars/check-availability`
- [ ] Accepter les paramètres : `registration_number`, `car_identifier`, `start_date`, `end_date`
- [ ] Retourner un JSON avec : `{"success": true, "available": bool}`
- [ ] Gérer les erreurs (véhicule non trouvé, dates invalides, etc.)
- [ ] (Optionnel) Implémenter l'authentification par token
- [ ] (Optionnel) Utiliser HTTPS pour sécuriser les communications

---

## 🧪 Test de l'API

### Test avec cURL

```bash
curl -X POST https://site-a.com/api/cars/check-availability \
  -H "Authorization: Bearer abc123xyz" \
  -H "Content-Type: application/json" \
  -d '{
    "registration_number": "ABC-123",
    "start_date": "2024-01-15",
    "end_date": "2024-01-20"
  }'
```

### Test avec Postman

1. Méthode : `POST`
2. URL : `https://site-a.com/api/cars/check-availability`
3. Headers :
   - `Authorization: Bearer abc123xyz`
   - `Content-Type: application/json`
4. Body (raw JSON) :
```json
{
  "registration_number": "ABC-123",
  "start_date": "2024-01-15",
  "end_date": "2024-01-20"
}
```

---

## ❓ Questions Fréquentes

### Q: Que se passe-t-il si un site externe est inaccessible ?

**R:** Le véhicule est considéré comme **indisponible par sécurité** pour éviter les doubles réservations.

### Q: À quelle fréquence les vérifications sont-elles effectuées ?

**R:** Les vérifications sont effectuées :
- En temps réel lors de l'affichage d'un véhicule
- Avant chaque réservation
- Avec un cache de 60 secondes pour optimiser les performances

### Q: Les sites externes peuvent-ils aussi vérifier la disponibilité sur mon site ?

**R:** Oui ! Mon site expose aussi une API : `POST /api/cars/check-availability`

### Q: Comment identifier un véhicule sur un site externe ?

**R:** Vous pouvez utiliser :
- Le `registration_number` (numéro d'immatriculation) - standard
- Le `car_identifier` (ID spécifique sur le site externe) - si différent

---

## 📞 Support

Pour toute question sur l'implémentation, contactez le support technique.

