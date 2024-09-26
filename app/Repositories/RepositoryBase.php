<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Database\Eloquent\Model;

abstract class RepositoryBase
{
    /**
     * Set a model
     * 
     * @var Model;
     */
    protected $model;

    /**
     * Create a new queryBuilder instace
     * 
     * @return Builder|QueryBuilder
     */
    public function query()
    {
        return app($this->model)
            ->newQuery();
    }

    /**
     * Structure a new query with pagination
     * but pagination is not required
     * 
     * @param Builder|QueryBuilder $query
     * @param int $take
     * @param bool $paginate
     * 
     * @return Collection|AbstractPaginator
     */
    public function makeQuery(
        $query = null,
        int $take = 12,
        bool $paginate = true
    )
    {
        if (null === $query) {
            $query = $this->query();
        }

        if (true === $paginate) {
            return $query->paginate($take);
        }

        if ($take && $take > 0) {
            $query->take($take);
        }

        return $query->get();
    }

    /**
     * Return all records from current model
     * 
     * @param int $take
     * @param bool $paginate
     * 
     * @return Collection|AbstractPaginator
     */
    public function getAll(int $take = 12, bool $paginate = true)
    {
        return $this->makeQuery(null, $take, $paginate);
    }

    /**
     * Retrieves a record by his id
     * 
     * @param int $id
     * @return Model
     */
    public function findById($id)
    {
        return $this->query()
            ->findOrFail($id);
    }

    /**
     * Save new data in database
     * 
     * @param array $data
     * @return Model
     */
    public function store(array $data)
    {
        $data = $this->query()
            ->create($data);

        return $data;
    }

    /**
     * Update entity from database
     * 
     * @param int $id
     * @param array $data
     * @return Model
     */
    public function update(int $id, array $data)
    {
        $entity = $this->findById($id);
        $entity->update($data);

        return $entity;
    }

    /**
     * Delete a entity from database
     * 
     * @param int $id
     * @return void
     */
    public function delete(int $id)
    {
        $entity = $this->findById($id);
        $entity->delete();
    }
}