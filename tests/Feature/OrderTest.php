<?php

namespace Tests\Feature;

use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\{
    Testing\RefreshDatabase,
    Testing\WithFaker
};

use App\Models\Order;
use App\Models\Product;

class OrderTest extends TestCase
{
    use WithFaker,
        RefreshDatabase;

    const ORDER_URL = '/api/v1/order';

    public function test_list_orders()
    {
        Order::factory(10)->create();

        $response = $this->get(self::ORDER_URL);
        $this->assertCount(10, $response->getData());
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_create_order()
    {
        $products = Product::factory(2)->create();
        $order = Order::factory()->create();
        $orderData = [
            'client_id' => $order->client_id,
            'products' =>  [
                [
                    'id' => $products[0]->id,
                    'amount' => $this->faker->randomDigitNotNull(),
                ],
                [
                    'id' => $products[1]->id,
                    'amount' => $this->faker->randomDigitNotNull(),
                ]
            ],        
        ];

        $response = $this->postJson(self::ORDER_URL, $orderData);
        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('orders', [
            'id' => $response->getData()->id,
            'client_id' => $orderData['client_id'],
        ]);
    }

    public function test_update_order_products()
    {
        $product = Product::factory(2)->create();
        $order = Order::factory()->create();
        
        $productsData = [
            [
                'id' => $product[0]->id,
                'amount' => $this->faker->randomDigitNotNull(),
            ],
            [
                'id' => $product[1]->id,
                'amount' => $this->faker->randomDigitNotNull(),
            ],        
        ];

        $response = $this->putJson(self::ORDER_URL . '/' . $order->id, $productsData);
        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('orders', [
            'id' => $response->getData()->id,
        ]);

        $this->assertDatabaseHas('order_product_bonds', [
            'order_id' => $response->getData()->id,
        ]);
    }

    public function test_delete_order()
    {
        $product = Product::factory(2)->create();
        $order = Order::factory()->create();
        
        $productsData = [
            [
                'id' => $product[0]->id,
                'amount' => $this->faker->randomDigitNotNull(),
            ],
            [
                'id' => $product[1]->id,
                'amount' => $this->faker->randomDigitNotNull(),
            ],        
        ];

        $response = $this->putJson(self::ORDER_URL . '/' . $order->id, $productsData);
        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
        ]);

        $this->assertDatabaseHas('order_product_bonds', [
            'order_id' => $order->id,
        ]);

        $deleteRequest = $this->delete(self::ORDER_URL . '/' . $order->id);
        $deleteRequest->assertStatus(Response::HTTP_OK);

        $this->assertSoftDeleted($order);
        $this->assertDatabaseHas('orders', [
            'id' => $response->getData()->id,
        ]);

        $this->assertDatabaseMissing('orders', [
            'id' => $response->getData()->id,
            'deleted_at' => null,
        ]);

        $this->assertDatabaseMissing('order_product_bonds', [
            'order_id' => $response->getData()->id,
            'deleted_at' => null,
        ]);
    }
}
