<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, ensure all categories exist
        $categoryMappings = [
            'Luxury' => 'Luxury',
            'Economy' => 'Economy', 
            'SUV' => 'SUV',
            'Sedan' => 'Sedan',
            'Hatchback' => 'Hatchback',
            'Convertible' => 'Convertible'
        ];

        foreach ($categoryMappings as $oldName => $newName) {
            DB::table('categories')->updateOrInsert(
                ['name' => $newName],
                [
                    'name' => $newName,
                    'description' => "Category for $newName vehicles",
                    'icon' => 'car',
                    'color' => '#3B82F6',
                    'is_active' => true,
                    'sort_order' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Update cars to use category_id instead of category
        $cars = DB::table('cars')->whereNotNull('category')->whereNull('category_id')->get();
        
        foreach ($cars as $car) {
            $category = DB::table('categories')->where('name', $car->category)->first();
            if ($category) {
                DB::table('cars')
                    ->where('id', $car->id)
                    ->update(['category_id' => $category->id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update cars back to use category field
        $cars = DB::table('cars')->whereNotNull('category_id')->get();
        
        foreach ($cars as $car) {
            $category = DB::table('categories')->where('id', $car->category_id)->first();
            if ($category) {
                DB::table('cars')
                    ->where('id', $car->id)
                    ->update(['category' => $category->name]);
            }
        }
    }
};