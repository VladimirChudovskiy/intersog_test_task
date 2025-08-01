<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    /**
     * CategoryRepository constructor.
     *
     * @param Category $model
     */
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    /**
     * Find a category by name.
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
     * Find or create a category by name.
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
