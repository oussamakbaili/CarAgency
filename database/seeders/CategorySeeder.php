<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Economy',
                'description' => 'Véhicules économiques et économes en carburant',
                'icon' => 'economy',
                'color' => '#10B981',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Hatchback',
                'description' => 'Véhicules compacts avec hayon arrière',
                'icon' => 'hatchback',
                'color' => '#3B82F6',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Luxury',
                'description' => 'Véhicules de luxe haut de gamme',
                'icon' => 'luxury',
                'color' => '#8B5CF6',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Sedan',
                'description' => 'Berlines classiques et élégantes',
                'icon' => 'sedan',
                'color' => '#F59E0B',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'SUV',
                'description' => 'Véhicules utilitaires sportifs',
                'icon' => 'suv',
                'color' => '#EF4444',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Convertible',
                'description' => 'Véhicules décapotables',
                'icon' => 'convertible',
                'color' => '#EC4899',
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['name' => $categoryData['name']],
                $categoryData
            );
        }
    }
}