#!/bin/bash

# Script pour vérifier et exécuter les migrations wishlists
# Usage: ./check-wishlists-migration.sh

echo "🔍 Vérification des migrations wishlists..."

# Vérifier si la table existe
php artisan tinker --execute="echo Schema::hasTable('wishlists') ? 'Table exists' : 'Table does not exist';"

# Exécuter les migrations si nécessaire
echo "📦 Exécution des migrations..."
php artisan migrate --path=database/migrations/2025_11_06_162221_create_wishlists_table.php
php artisan migrate --path=database/migrations/2025_11_06_162222_create_wishlist_items_table.php

echo "✅ Vérification terminée!"

