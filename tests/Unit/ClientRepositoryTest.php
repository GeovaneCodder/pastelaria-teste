<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Repositories\ClientRepository;
use App\Models\Client;
use Illuminate\Foundation\Testing\{
    WithFaker,
    RefreshDatabase
};

class ClientRepositoryTest extends TestCase
{
    use WithFaker,
        RefreshDatabase;


    private ?ClientRepository $clientRepository;

    public function setUp(): void
    {
        $this->clientRepository = new ClientRepository;
        parent::setUp();
    }

    private function clientData(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'phone_number' => $this->faker->cellphone(false),
            'birthday' => $this->faker->date,
            'address' => $this->faker->streetAddress,
            'complement' => $this->faker->word,
            'neighborhood' => $this->faker->word,
            'postal_code' => str_ireplace('-', '', $this->faker->postcode),
        ];
    }

    public function test_create_client(): void
    {
        $data = $this->clientData();
        $this->clientRepository->createClient($data);
        $this->assertDatabaseHas('clients', [
            'email' => $data['email'],
        ]);
    }

    public function test_list_clients(): void
    {
        Client::factory()->count(5)->create();
        $data = $this->clientRepository->listClients(12, false);
        $total = count($data->getData());
        $this->assertEquals(5, $total);
    }

    public function test_get_by_id(): void
    {
        $data = Client::factory()->create();
        $client = $this->clientRepository->getClientById($data['id']);
        $this->assertEquals($data['email'], $client->getData()->email);
    }

    public function test_update_client(): void
    {
        $newEmail = 'new_client_email@teste.com';
        $data = Client::factory()->create();
        $this->clientRepository->update($data['id'], [
            'email' => $newEmail,
        ]);

        $this->assertDatabaseHas('clients', [
            'email' => $newEmail,
        ]);
    }

    public function test_delete_client(): void
    {
        $data = Client::factory()->create();
        $this->clientRepository->delete($data['id']);

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
