<?php

namespace App\Http\Controllers\V1;

use App\Repositories\ClientRepository;
use Illuminate\Http\JsonResponse;
use App\Http\{
    Controllers\Controller,
    Requests\V1\Client\CreateRequest
};

class ClientController extends Controller
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
     * @var ClientRepository
     */
    private ?ClientRepository $repository;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->repository = new ClientRepository;
    }

    /**
     * List all clients
     */
    public function index(): JsonResponse
    {
        return $this->repository->listClients(
            self::TOTAL_REGISTERS,
            self::PAGINATE
        );
    }

    /**
     * Create a new client method
     * @param CreateRequest $request
     */
    public function store(CreateRequest $request): JsonResponse
    {
        $data = $request->toArray();
        return $this->repository->createClient($data);
    }

    /**
     * Get a client by id
     * @param int $id
     */
    public function show(int $id): JsonResponse
    {
        return $this->repository->getClientById($id);
    }

    /**
     * Update client
     * @param int $id
     */
    public function update(int $id): JsonResponse
    {
        return $this->repository->updateClient($id, request()->toArray());
    }

    /**
     * Delete client
     * @param int $id
     */
    public function destroy(int $id): JsonResponse
    {
        return $this->repository->deleteClient($id, request()->toArray());
    }
}
