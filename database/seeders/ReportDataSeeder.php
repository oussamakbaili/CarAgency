<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rental;
use App\Models\Car;
use App\Models\Agency;
use App\Models\User;
use Carbon\Carbon;

class ReportDataSeeder extends Seeder
{
    public function run()
    {
        // Get the first agency
        $agency = Agency::first();
        if (!$agency) {
            $this->command->error('No agency found. Please run AgencySeeder first.');
            return;
        }

        // Get cars for this agency
        $cars = Car::where('agency_id', $agency->id)->get();
        if ($cars->isEmpty()) {
            $this->command->error('No cars found for agency. Please run AgencyDashboardSeeder first.');
            return;
        }

        // Get or create a test user
        $user = User::firstOrCreate(
            ['email' => 'test@client.com'],
            [
                'name' => 'Test Client',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        // Create realistic rental data for the last 12 months
        $rentalStatuses = ['active', 'completed', 'pending', 'rejected'];
        $statusWeights = [30, 40, 20, 10]; // 40% completed, 30% active, 20% pending, 10% rejected
        
        for ($i = 0; $i < 100; $i++) {
            $car = $cars->random();
            $status = $this->weightedRandom($rentalStatuses, $statusWeights);
            
            // Create dates in the last 12 months
            $startDate = Carbon::now()->subMonths(rand(0, 11))->subDays(rand(0, 30));
            $endDate = $startDate->copy()->addDays(rand(1, 14));
            
            // Calculate realistic pricing
            $days = $startDate->diffInDays($endDate);
            $basePrice = $car->price_per_day;
            
            // Add some variation to pricing
            $priceMultiplier = rand(80, 120) / 100; // 80% to 120% of base price
            $totalPrice = $days * $basePrice * $priceMultiplier;
            
            // Create rental
            Rental::create([
                'user_id' => $user->id,
                'car_id' => $car->id,
                'agency_id' => $agency->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_price' => round($totalPrice, 2),
                'status' => $status,
                'created_at' => $startDate->copy()->subDays(rand(1, 7)), // Rental created a few days before start
                'updated_at' => $startDate->copy()->subDays(rand(0, 3)),
            ]);
        }

        $this->command->info('Report data seeded successfully!');
        $this->command->info('Created 100 rentals with realistic revenue data.');
        
        // Show some statistics
        $totalRevenue = Rental::where('agency_id', $agency->id)
            ->whereIn('status', ['active', 'completed'])
            ->sum('total_price');
        $totalRentals = Rental::where('agency_id', $agency->id)->count();
        $completedRentals = Rental::where('agency_id', $agency->id)->where('status', 'completed')->count();
        $activeRentals = Rental::where('agency_id', $agency->id)->where('status', 'active')->count();
        
        $this->command->info("Total Revenue: " . number_format($totalRevenue, 2) . " MAD");
        $this->command->info("Total Rentals: " . $totalRentals);
        $this->command->info("Completed Rentals: " . $completedRentals);
        $this->command->info("Active Rentals: " . $activeRentals);
    }
    
    private function weightedRandom($items, $weights)
    {
        $totalWeight = array_sum($weights);
        $random = mt_rand(1, $totalWeight);
        
        $currentWeight = 0;
        foreach ($items as $index => $item) {
            $currentWeight += $weights[$index];
            if ($random <= $currentWeight) {
                return $item;
            }
        }
        
        return $items[0]; // Fallback
    }
}