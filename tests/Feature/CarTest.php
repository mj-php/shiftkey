<?php

namespace Tests\Feature;

use App\Car;
use App\User as User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A test for wrong data provided by authenticated user.
     *
     * @return void
     */
    public function testCarAuthenticatedCreationWrongData()
    {
        $payload = [
            'make' => "chevrolet",
            'model' => "cruze",
            'year' => -2000
        ];

        $user = factory(User::class, 1)->create()->first();

        $response = $this->actingAs($user, 'api')->postJson('/api/mock-add-car', $payload);

        //dd($response->getContent(),$response->getStatusCode());

        $response
            ->assertStatus(422)
            ->assertJson(['message' => 'The given data was invalid.']);
    }

    /**
     * A test for wrong data provided by authenticated user.
     *
     * @return void
     */
    public function testCarAuthenticatedCreationCorrectData()
    {
        $payload = [
            'make' => "porsche",
            'model' => "cayenne",
            'year' => 2005
        ];

        $user = factory(User::class, 1)->create()->first();

        $response = $this->actingAs($user, 'api')->postJson('/api/mock-add-car', $payload);

        //dd($response->getContent(),$response->getStatusCode());

        $response
            ->assertStatus(201)
            ->assertJson(['make' => 'porsche']);
    }

    /**
     * A test for wrong data provided by authenticated user.
     *
     * @return void
     */
    public function testCarNotAuthenticatedCreationCorrectData()
    {
        $payload = [
            'make' => "porsche",
            'model' => "cayenne",
            'year' => 2005
        ];

        $response = $this->postJson('/api/mock-add-car', $payload);

        //dd($response->getContent(),$response->getStatusCode());

        $response
            ->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    /**
     * A test for wrong data provided by authenticated user.
     *
     * @return void
     */
    public function testCarAuthenticatedFetchData()
    {
        $user = factory(User::class, 1)->create()->first();

        $car = new Car();

        $car->make = 'Test Make';
        $car->user_id = $user->id;
        $car->model = 'Test Model';
        $car->year = '1999';
        $car->save();

        $response = $this->actingAs($user, 'api')->get('/api/mock-get-car/' . $car->id);

        //dd($response->getContent(),$response->getStatusCode());

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'make' => 'Test Make'
                ]
            ]);
    }
}
