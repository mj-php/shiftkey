<?php

namespace Tests\Feature;

use App\Car;
use App\User as User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class TripTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class,1)->create()->first();
    }

    /**
     * A test for wrong data provided by authenticated user.
     *
     * @return void
     */
    public function testTripAuthenticatedCreationWrongData()
    {
        $car = $this->createDummyCar();

        $payload = [
            'car_id' => '',
            'date' => Carbon::now()->subDays(1)->format('m/d/Y'),
            'miles' => 25.15,
        ];

        $response = $this->actingAs($this->user,'api')->postJson('/api/add-trip', $payload);

        //dd($response->getContent(),$response->getStatusCode());

        $response
            ->assertStatus(422)
            ->assertJson(['message' => 'The given data was invalid.']);
    }

    /**
     * A test for correct data provided by authenticated user.
     *
     * @return void
     */
    public function testTripAuthenticatedCreationCorrectData()
    {
        $car = $this->createDummyCar();

        $payload = [
            'car_id' => $car->id,
            'date' => Carbon::now()->subDays(1)->format('m/d/Y'),
            'miles' => 25.15,
        ];

        $response = $this->actingAs($this->user,'api')->postJson('/api/add-trip', $payload);

        //dd($response->getContent(),$response->getStatusCode());

        $response
            ->assertStatus(201)
            ->assertJson(['car_id' => $car->id]);
    }

    /**
     * A test for correct data provided by not authenticated user.
     *
     * @return void
     */
    public function testTripNotAuthenticatedCreationCorrectData()
    {
        $car = $this->createDummyCar();

        $payload = [
            'car_id' => $car->id,
            'date' => Carbon::now()->subDays(1)->format('m/d/Y'),
            'miles' => 25.15,
        ];

        $response = $this->postJson('/api/add-trip', $payload);

        //dd($response->getContent(),$response->getStatusCode());

        $response
            ->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    /**
     * Function for creating dummy Car object.
     *
     * @return Car
     */
    private function createDummyCar(): Car
    {
        $car = new Car();

        $car->make = 'Test Make';
        $car->user_id = $this->user->id;
        $car->model = 'Test Model';
        $car->year = '1999';
        $car->save();

        return $car;
    }
}
