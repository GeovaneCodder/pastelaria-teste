<?php

namespace App\Http\Controllers\V1;

use App\Repositories\OrderRepository;
use Illuminate\Http\JsonResponse;
use App\Http\{
    Controllers\Controller,
    Requests\V1\Order\CreateRequest
};

class OrderController extends Controller
{
    /**
     * @var bool
     */
    const PAGINATE = true;

    /**
     * @var int
     */
    const TOTAL_REGISTERS = 10;

    /**
     * @var OrderRepository
     */
    private ?OrderRepository $repository;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->repository = new OrderRepository;
    }

    /**
     * List all orders
     */
    public function index(): JsonResponse
    {
        return $this->repository->listOrders(
            self::TOTAL_REGISTERS,
            self::PAGINATE
        );
    }

    /**
     * Create a new order method
     * @param CreateRequest $request
     */
    public function store(CreateRequest $request): JsonResponse
    {
        $data = $request->toArray();
        return $this->repository->createOrder($data);
    }

    /**
     * Get a order by id
     * @param int $id
     */
    public function show(int $id): JsonResponse
    {
        return $this->repository->getOrderById($id);
    }

    /**
     * Update order
     * @param int $id
     */
    public function update(int $id): JsonResponse
    {
        return $this->repository->updateOrder($id, request()->toArray());
    }

    /**
     * Delete order
     * @param int $id
     */
    public function destroy(int $id): JsonResponse
    {
        return $this->repository->deleteOrder($id);
    }
}
