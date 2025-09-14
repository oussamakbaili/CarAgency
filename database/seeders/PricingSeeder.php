<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agency;
use App\Models\Car;
use App\Models\User;
use App\Models\PricingRule;
use App\Models\SeasonalRule;
use App\Models\SpecialOffer;
use App\Models\DynamicPricingConfig;

class PricingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first agency
        $agency = Agency::first();
        if (!$agency) {
            $this->command->info('No agency found. Please run AgencySeeder first.');
            return;
        }

        // Get cars for this agency or create some test cars
        $cars = Car::where('agency_id', $agency->id)->get();
        
        if ($cars->isEmpty()) {
            // Create some test cars for the agency
            $cars = collect();
            for ($i = 1; $i <= 3; $i++) {
                $car = Car::create([
                    'agency_id' => $agency->id,
                    'brand' => 'Toyota',
                    'model' => 'Corolla ' . $i,
                    'year' => 2020 + $i,
                    'registration_number' => 'ABC-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'price_per_day' => 300 + ($i * 50),
                ]);
                $cars->push($car);
            }
            $this->command->info('Created ' . $cars->count() . ' test cars for agency ' . $agency->id);
        } else {
            $this->command->info('Found ' . $cars->count() . ' cars for agency ' . $agency->id);
        }

        // Get the first user (admin)
        $user = User::first();

        // Create pricing rules (price history)
        foreach ($cars->take(3) as $car) {
            // Create some price changes
            PricingRule::create([
                'agency_id' => $agency->id,
                'car_id' => $car->id,
                'old_price' => $car->price_per_day - 50,
                'new_price' => $car->price_per_day,
                'reason' => 'Ajustement saisonnier',
                'seasonal_multiplier' => 1.2,
                'user_id' => $user->id,
                'created_at' => now()->subDays(30),
            ]);

            PricingRule::create([
                'agency_id' => $agency->id,
                'car_id' => $car->id,
                'old_price' => $car->price_per_day,
                'new_price' => $car->price_per_day + 30,
                'reason' => 'Augmentation de la demande',
                'seasonal_multiplier' => 1.0,
                'user_id' => $user->id,
                'created_at' => now()->subDays(15),
            ]);
        }

        // Create seasonal rules
        SeasonalRule::create([
            'agency_id' => $agency->id,
            'name' => 'Règle Été 2024',
            'description' => 'Tarification estivale avec majoration de 25%',
            'start_date' => now()->startOfMonth(),
            'end_date' => now()->addMonths(3),
            'price_multiplier' => 1.25,
            'vehicle_ids' => $cars->take(2)->pluck('id')->toArray(),
            'is_active' => true,
        ]);

        SeasonalRule::create([
            'agency_id' => $agency->id,
            'name' => 'Règle Hiver 2024',
            'description' => 'Tarification hivernale avec réduction de 10%',
            'start_date' => now()->addMonths(6),
            'end_date' => now()->addMonths(9),
            'price_multiplier' => 0.9,
            'vehicle_ids' => $cars->take(3)->pluck('id')->toArray(),
            'is_active' => false,
        ]);

        // Create special offers
        SpecialOffer::create([
            'agency_id' => $agency->id,
            'name' => 'Offre Été 2024',
            'code' => 'ETE2024',
            'type' => 'percentage',
            'discount_value' => 15,
            'start_date' => now()->startOfMonth(),
            'end_date' => now()->addMonths(2),
            'max_usage' => 100,
            'usage_count' => 0,
            'vehicle_ids' => $cars->take(2)->pluck('id')->toArray(),
            'is_active' => true,
        ]);

        SpecialOffer::create([
            'agency_id' => $agency->id,
            'name' => 'Weekend Special',
            'code' => 'WEEKEND',
            'type' => 'fixed',
            'discount_value' => 50,
            'start_date' => now()->startOfMonth(),
            'end_date' => now()->addMonths(6),
            'max_usage' => 50,
            'usage_count' => 0,
            'vehicle_ids' => $cars->take(3)->pluck('id')->toArray(),
            'is_active' => true,
        ]);

        // Create dynamic pricing configuration
        DynamicPricingConfig::create([
            'agency_id' => $agency->id,
            'enabled' => true,
            'peak_hour_multiplier' => 1.2,
            'weekend_multiplier' => 1.1,
            'last_minute_multiplier' => 0.9,
            'demand_threshold' => 80,
            'peak_hours' => [9, 10, 11, 14, 15, 16, 17, 18],
        ]);

        $this->command->info('Pricing data seeded successfully!');
    }
}