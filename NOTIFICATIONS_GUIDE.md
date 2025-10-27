# 🔔 Guide d'Utilisation - Système de Notifications

## 🚀 Démarrage Rapide

### 1. Migration de la Base de Données
La migration a déjà été exécutée. Si vous avez besoin de la réexécuter :

```bash
php artisan migrate
```

### 2. Tester avec des Notifications de Démonstration

Pour générer des notifications de test :

```bash
php artisan notifications:generate-test
```

Ou pour une agence spécifique :

```bash
php artisan notifications:generate-test 1
```

Cela créera 8 notifications variées :
- ✅ 3 nouvelles réservations (non lues)
- ✅ 1 paiement reçu (lu)
- ✅ 1 maintenance nécessaire (non lue)
- ✅ 1 nouvel avis (lu)
- ✅ 1 rappel de location (non lu)
- ✅ 1 stock faible (lu)
- ✅ 1 annulation (lu)

---

## 📝 Utilisation du Helper

### Créer une Notification de Nouvelle Réservation

```php
use App\Helpers\NotificationHelper;

NotificationHelper::notifyNewBooking($agencyId, $rental, $car, $customer);
```

### Créer une Notification de Paiement

```php
NotificationHelper::notifyPaymentReceived($agencyId, $payment, $rental);
```

### Créer une Notification de Maintenance

```php
NotificationHelper::notifyMaintenanceRequired($agencyId, $car, 'Révision des 10,000 km requise');
```

### Créer une Notification de Stock Faible

```php
NotificationHelper::notifyLowStock($agencyId, $car);
```

### Créer une Notification d'Avis Client

```php
NotificationHelper::notifyNewReview($agencyId, $review, $customer, $car);
```

### Créer une Notification de Rappel

```php
// Location qui commence demain
NotificationHelper::notifyRentalStartingSoon($agencyId, $rental, $car, $customer);

// Location qui se termine demain
NotificationHelper::notifyRentalEndingSoon($agencyId, $rental, $car, $customer);
```

### Créer une Notification d'Annulation

```php
NotificationHelper::notifyBookingCancelled($agencyId, $rental, $car, $customer, 'client');
// ou 'admin' si annulé par l'admin
```

---

## 🎨 Personnaliser une Notification

Si vous voulez créer une notification personnalisée :

```php
use App\Models\Notification;

Notification::create([
    'agency_id' => $agencyId,
    'type' => 'custom',                    // Type personnalisé
    'title' => 'Titre court',              // Max 255 caractères
    'message' => 'Message détaillé...',     // Texte complet
    'icon' => 'bell',                       // bell, car, calendar, money, check, alert, user
    'icon_color' => 'blue',                 // blue, green, orange, red, purple, yellow
    'action_url' => route('some.route'),    // URL de redirection (optionnel)
    'related_id' => $entity->id,            // ID de l'entité liée (optionnel)
]);
```

---

## 🔧 Maintenance

### Nettoyer les Anciennes Notifications

Pour supprimer les notifications lues de plus de 30 jours :

```php
use App\Helpers\NotificationHelper;

NotificationHelper::cleanupOldNotifications(30);
```

Vous pouvez créer une tâche planifiée dans `app/Console/Kernel.php` :

```php
protected function schedule(Schedule $schedule)
{
    // Nettoyer les notifications tous les jours à 2h du matin
    $schedule->call(function () {
        \App\Helpers\NotificationHelper::cleanupOldNotifications(30);
    })->daily()->at('02:00');
}
```

---

## 📊 Récupérer les Notifications

### Dans un Contrôleur

```php
use App\Models\Notification;

// Toutes les notifications de l'agence
$notifications = Notification::where('agency_id', $agencyId)
    ->orderBy('created_at', 'desc')
    ->get();

// Notifications non lues uniquement
$unreadNotifications = Notification::where('agency_id', $agencyId)
    ->unread()
    ->get();

// Nombre de notifications non lues
$unreadCount = Notification::where('agency_id', $agencyId)
    ->unread()
    ->count();

// 10 dernières notifications
$recentNotifications = Notification::where('agency_id', $agencyId)
    ->recent(10)
    ->get();
```

### Dans une Vue Blade

```blade
@php
    $notifications = \App\Models\Notification::where('agency_id', auth()->user()->agency->id)
        ->recent(5)
        ->get();
@endphp

@foreach($notifications as $notification)
    <div class="notification {{ $notification->is_read ? 'read' : 'unread' }}">
        <h4>{{ $notification->title }}</h4>
        <p>{{ $notification->message }}</p>
        <small>{{ $notification->time_ago }}</small>
    </div>
@endforeach
```

---

## 🎯 Cas d'Usage Avancés

### 1. Envoyer une Notification à Plusieurs Agences

```php
$agencyIds = [1, 2, 3];

foreach ($agencyIds as $agencyId) {
    Notification::create([
        'agency_id' => $agencyId,
        'type' => 'announcement',
        'title' => 'Mise à jour de la plateforme',
        'message' => 'Nouvelle fonctionnalité disponible : Gestion des assurances',
        'icon' => 'bell',
        'icon_color' => 'blue',
        'action_url' => route('agence.dashboard'),
    ]);
}
```

### 2. Marquer Toutes les Notifications Comme Lues

```php
Notification::where('agency_id', $agencyId)
    ->unread()
    ->update([
        'is_read' => true,
        'read_at' => now(),
    ]);
```

### 3. Supprimer une Notification

```php
$notification = Notification::find($id);
if ($notification && $notification->agency_id === $agencyId) {
    $notification->delete();
}
```

### 4. Vérifier si une Notification Existe Déjà

Pour éviter les doublons :

```php
$exists = Notification::where('agency_id', $agencyId)
    ->where('type', 'booking')
    ->where('related_id', $rental->id)
    ->exists();

if (!$exists) {
    NotificationHelper::notifyNewBooking($agencyId, $rental, $car, $customer);
}
```

---

## 🔔 Événements Laravel (Optionnel)

Pour une architecture plus avancée, vous pouvez créer des événements Laravel :

### 1. Créer un Événement

```bash
php artisan make:event BookingCreated
```

### 2. Définir l'Événement

```php
namespace App\Events;

use App\Models\Rental;
use Illuminate\Foundation\Events\Dispatchable;

class BookingCreated
{
    use Dispatchable;

    public function __construct(public Rental $rental)
    {
    }
}
```

### 3. Créer un Listener

```bash
php artisan make:listener SendBookingNotification --event=BookingCreated
```

### 4. Définir le Listener

```php
namespace App\Listeners;

use App\Events\BookingCreated;
use App\Helpers\NotificationHelper;

class SendBookingNotification
{
    public function handle(BookingCreated $event)
    {
        $rental = $event->rental;
        NotificationHelper::notifyNewBooking(
            $rental->agency_id,
            $rental,
            $rental->car,
            $rental->user
        );
    }
}
```

### 5. Enregistrer le Listener

Dans `app/Providers/EventServiceProvider.php` :

```php
protected $listen = [
    BookingCreated::class => [
        SendBookingNotification::class,
    ],
];
```

### 6. Déclencher l'Événement

```php
use App\Events\BookingCreated;

event(new BookingCreated($rental));
```

---

## 📱 Interface Utilisateur

### Badge de Notification

Le badge apparaît automatiquement quand il y a des notifications non lues :

```html
<!-- Badge automatique avec Alpine.js -->
<span x-show="unreadCount > 0" x-text="unreadCount" class="badge"></span>
```

### Dropdown

Le dropdown se rafraîchit automatiquement toutes les 60 secondes et affiche :
- ✅ Icône colorée par type
- ✅ Titre et message
- ✅ Temps relatif ("il y a 5 minutes")
- ✅ Indicateur non lu (point bleu)
- ✅ Action au clic (marquer comme lu + redirection)

### Personnaliser le Rafraîchissement

Dans `resources/views/layouts/agence.blade.php`, ligne 358 :

```javascript
// Changer l'intervalle de rafraîchissement
setInterval(() => {
    this.loadNotifications();
}, 30000); // 30 secondes au lieu de 60
```

---

## 🔐 Sécurité

Toutes les requêtes sont sécurisées :
- ✅ Middleware `auth` obligatoire
- ✅ Middleware `role:agence` pour les agences
- ✅ Middleware `approved.agency` pour les agences approuvées
- ✅ Vérification `agency_id` dans toutes les requêtes
- ✅ Token CSRF sur POST/DELETE

---

## 📈 Performance

### Optimisations Actuelles
- ✅ Index sur `(agency_id, is_read)` pour requêtes rapides
- ✅ Index sur `created_at` pour tri chronologique
- ✅ Limite de 15 notifications récentes
- ✅ Eager loading des relations

### Optimisations Futures (Optionnelles)
- 🔄 Cache Redis pour notifications non lues
- 🔄 Pagination pour historique complet
- 🔄 WebSockets pour notifications temps réel (Pusher/Laravel Echo)

---

## 🐛 Dépannage

### Aucune notification n'apparaît

1. Vérifier que la migration a été exécutée :
```bash
php artisan migrate:status
```

2. Générer des notifications de test :
```bash
php artisan notifications:generate-test
```

3. Vérifier les logs Laravel :
```bash
tail -f storage/logs/laravel.log
```

### Le badge ne se met pas à jour

1. Vérifier la console du navigateur (F12)
2. Vérifier que la route `/agence/notifications` fonctionne
3. Rafraîchir la page complète (Ctrl+F5)

### Erreur 403 Forbidden

Vérifier que l'utilisateur est :
- ✅ Connecté
- ✅ A le rôle `agence`
- ✅ Son agence est `approved`

---

## ✅ Checklist de Production

Avant de mettre en production :

- [ ] Migration exécutée sur production
- [ ] Tests avec notifications de démonstration
- [ ] Vérifier le rafraîchissement automatique
- [ ] Tester sur mobile et desktop
- [ ] Vérifier les permissions et sécurité
- [ ] Configurer le nettoyage automatique (optionnel)
- [ ] Documenter pour l'équipe
- [ ] Former les utilisateurs

---

## 📞 Support

Pour toute question ou problème, consulter :
- `SYSTEME_NOTIFICATIONS_COMPLET.md` - Documentation technique complète
- `app/Helpers/NotificationHelper.php` - Helper avec toutes les méthodes
- `app/Models/Notification.php` - Modèle avec scopes et attributs

---

**Dernière mise à jour :** 12 Octobre 2025  
**Version :** 1.0.0  
**Statut :** ✅ PRODUCTION READY

