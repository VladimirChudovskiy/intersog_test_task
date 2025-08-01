<?php

namespace App\Repositories;

interface RepositoryInterface
{
    /**
     * Get all resources.
     *
     * @return mixed
     */
    public function all();

    /**
     * Find a resource by id.
     *
     * @param int $id
     * @return mixed
     */
    public function find($id);

    /**
     * Create a new resource.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update a resource.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update($id, array $data);

    /**
     * Delete a resource.
     *
     * @param int $id
     * @return mixed
     */
    public function delete($id);
}
