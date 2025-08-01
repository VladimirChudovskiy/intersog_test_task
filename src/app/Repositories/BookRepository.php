<?php

namespace App\Repositories;

use App\Models\Book;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BookRepository extends BaseRepository implements BookRepositoryInterface
{
    /**
     * BookRepository constructor.
     *
     * @param Book $model
     */
    public function __construct(Book $model)
    {
        parent::__construct($model);
    }

    /**
     * Find a book by ISBN.
     *
     * @param string $isbn
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function findByIsbn(string $isbn)
    {
        return $this->model->where('isbn', $isbn)->firstOrFail();
    }

    /**
     * Update or create a book by ISBN.
     *
     * @param string $isbn
     * @param array $data
     * @return mixed
     */
    public function updateOrCreateByIsbn(string $isbn, array $data)
    {
        return $this->model->updateOrCreate(['isbn' => $isbn], $data);
    }
}
