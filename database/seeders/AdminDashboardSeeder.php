<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Rental;
use App\Models\Agency;
use App\Models\Car;
use App\Models\User;
use App\Models\Client;
use Carbon\Carbon;

class AdminDashboardSeeder extends Seeder
{
    public function run()
    {
        // Create test data for admin dashboard charts
        
        // Get or create agencies
        $agencies = Agency::all();
        if ($agencies->isEmpty()) {
            $this->command->info('No agencies found. Creating test agencies...');
            
            // Create test user for agency
            $user = User::create([
                'name' => 'Test Agency',
                'email' => 'agency@test.com',
                'password' => bcrypt('password'),
                'role' => 'agence',
                'email_verified_at' => now(),
            ]);
            
            $agency = Agency::create([
                'user_id' => $user->id,
                'agency_name' => 'Test Car Rental',
                'responsable_name' => 'John Doe',
                'email' => 'agency@test.com',
                'phone' => '0123456789',
                'address' => '123 Test Street',
                'city' => 'Test City',
                'status' => 'approved',
            ]);
            
            $agencies = collect([$agency]);
        }
        
        // Create test cars for each agency
        foreach ($agencies as $agency) {
            if (Car::where('agency_id', $agency->id)->count() < 3) {
                for ($i = 1; $i <= 3; $i++) {
                    Car::create([
                        'agency_id' => $agency->id,
                        'brand' => 'Toyota',
                        'model' => 'Corolla ' . $i,
                        'year' => 2020 + $i,
                        'registration_number' => 'TEST-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                        'price_per_day' => 300 + ($i * 50),
                    ]);
                }
            }
        }
        
        // Get or create test client
        $client = User::firstOrCreate(
            ['email' => 'client@test.com'],
            [
                'name' => 'Test Client',
                'password' => bcrypt('password'),
                'role' => 'client',
                'email_verified_at' => now(),
            ]
        );
        
        // Create Client record if doesn't exist
        Client::firstOrCreate(
            ['user_id' => $client->id],
            [
                'phone' => '0987654321',
                'address' => '456 Client Street',
                'city' => 'Client City',
            ]
        );
        
        // Create rentals and transactions for the last 12 months
        $this->createHistoricalData($agencies, $client);
        
        $this->command->info('Admin dashboard test data created successfully!');
    }
    
    private function createHistoricalData($agencies, $client)
    {
        $cars = Car::all();
        if ($cars->isEmpty()) {
            $this->command->error('No cars found. Please create cars first.');
            return;
        }
        
        // Create rentals and transactions for the last 12 months
        for ($monthOffset = 11; $monthOffset >= 0; $monthOffset--) {
            $monthStart = Carbon::now()->subMonths($monthOffset)->startOfMonth();
            $monthEnd = Carbon::now()->subMonths($monthOffset)->endOfMonth();
            
            // Create 5-15 rentals per month
            $rentalsCount = rand(5, 15);
            
            for ($i = 0; $i < $rentalsCount; $i++) {
                $car = $cars->random();
                $agency = $car->agency;
                
                $startDate = $monthStart->copy()->addDays(rand(0, 20));
                $endDate = $startDate->copy()->addDays(rand(1, 7));
                
                // Ensure dates are within the month
                if ($endDate->gt($monthEnd)) {
                    $endDate = $monthEnd->copy();
                }
                
                $totalPrice = $car->price_per_day * $startDate->diffInDays($endDate);
                $status = ['completed', 'active', 'pending'][rand(0, 2)];
                
                $rental = Rental::create([
                    'user_id' => $client->id,
                    'car_id' => $car->id,
                    'agency_id' => $agency->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'total_price' => $totalPrice,
                    'status' => $status,
                    'created_at' => $startDate->copy()->subDays(rand(1, 5)),
                ]);
                
                // Create transaction if rental is completed or active
                if (in_array($status, ['completed', 'active'])) {
                    Transaction::create([
                        'agency_id' => $agency->id,
                        'rental_id' => $rental->id,
                        'type' => Transaction::TYPE_RENTAL_PAYMENT,
                        'amount' => $totalPrice,
                        'balance_before' => $agency->balance ?? 0,
                        'balance_after' => ($agency->balance ?? 0) + $totalPrice,
                        'description' => "Paiement pour location #{$rental->id}",
                        'status' => Transaction::STATUS_COMPLETED,
                        'processed_at' => $rental->created_at,
                    ]);
                }
            }
        }
        
        // Create some recent daily bookings for the booking chart (last 30 days)
        for ($dayOffset = 29; $dayOffset >= 0; $dayOffset--) {
            $date = Carbon::now()->subDays($dayOffset);
            
            // Create 0-5 bookings per day
            $dailyBookings = rand(0, 5);
            
            for ($i = 0; $i < $dailyBookings; $i++) {
                $car = $cars->random();
                $agency = $car->agency;
                
                $startDate = $date->copy()->addDays(rand(1, 7));
                $endDate = $startDate->copy()->addDays(rand(1, 5));
                
                $totalPrice = $car->price_per_day * $startDate->diffInDays($endDate);
                
                Rental::create([
                    'user_id' => $client->id,
                    'car_id' => $car->id,
                    'agency_id' => $agency->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'total_price' => $totalPrice,
                    'status' => 'pending',
                    'created_at' => $date,
                ]);
            }
        }
    }
}
