# Suppression de Stripe et CMI - Ne garder que PayPal

## Fichiers à modifier

### 1. Vues (resources/views/client/booking/)
- `main.blade.php` - Supprimer tout le code Stripe et CMI, ne garder que PayPal
- `step4.blade.php` - Supprimer les options Stripe et CMI
- `confirm.blade.php` - Supprimer les références Stripe et CMI

### 2. Contrôleurs
- `app/Http/Controllers/Client/BookingController.php` - Supprimer les méthodes initStripePayment et initCMIPayment
- Modifier processPayment pour ne garder que PayPal

### 3. Services
- `app/Services/PaymentService.php` - Supprimer tout le code Stripe et CMI, ne garder que PayPal

### 4. Routes
- `routes/web.php` - Supprimer les routes Stripe et CMI

### 5. Validations
- Modifier les validations pour ne garder que PayPal

## Commandes à exécuter après modifications

```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

