<?php

namespace Tests\Feature;

use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Product;
use Illuminate\{
    Foundation\Testing\RefreshDatabase,
    Foundation\Testing\WithFaker,
    Testing\Fluent\AssertableJson
};

class ProductCrudTest extends TestCase
{
    use WithFaker,
        RefreshDatabase;

    const PRODUCT_URL = '/api/v1/product';

    public function test_list_product_route()
    {
        $response = $this->get(self::PRODUCT_URL);
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_create_product_route()
    {
        $data = [
            'name' => $this->faker->sentence(3),
            'price' => $this->faker->randomNumber(5, true),
            'photo' => $this->faker->imageUrl(),
        ];

        $response = $this->postJson(self::PRODUCT_URL, $data);
        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('products', [
            'id' => $response->getData()->id,
        ]);
    }

    public function test_show_product_route()
    {
        $data = Product::factory()->create();
        $response = $this->get(self::PRODUCT_URL . '/' . $data['id']);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('id', $data['id'])
                    ->etc());
    }

    public function test_update_product_route()
    {
        $newProductName = 'New product name for test';
        $data = Product::factory()->create();
        $response = $this->put(self::PRODUCT_URL . '/' . $data['id'], [
            'name' => $newProductName,
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseMissing('products', [
            'id' => $data['id'],
            'name' => $data['name'],
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $data['id'],
            'name' => $newProductName,
        ]);
    }

    public function test_delete_product_route()
    {
        $data = Product::factory()->create();
        $response = $this->delete(self::PRODUCT_URL . '/' . $data['id']);
        $response->assertStatus(Response::HTTP_OK);

        $this->assertSoftDeleted($data);
        $this->assertDatabaseHas('products', [
            'id' => $data['id']
        ]);

        $this->assertDatabaseMissing('products', [
            'id' => $data['id'],
            'deleted_at' => null
        ]);
    }
}
