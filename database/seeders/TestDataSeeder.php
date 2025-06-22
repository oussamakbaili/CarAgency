<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Agency;
use App\Models\Car;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test agency users
        $agency1User = User::create([
            'name' => 'Rent-A-Car Express',
            'email' => 'agency1@test.com',
            'password' => Hash::make('password'),
            'role' => 'agence',
            'phone' => '0123456789',
            'email_verified_at' => now(),
        ]);

        $agency2User = User::create([
            'name' => 'Premium Auto Location',
            'email' => 'agency2@test.com',
            'password' => Hash::make('password'),
            'role' => 'agence',
            'phone' => '0987654321',
            'email_verified_at' => now(),
        ]);

        // Create agencies
        $agency1 = Agency::create([
            'user_id' => $agency1User->id,
            'agency_name' => 'Rent-A-Car Express',
            'responsable_name' => 'John Doe',
            'email' => 'agency1@test.com',
            'phone' => '0123456789',
            'address' => '123 Main Street',
            'city' => 'Paris',
            'postal_code' => '75001',
            'tax_number' => 'TAX123456',
            'commercial_register_number' => 'REG789',
            'responsable_phone' => '0123456789',
            'responsable_position' => 'Manager',
            'responsable_identity_number' => 'ID123456',
            'years_in_business' => '5',
            'business_description' => 'Car rental services',
            'estimated_fleet_size' => '20',
            'status' => 'approved',
        ]);

        $agency2 = Agency::create([
            'user_id' => $agency2User->id,
            'agency_name' => 'Premium Auto Location',
            'responsable_name' => 'Jane Smith',
            'email' => 'agency2@test.com',
            'phone' => '0987654321',
            'address' => '456 Avenue des Champs',
            'city' => 'Lyon',
            'postal_code' => '69001',
            'tax_number' => 'TAX789123',
            'commercial_register_number' => 'REG456',
            'responsable_phone' => '0987654321',
            'responsable_position' => 'Director',
            'responsable_identity_number' => 'ID789123',
            'years_in_business' => '10',
            'business_description' => 'Premium car rental',
            'estimated_fleet_size' => '50',
            'status' => 'approved',
        ]);

        // Create cars for agency 1
        $cars1 = [
            [
                'brand' => 'Peugeot',
                'model' => '208',
                'year' => 2021,
                'color' => 'Blanc',
                'fuel_type' => 'Essence',
                'price_per_day' => 35.00,
                'registration_number' => 'AA-123-BB',
                'description' => 'Citadine économique et pratique pour vos déplacements urbains.',
            ],
            [
                'brand' => 'Renault',
                'model' => 'Clio',
                'year' => 2020,
                'color' => 'Rouge',
                'fuel_type' => 'Diesel',
                'price_per_day' => 32.00,
                'registration_number' => 'BB-456-CC',
                'description' => 'Véhicule compact idéal pour la ville et les petits trajets.',
            ],
            [
                'brand' => 'Volkswagen',
                'model' => 'Golf',
                'year' => 2022,
                'color' => 'Gris',
                'fuel_type' => 'Essence',
                'price_per_day' => 45.00,
                'registration_number' => 'CC-789-DD',
                'description' => 'Berline familiale confortable avec un excellent rapport qualité-prix.',
            ],
        ];

        foreach ($cars1 as $carData) {
            Car::create(array_merge($carData, [
                'agency_id' => $agency1->id,
                'status' => 'available',
            ]));
        }

        // Create cars for agency 2
        $cars2 = [
            [
                'brand' => 'BMW',
                'model' => 'Série 3',
                'year' => 2023,
                'color' => 'Noir',
                'fuel_type' => 'Diesel',
                'price_per_day' => 75.00,
                'registration_number' => 'DD-111-EE',
                'description' => 'Berline de luxe pour un confort de conduite exceptionnel.',
            ],
            [
                'brand' => 'Mercedes',
                'model' => 'Classe A',
                'year' => 2022,
                'color' => 'Bleu',
                'fuel_type' => 'Essence',
                'price_per_day' => 68.00,
                'registration_number' => 'EE-222-FF',
                'description' => 'Véhicule premium avec équipements haut de gamme.',
            ],
            [
                'brand' => 'Audi',
                'model' => 'A4',
                'year' => 2023,
                'color' => 'Argent',
                'fuel_type' => 'Hybride',
                'price_per_day' => 82.00,
                'registration_number' => 'FF-333-GG',
                'description' => 'Berline hybride alliant performance et respect de l\'environnement.',
            ],
            [
                'brand' => 'Tesla',
                'model' => 'Model 3',
                'year' => 2023,
                'color' => 'Blanc',
                'fuel_type' => 'Électrique',
                'price_per_day' => 95.00,
                'registration_number' => 'GG-444-HH',
                'description' => 'Véhicule électrique innovant avec une technologie de pointe.',
            ],
        ];

        foreach ($cars2 as $carData) {
            Car::create(array_merge($carData, [
                'agency_id' => $agency2->id,
                'status' => 'available',
            ]));
        }

        // Create a test client user
        $clientUser = User::create([
            'name' => 'Test Client',
            'email' => 'client@test.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'phone' => '0555123456',
            'email_verified_at' => now(),
        ]);

        // Create client record
        Client::create([
            'user_id' => $clientUser->id,
            'name' => 'Test Client',
            'cin' => 'CIN123456',
            'birthday' => '1990-01-01',
            'phone' => '0555123456',
            'address' => '789 Client Street, Paris',
        ]);

        echo "Test data created successfully!\n";
        echo "Agency 1: agency1@test.com / password\n";
        echo "Agency 2: agency2@test.com / password\n";
        echo "Client: client@test.com / password\n";
    }
}
