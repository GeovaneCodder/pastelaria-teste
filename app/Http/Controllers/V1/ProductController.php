<?php

namespace App\Http\Controllers\V1;

use App\Repositories\ProductRepository;
use Illuminate\Http\JsonResponse;
use App\Http\{
    Controllers\Controller,
    Requests\V1\Product\CreateRequest
};

class ProductController extends Controller
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
     * @var ProductRepository
     */
    private ?ProductRepository $repository;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->repository = new ProductRepository;
    }

    /**
     * List all products
     */
    public function index(): JsonResponse
    {
        return $this->repository->listProducts(
            self::TOTAL_REGISTERS,
            self::PAGINATE
        );
    }

    /**
     * Create a new product method
     * @param CreateRequest $request
     */
    public function store(CreateRequest $request): JsonResponse
    {
        $data = $request->toArray();
        return $this->repository->createProduct($data);
    }

    /**
     * Get a product by id
     * @param int $id
     */
    public function show(int $id): JsonResponse
    {
        return $this->repository->getProductById($id);
    }

    /**
     * Update product
     * @param int $id
     */
    public function update(int $id): JsonResponse
    {
        return $this->repository->updateProduct($id, request()->toArray());
    }

    /**
     * Delete product
     * @param int $id
     */
    public function destroy(int $id): JsonResponse
    {
        return $this->repository->deleteProduct($id, request()->toArray());
    }
}
