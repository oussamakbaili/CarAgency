<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Agency;
use App\Models\Car;
use App\Models\Client;
use App\Models\Rental;
use App\Models\Transaction;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AgencyDashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test agency user
        $agencyUser = User::firstOrCreate(
            ['email' => 'agence@test.com'],
            [
                'name' => 'Agence Test',
                'password' => Hash::make('password'),
                'role' => 'agence',
            ]
        );

        // Create the agency
        $agency = Agency::firstOrCreate(
            ['user_id' => $agencyUser->id],
            [
                'agency_name' => 'Agence de Location de Voitures Premium',
                'responsable_name' => 'Ahmed Benali',
                'email' => 'agence@test.com',
                'phone' => '+212 6 12 34 56 78',
                'address' => '123 Avenue Mohammed V',
                'city' => 'Casablanca',
                'postal_code' => '20000',
                'tax_number' => 'TAX123456789',
                'commercial_register_number' => 'RC123456789',
                'responsable_phone' => '+212 6 12 34 56 78',
                'responsable_position' => 'Directeur',
                'responsable_identity_number' => 'C123456789',
                'years_in_business' => 5,
                'business_description' => 'Agence de location de voitures de luxe et économiques',
                'estimated_fleet_size' => 20,
                'status' => 'approved',
                'balance' => 50000.00,
                'total_earnings' => 150000.00,
                'pending_earnings' => 15000.00,
                'commission_rate' => 10.00,
            ]
        );

        // Create some clients
        $clients = [];
        for ($i = 1; $i <= 15; $i++) {
            $clientUser = User::firstOrCreate(
                ['email' => "client$i@test.com"],
                [
                    'name' => "Client $i",
                    'password' => Hash::make('password'),
                    'role' => 'client',
                ]
            );

            $clients[] = Client::firstOrCreate(
                ['user_id' => $clientUser->id],
                [
                    'cin' => 'C' . str_pad($i, 8, '0', STR_PAD_LEFT),
                    'birthday' => Carbon::now()->subYears(25 + $i),
                    'phone' => '+212 6 ' . str_pad($i, 8, '0', STR_PAD_LEFT),
                    'address' => "Adresse Client $i",
                    'city' => 'Casablanca',
                    'postal_code' => '20000',
                    'date_of_birth' => Carbon::now()->subYears(25 + $i),
                    'driving_license_number' => 'DL' . str_pad($i, 8, '0', STR_PAD_LEFT),
                    'driving_license_expiry' => Carbon::now()->addYears(5),
                ]
            );
        }

        // Create cars
        $carBrands = ['BMW', 'Mercedes', 'Audi', 'Toyota', 'Honda', 'Nissan', 'Peugeot', 'Renault'];
        $carModels = ['X5', 'C-Class', 'A4', 'Camry', 'Civic', 'Altima', '308', 'Clio'];
        // Get categories from database
        $categories = \App\Models\Category::all();
        if ($categories->isEmpty()) {
            // If no categories exist, create some
            $categoryNames = ['Luxury', 'Economy', 'SUV', 'Sedan', 'Hatchback'];
            foreach ($categoryNames as $name) {
                \App\Models\Category::create([
                    'name' => $name,
                    'description' => "Category for $name vehicles",
                    'is_active' => true,
                    'sort_order' => 0,
                ]);
            }
            $categories = \App\Models\Category::all();
        }
        $colors = ['White', 'Black', 'Silver', 'Blue', 'Red'];
        $fuelTypes = ['Gasoline', 'Diesel', 'Hybrid', 'Electric'];
        $transmissions = ['Automatic', 'Manual'];
        $features = [
            ['GPS', 'Bluetooth', 'Air Conditioning'],
            ['GPS', 'Bluetooth', 'Air Conditioning', 'Leather Seats'],
            ['GPS', 'Bluetooth', 'Air Conditioning', 'Sunroof'],
            ['GPS', 'Bluetooth', 'Air Conditioning', 'Backup Camera'],
        ];

        $cars = [];
        for ($i = 1; $i <= 20; $i
        
        ++) {
            $brand = $carBrands[array_rand($carBrands)];
            $model = $carModels[array_rand($carModels)];
            $category = $categories->random();
            $color = $colors[array_rand($colors)];
            $fuelType = $fuelTypes[array_rand($fuelTypes)];
            $transmission = $transmissions[array_rand($transmissions)];
            $carFeatures = $features[array_rand($features)];

            $cars[] = Car::firstOrCreate(
                ['registration_number' => 'A' . str_pad($i, 6, '0', STR_PAD_LEFT)],
                [
                    'agency_id' => $agency->id,
                    'brand' => $brand,
                    'model' => $model,
                    'year' => 2020 + ($i % 4),
                    'price_per_day' => 200 + ($i * 50),
                    'description' => "Voiture $brand $model en excellent état",
                    'category_id' => $category->id,
                    'color' => $color,
                    'fuel_type' => $fuelType,
                    'status' => $i <= 15 ? 'available' : 'maintenance',
                    'stock_quantity' => 1,
                    'available_stock' => $i <= 15 ? 1 : 0,
                    'track_stock' => true,
                    'maintenance_due' => $i <= 15 ? null : Carbon::now()->addDays(rand(1, 30)),
                    'last_maintenance' => $i <= 15 ? Carbon::now()->subDays(rand(30, 90)) : Carbon::now()->subDays(rand(1, 30)),
                    'mileage' => rand(10000, 100000),
                    'transmission' => $transmission,
                    'seats' => rand(4, 7),
                    'engine_size' => rand(1, 3) . '.0L',
                    'features' => $carFeatures,
                ]
            );
        }

        // Create rentals
        $rentalStatuses = ['pending', 'active', 'completed', 'rejected'];
        $rentals = [];
        
        for ($i = 1; $i <= 50; $i++) {
            $client = $clients[array_rand($clients)];
            $car = $cars[array_rand($cars)];
            $status = $rentalStatuses[array_rand($rentalStatuses)];
            
            $startDate = Carbon::now()->subDays(rand(1, 60));
            $endDate = $startDate->copy()->addDays(rand(1, 14));
            $totalPrice = $car->price_per_day * $endDate->diffInDays($startDate);

            $rentals[] = Rental::create([
                'user_id' => $client->user_id,
                'car_id' => $car->id,
                'agency_id' => $agency->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_price' => $totalPrice,
                'status' => $status,
            ]);
        }

        // Create transactions
        foreach ($rentals as $rental) {
            if (in_array($rental->status, ['active', 'completed'])) {
                // Rental payment
                Transaction::create([
                    'agency_id' => $agency->id,
                    'rental_id' => $rental->id,
                    'type' => 'rental_payment',
                    'amount' => $rental->total_price,
                    'balance_before' => $agency->balance,
                    'balance_after' => $agency->balance + $rental->total_price,
                    'description' => "Paiement pour location #{$rental->id}",
                    'status' => 'completed',
                    'processed_at' => $rental->created_at,
                ]);

                // Commission
                $commission = $rental->total_price * ($agency->commission_rate / 100);
                Transaction::create([
                    'agency_id' => $agency->id,
                    'rental_id' => $rental->id,
                    'type' => 'commission',
                    'amount' => -$commission,
                    'balance_before' => $agency->balance + $rental->total_price,
                    'balance_after' => $agency->balance + $rental->total_price - $commission,
                    'description' => "Commission pour location #{$rental->id}",
                    'status' => 'completed',
                    'processed_at' => $rental->created_at,
                ]);
            }
        }

        // Create withdrawal transactions (payouts)
        $withdrawalMethods = ['bank_transfer', 'check', 'cash'];
        $withdrawalStatuses = ['completed', 'pending', 'failed'];
        
        for ($i = 1; $i <= 15; $i++) {
            $amount = rand(500, 5000);
            $method = $withdrawalMethods[array_rand($withdrawalMethods)];
            $status = $withdrawalStatuses[array_rand($withdrawalStatuses)];
            $createdAt = now()->subDays(rand(1, 90));
            
            Transaction::create([
                'agency_id' => $agency->id,
                'type' => 'withdrawal',
                'amount' => $amount,
                'balance_before' => $agency->balance + $amount,
                'balance_after' => $agency->balance,
                'description' => "Retrait - $method",
                'status' => $status,
                'metadata' => [
                    'payment_method' => $method,
                    'notes' => "Demande de retrait #$i",
                    'requested_at' => $createdAt->toISOString()
                ],
                'processed_at' => $status === 'completed' ? $createdAt->addHours(rand(1, 48)) : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        // Create activities
        $activityTypes = ['booking_created', 'booking_approved', 'booking_rejected', 'car_added', 'car_updated', 'payment_received'];
        
        for ($i = 1; $i <= 30; $i++) {
            Activity::create([
                'agency_id' => $agency->id,
                'type' => $activityTypes[array_rand($activityTypes)],
                'description' => "Activité test $i",
                'data' => json_encode(['test' => true]),
            ]);
        }

        $this->command->info('Agency dashboard data seeded successfully!');
    }
}
