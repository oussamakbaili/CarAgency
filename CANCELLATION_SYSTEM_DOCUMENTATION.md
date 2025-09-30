# Système de Gestion des Annulations d'Agences

## Vue d'ensemble

Ce système permet de gérer les annulations de réservations par les agences avec une logique de suspension automatique après 2-3 annulations. Il inclut des avertissements progressifs et des notifications par email.

## Fonctionnalités

### 1. Suivi des Annulations
- Compteur d'annulations par agence
- Limite configurable (par défaut: 3 annulations)
- Historique détaillé des annulations
- Raisons d'annulation et notes

### 2. Système d'Avertissements
- **1 annulation restante**: Avertissement critique
- **2 annulations restantes**: Avertissement modéré
- **0 annulation restante**: Suspension automatique

### 3. Suspension Automatique
- Suspension après atteinte de la limite
- Blocage de nouvelles réservations
- Notification par email
- Gestion administrative

### 4. Gestion Administrative
- Liste des agences suspendues
- Réactivation manuelle
- Réinitialisation du compteur
- Modification de la limite

## Structure de la Base de Données

### Table `agencies` (colonnes ajoutées)
```sql
cancellation_count INT DEFAULT 0
last_cancellation_at TIMESTAMP NULL
is_suspended BOOLEAN DEFAULT FALSE
suspended_at TIMESTAMP NULL
suspension_reason TEXT NULL
max_cancellations INT DEFAULT 3
```

### Table `agency_cancellation_logs`
```sql
id BIGINT PRIMARY KEY
agency_id BIGINT (FK vers agencies)
rental_id BIGINT (FK vers rentals)
cancellation_reason VARCHAR(255)
notes TEXT
cancelled_at TIMESTAMP
created_at TIMESTAMP
updated_at TIMESTAMP
```

## Utilisation

### 1. Annulation d'une Réservation

```php
use App\Services\AgencyCancellationService;

$cancellationService = new AgencyCancellationService();

$result = $cancellationService->handleCancellation(
    $agency,
    $rental,
    'vehicle_unavailable', // raison
    'Véhicule en panne'    // notes
);

if ($result['success']) {
    // Annulation réussie
    if ($result['suspended']) {
        // Agence suspendue
    }
    if ($result['warning']) {
        // Avertissement affiché
    }
}
```

### 2. Vérification du Statut

```php
// Vérifier si l'agence peut annuler
if ($agency->canCancelBooking()) {
    // Peut annuler
}

// Vérifier si suspendue
if ($agency->isSuspended()) {
    // Agence suspendue
}

// Obtenir le message d'avertissement
$warning = $agency->getCancellationWarningMessage();
```

### 3. Gestion Administrative

```php
// Suspendre une agence
$agency->suspend('Raison de suspension');

// Réactiver une agence
$agency->unsuspend();

// Réinitialiser le compteur
$cancellationService->resetCancellationCount($agency);

// Modifier la limite
$agency->update(['max_cancellations' => 5]);
```

## Messages d'Avertissement

### Français
- **2 annulations restantes**: "Attention: Vous avez annulé X réservation(s). 2 annulation(s) restante(s) avant suspension."
- **1 annulation restante**: "ATTENTION: Vous avez annulé X réservation(s). Une annulation de plus entraînera la suspension de votre compte."
- **Suspendue**: "Votre compte a été suspendu pour trop d'annulations. Contactez l'administrateur."

## Notifications Email

### Avertissement d'Annulation
- Envoyé avant la suspension
- Contient les statistiques d'annulation
- Recommandations pour éviter les suspensions

### Notification de Suspension
- Envoyé lors de la suspension
- Explique les conséquences
- Instructions pour la réactivation

## Routes Disponibles

### Agences
```
GET  /agence/rentals/{rental}/cancel     - Formulaire d'annulation
PATCH /agence/rentals/{rental}/cancel    - Traiter l'annulation
GET  /agence/cancellation/stats          - Statistiques d'annulation
```

### Administration
```
GET  /admin/agencies/suspended                    - Liste des agences suspendues
GET  /admin/agencies/suspension/{agency}          - Détails de suspension
POST /admin/agencies/suspension/{agency}/suspend  - Suspendre une agence
PATCH /admin/agencies/suspension/{agency}/unsuspend - Réactiver une agence
PATCH /admin/agencies/suspension/{agency}/reset-cancellations - Réinitialiser
PATCH /admin/agencies/suspension/{agency}/update-max-cancellations - Modifier limite
```

## Widget Dashboard

Inclure le widget dans le dashboard agence :

```blade
<x-agency-cancellation-widget :agency="$agency" />
```

## Configuration

### Variables d'Environnement
```env
MAIL_FROM_ADDRESS=support@rentcarplatform.com
MAIL_FROM_NAME="RentCar Platform"
```

### Limites par Défaut
- `max_cancellations`: 3
- `cancellation_count`: 0 (réinitialisé lors de la réactivation)

## Sécurité

- Vérification des permissions avant annulation
- Validation des données d'entrée
- Logs d'audit complets
- Protection contre les annulations multiples

## Monitoring

### Métriques Importantes
- Nombre d'agences suspendues
- Taux d'annulation moyen
- Agences proches de la limite
- Temps de réactivation

### Alertes Recommandées
- Nouvelle suspension d'agence
- Agence proche de la limite (1 annulation restante)
- Tentative d'annulation par agence suspendue

## Tests

### Scénarios de Test
1. Annulation normale (sous la limite)
2. Annulation avec avertissement (1 restante)
3. Annulation avec suspension (limite atteinte)
4. Tentative d'annulation par agence suspendue
5. Réactivation administrative
6. Réinitialisation du compteur

### Données de Test
```php
// Créer une agence de test
$agency = Agency::create([
    'user_id' => $user->id,
    'agency_name' => 'Test Agency',
    'cancellation_count' => 0,
    'max_cancellations' => 3,
    'is_suspended' => false
]);

// Tester l'annulation
$rental = Rental::create([...]);
$result = $cancellationService->handleCancellation($agency, $rental, 'test');
```

## Maintenance

### Tâches Régulières
- Nettoyer les anciens logs d'annulation
- Analyser les patterns d'annulation
- Ajuster les limites si nécessaire
- Surveiller les fausses suspensions

### Commandes Artisan
```bash
# Vérifier les agences proches de la limite
php artisan tinker
>>> Agency::whereRaw('cancellation_count >= (max_cancellations - 1)')->get();

# Réinitialiser toutes les suspensions (attention!)
>>> Agency::where('is_suspended', true)->update(['is_suspended' => false, 'cancellation_count' => 0]);
```

## Support

Pour toute question ou problème :
- Email: support@rentcarplatform.com
- Documentation: [Lien vers la documentation complète]
- Issues: [Lien vers le système de tickets]
