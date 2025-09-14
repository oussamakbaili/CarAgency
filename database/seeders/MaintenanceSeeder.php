<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Maintenance;
use App\Models\Car;
use App\Models\Agency;

class MaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get the first agency
        $agency = Agency::first();
        if (!$agency) {
            return;
        }

        // Get cars for this agency
        $cars = $agency->cars;
        if ($cars->isEmpty()) {
            return;
        }

        $maintenanceTypes = [
            'routine' => 'Révision générale',
            'repair' => 'Réparation moteur',
            'inspection' => 'Inspection technique',
            'emergency' => 'Panne urgente',
            'other' => 'Changement de pneus'
        ];

        $garages = [
            'Auto Service Plus',
            'Garage Central',
            'Pneu Center',
            'Mercedes Service',
            'BMW Garage',
            'Auto Express'
        ];

        $statuses = ['scheduled', 'in_progress', 'completed'];

        foreach ($cars->take(5) as $car) {
            // Create 2-3 maintenance records per car
            $maintenanceCount = rand(2, 3);
            
            for ($i = 0; $i < $maintenanceCount; $i++) {
                $type = array_rand($maintenanceTypes);
                $status = $statuses[array_rand($statuses)];
                
                $scheduledDate = now()->addDays(rand(-30, 30));
                $startDate = null;
                $endDate = null;
                
                if ($status === 'in_progress') {
                    $startDate = $scheduledDate->copy()->addDays(rand(0, 5));
                } elseif ($status === 'completed') {
                    $startDate = $scheduledDate->copy()->addDays(rand(0, 5));
                    $endDate = $startDate->copy()->addDays(rand(1, 7));
                }

                Maintenance::create([
                    'car_id' => $car->id,
                    'agency_id' => $agency->id,
                    'title' => $maintenanceTypes[$type] . ' - ' . rand(10000, 50000) . ' km',
                    'description' => 'Maintenance ' . strtolower($maintenanceTypes[$type]) . ' pour le véhicule ' . $car->brand . ' ' . $car->model,
                    'type' => $type,
                    'status' => $status,
                    'scheduled_date' => $scheduledDate,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'cost' => rand(200, 2000) + (rand(0, 99) / 100),
                    'garage_name' => $garages[array_rand($garages)],
                    'garage_contact' => '+212 6 ' . rand(10000000, 99999999),
                    'notes' => $status === 'completed' ? 'Maintenance terminée avec succès. Véhicule en bon état.' : null,
                    'mileage_at_service' => rand(10000, 100000),
                ]);
            }
        }
    }
}