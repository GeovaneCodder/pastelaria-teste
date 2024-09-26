<?php

namespace Tests\Feature;

use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\{
    Testing\RefreshDatabase,
    Testing\WithFaker
};

class OrderTest extends TestCase
{
    use WithFaker,
        RefreshDatabase;

    const ORDER_URL = '/api/v1/order';

    public function test_create_order()
    {

        $response = $this->postJson(self::ORDER_URL, $data);
        $response->assertStatus(Response::HTTP_CREATED);
    }
}
