<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\RepositoryBase;
use App\Models\Order;
use App\Models\OrderProductBond;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Exception;
use function response;

class OrderRepository extends RepositoryBase
{
    /**
     * Set model for this repository
     * 
     * @var Product
     */
    protected $model = Order::class;

    /**
     * @param Order $order
     * @param array $products
     */
    public function insertProductOnOrder(Order $order, array $products)
    {
        $arrayToInsert = [];

        foreach($products as $product) {
            for($i = $product['amount']; $i > 0; $i--) {
                array_push($arrayToInsert, [
                    'order_id' => $order->id,
                    'product_id' => $product['id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        OrderProductBond::insert($arrayToInsert);
        return $order->with('products')->first();
    }

    /**
     * @var string[] $data
     * @return JsonResponse
     */
    public function createOrder(array $data): JsonResponse
    {
        try {
            $order = $this->store($data);
            $toReturn = $this->insertProductOnOrder($order, $data['products']);
            return response()->json($toReturn, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json(
                sprintf('Error: %s', $e->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @var int $take
     * @var int $paginate
     * @return JsonResponse
     */
    public function listOrders(int $take, bool $paginate): JsonResponse
    {
        try {
            $data = Order::with('products')->get();
            return response()->json($data, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(
                sprintf('Error: %s', $e->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @var int $orderId
     * @return JsonResponse
     */
    public function getOrderById(int $orderId)
    {
        try {
            $data = $this->findById($orderId);
            $data = $data->with('products')->first();
            return response()->json($data, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(
                sprintf('Error: %s', $e->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @var int $orderId
     * @var array $products
     * @return JsonResponse
     */
    public function updateOrder(int $orderId, array $products)
    {
        try {
            OrderProductBond::where([
                'order_id' => $orderId,
            ])->delete();
            $order = $this->findById($orderId);
            $orderWithProducts = $this->insertProductOnOrder($order, $products);
            return response()->json($orderWithProducts, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(
                sprintf('Error: %s', $e->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @var int $orderId
     * @var array $products
     * @return JsonResponse
     */
    public function deleteOrder(int $orderId)
    {
        try {
            $this->delete($orderId);

            OrderProductBond::where([
                'order_id' => $orderId,
            ])->delete();

            return response()->json([], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(
                sprintf('Error: %s', $e->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}