<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement PayPal Réussi - {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
            <!-- Success Icon -->
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <!-- Success Message -->
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Paiement Réussi !</h1>
            <p class="text-gray-600 mb-6">
                Votre paiement PayPal a été traité avec succès. Votre réservation est maintenant confirmée.
            </p>

            <!-- Rental Details -->
            @if($rental)
            <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
                <h3 class="font-semibold text-gray-900 mb-2">Détails de la réservation</h3>
                <div class="space-y-1 text-sm text-gray-600">
                    <p><strong>Véhicule:</strong> {{ $rental->car->brand }} {{ $rental->car->model }}</p>
                    <p><strong>Dates:</strong> {{ $rental->start_date->format('d/m/Y') }} - {{ $rental->end_date->format('d/m/Y') }}</p>
                    <p><strong>Montant:</strong> {{ number_format($rental->total_price, 2, ',', ' ') }} MAD</p>
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="space-y-3">
                <button onclick="closeWindow()" class="w-full bg-orange-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-orange-700 transition-colors">
                    Fermer cette fenêtre
                </button>
                <a href="{{ route('rentals.show', $rental->id ?? '#') }}" class="block w-full bg-gray-200 text-gray-800 py-3 px-4 rounded-lg font-semibold hover:bg-gray-300 transition-colors text-center">
                    Voir ma réservation
                </a>
            </div>
        </div>
    </div>

    <script>
        function closeWindow() {
            // Essayer de fermer la fenêtre si elle a été ouverte dans un nouvel onglet
            if (window.opener) {
                // Si la fenêtre a été ouverte par window.open(), on peut la fermer
                window.close();
            } else {
                // Sinon, rediriger vers la page de réservation
                window.location.href = '{{ route('rentals.show', $rental->id ?? '#') }}';
            }
        }

        // Auto-fermer après 5 secondes si la fenêtre a été ouverte par window.open()
        if (window.opener) {
            setTimeout(function() {
                closeWindow();
            }, 5000);
        }

        // Notifier la fenêtre parente que le paiement est réussi
        if (window.opener) {
            window.opener.postMessage({
                type: 'paypal_payment_success',
                rental_id: {{ $rental->id ?? 'null' }}
            }, '*');
        }
    </script>
</body>
</html>

