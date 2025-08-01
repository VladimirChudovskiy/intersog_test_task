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

    /**
     * Search authors by name with book count.
     *
     * @param string|null $name
     * @return mixed
     */
    public function searchAuthors(?string $name = null)
    {
        $query = $this->model->withCount('books');

        if ($name) {
            $query->where('name', 'like', "%{$name}%");
        }

        return $query->paginate(10);
    }
}
