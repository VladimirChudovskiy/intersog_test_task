<?php

namespace App\Repositories;

use App\Models\Author;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthorRepository extends BaseRepository implements AuthorRepositoryInterface
{
    /**
     * AuthorRepository constructor.
     *
     * @param Author $model
     */
    public function __construct(Author $model)
    {
        parent::__construct($model);
    }

    /**
     * Find an author by name.
     *
     * @param string $name
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function findByName(string $name)
    {
        return $this->model->where('name', $name)->firstOrFail();
    }

    /**
     * Find or create an author by name.
     *
     * @param string $name
     * @return mixed|null
     */
    public function findOrCreateByName(string $name)
    {
        if (empty(trim($name))) {
            return null;
        }

        return $this->model->firstOrCreate(['name' => $name]);
    }
}
