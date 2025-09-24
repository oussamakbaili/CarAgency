<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Car;
use App\Models\Agency;

class CarShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_view_available_car()
    {
        // Create a client user
        $user = User::factory()->create(['role' => 'client']);
        $client = Client::factory()->create(['user_id' => $user->id]);
        
        // Create an approved agency
        $agencyUser = User::factory()->create(['role' => 'agence']);
        $agency = Agency::factory()->create([
            'user_id' => $agencyUser->id,
            'status' => 'approved'
        ]);
        
        // Create an available car
        $car = Car::factory()->create([
            'agency_id' => $agency->id,
            'status' => 'available'
        ]);

        // Test the route
        $response = $this->actingAs($user)->get(route('client.cars.show', $car));
        
        $response->assertStatus(200);
        $response->assertViewIs('client.cars.show');
        $response->assertViewHas('car');
    }
}