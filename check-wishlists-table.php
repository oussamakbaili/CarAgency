<?php
/**
 * Script pour vérifier si la table wishlists existe
 * Usage: php check-wishlists-table.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

try {
    echo "🔍 Vérification de la table wishlists...\n";
    
    if (Schema::hasTable('wishlists')) {
        echo "✅ La table wishlists existe.\n";
        
        // Vérifier la structure
        $columns = Schema::getColumnListing('wishlists');
        echo "📋 Colonnes: " . implode(', ', $columns) . "\n";
        
        // Compter les wishlists
        $count = DB::table('wishlists')->count();
        echo "📊 Nombre de wishlists: $count\n";
    } else {
        echo "❌ La table wishlists n'existe pas.\n";
        echo "💡 Vous devez exécuter les migrations.\n";
        echo "   Commande: php artisan migrate --force\n";
    }
    
    // Vérifier aussi wishlist_items
    if (Schema::hasTable('wishlist_items')) {
        echo "✅ La table wishlist_items existe.\n";
    } else {
        echo "❌ La table wishlist_items n'existe pas.\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}

