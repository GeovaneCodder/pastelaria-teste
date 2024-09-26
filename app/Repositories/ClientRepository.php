<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\RepositoryBase;
use App\Models\Client;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Exception;
use function response;

class ClientRepository extends RepositoryBase
{
    /**
     * Set model for this repository
     * 
     * @var Client
     */
    protected $model = Client::class;

    /**
     * @var string[] $data
     * @return JsonResponse
     */
    public function createClient(array $data): JsonResponse
    {
        try {
            $data = $this->store($data);
            return response()->json($data, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json(
                sprintf('Error: %s', $e->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @var string[] $data
     * @return JsonResponse
     */
    public function listClients(int $take, bool $paginate): JsonResponse
    {
        try {
            $data = $this->getAll($take, $paginate);
            return response()->json($data, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(
                sprintf('Error: %s', $e->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @var string[] $data
     * @return JsonResponse
     */
    public function getClientById(int $id): JsonResponse
    {
        try {
            $data = $this->findById($id);
            return response()->json($data, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(
                sprintf('Error: %s', $e->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @var string[] $data
     * @return JsonResponse
     */
    public function updateClient(int $id, array $data): JsonResponse
    {
        try {
            $data = $this->update($id, $data);
            return response()->json($data, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(
                sprintf('Error: %s', $e->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @var string[] $data
     * @return JsonResponse
     */
    public function deleteClient(int $id): JsonResponse
    {
        try {
            $data = $this->delete($id);
            return response()->json($data, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(
                sprintf('Error: %s', $e->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}