<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Repositories\ProductRepository;
use App\Models\Product;
use Illuminate\Foundation\Testing\{
    WithFaker,
    RefreshDatabase
};

class ProductRepositoryTest extends TestCase
{
    use WithFaker,
        RefreshDatabase;


    private ?ProductRepository $productRepository;

    public function setUp(): void
    {
        $this->productRepository = new ProductRepository;
        parent::setUp();
    }

    private function productData(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'price' => $this->faker->randomNumber(5, true),
            'photo' => $this->faker->imageUrl(),
        ];
    }

    public function test_create_product(): void
    {
        $data = $this->productData();
        $this->productRepository->createProduct($data);
        $this->assertDatabaseHas('products', [
            'name' => $data['name'],
        ]);
    }

    public function test_list_products(): void
    {
        Product::factory()->count(5)->create();
        $data = $this->productRepository->listProducts(12, false);
        $total = count($data->getData());
        $this->assertEquals(5, $total);
    }

    public function test_get_by_id(): void
    {
        $data = Product::factory()->create();
        $client = $this->productRepository->getProductById($data['id']);
        $this->assertEquals($data['name'], $client->getData()->name);
        $this->assertEquals($data['id'], $client->getData()->id);
    }

    public function test_update_product(): void
    {
        $newName = 'meu novo name é teste';
        $data = Product::factory()->create();
        $this->productRepository->update($data['id'], [
            'name' => $newName,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $data['id'],
            'name' => $newName,
        ]);
    }

    public function test_delete_product(): void
    {
        $data = Product::factory()->create();
        $this->productRepository->delete($data['id']);

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
