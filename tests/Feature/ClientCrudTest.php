<?php

namespace Tests\Feature;

use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Client;
use Illuminate\{
    Foundation\Testing\RefreshDatabase,
    Foundation\Testing\WithFaker,
    Testing\Fluent\AssertableJson
};

class ClientCrudTest extends TestCase
{
    use WithFaker,
        RefreshDatabase;

    const CLIENT_URL = '/api/v1/client';

    public function test_list_client_route()
    {
        $response = $this->get(self::CLIENT_URL);
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_create_client_route()
    {
        $data = [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'phone_number' => $this->faker->landlineNumber(false),
            'birthday' => $this->faker->date,
            'address' => $this->faker->streetAddress,
            'complement' => $this->faker->word,
            'neighborhood' => $this->faker->word,
            'postal_code' => str_ireplace('-', '', $this->faker->postcode),
        ];

        $response = $this->postJson(self::CLIENT_URL, $data);
        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('clients', [
            'email' => $data['email'],
        ]);
    }

    public function test_show_client_route()
    {
        $data = Client::factory()->create();
        $response = $this->get(self::CLIENT_URL . '/' . $data['id']);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('email', $data['email'])
                    ->etc());
    }

    public function test_update_client_route()
    {
        $data = Client::factory()->create();
        $response = $this->put(self::CLIENT_URL . '/' . $data['id'], [
            'email' => 'new_email_updated@email.com',
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseMissing('clients', [
            'id' => $data['id'],
            'email' => $data['email'],
        ]);
    }

    public function test_delete_client_route()
    {
        $data = Client::factory()->create();
        $response = $this->delete(self::CLIENT_URL . '/' . $data['id']);
        $response->assertStatus(Response::HTTP_OK);

        $this->assertSoftDeleted($data);
        $this->assertDatabaseHas('clients', [
            'id' => $data['id']
        ]);

        $this->assertDatabaseMissing('clients', [
            'id' => $data['id'],
            'deleted_at' => null
        ]);
    }
}
