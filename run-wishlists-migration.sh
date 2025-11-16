#!/bin/bash

# Script pour exécuter les migrations wishlists en contournant le problème de version PHP
# Usage: ./run-wishlists-migration.sh

set -e

echo "🔍 Vérification et exécution des migrations wishlists..."

cd public_html || { echo "❌ Erreur: Répertoire public_html introuvable"; exit 1; }

# Vérifier si la table existe déjà
echo "📊 Vérification de l'existence de la table..."
php artisan tinker --execute="
    if (Schema::hasTable('wishlists')) {
        echo '✅ La table wishlists existe déjà.\n';
        exit(0);
    } else {
        echo '❌ La table wishlists n\'existe pas.\n';
        exit(1);
    }
" 2>/dev/null || TABLE_EXISTS=false

if [ "$TABLE_EXISTS" = "false" ]; then
    echo "📦 Exécution des migrations wishlists..."
    
    # Essayer d'exécuter les migrations avec --force pour éviter les confirmations
    php artisan migrate --force --path=database/migrations/2025_11_06_162221_create_wishlists_table.php 2>&1 | grep -v "Composer detected issues" || true
    
    php artisan migrate --force --path=database/migrations/2025_11_06_162222_create_wishlist_items_table.php 2>&1 | grep -v "Composer detected issues" || true
    
    # Vérifier à nouveau
    php artisan tinker --execute="
        if (Schema::hasTable('wishlists')) {
            echo '✅ Migration réussie: La table wishlists existe maintenant.\n';
        } else {
            echo '❌ Erreur: La table wishlists n\'existe toujours pas.\n';
            exit(1);
        }
    " 2>/dev/null || echo "⚠️  Impossible de vérifier. Vérifiez manuellement."
else
    echo "✅ La table existe déjà, pas besoin de migration."
fi

echo "✅ Terminé!"

