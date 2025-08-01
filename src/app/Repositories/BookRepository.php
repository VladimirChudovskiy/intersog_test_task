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

    /**
     * Search books by title, description, author name, or author ID.
     *
     * @param string|null $title
     * @param string|null $description
     * @param string|null $author
     * @param int|null $authorId
     * @return mixed
     */
    public function searchBooks(?string $title = null, ?string $description = null, ?string $author = null, ?int $authorId = null)
    {
        $query = $this->model->with('authors');

        if ($title) {
            $query->where('title', 'like', "%{$title}%");
        }

        if ($description) {
            $query->where(function($q) use ($description) {
                $q->where('short_description', 'like', "%{$description}%")
                  ->orWhere('long_description', 'like', "%{$description}%");
            });
        }

        if ($author) {
            $query->whereHas('authors', function($q) use ($author) {
                $q->where('name', 'like', "%{$author}%");
            });
        }

        if ($authorId) {
            $query->whereHas('authors', function($q) use ($authorId) {
                $q->where('authors.id', $authorId);
            });
        }

        return $query->paginate(10);
    }

    /**
     * Get books by author ID.
     *
     * @param int $authorId
     * @return mixed
     */
    public function getBooksByAuthor(int $authorId)
    {
        return $this->model->with('authors')
            ->whereHas('authors', function($q) use ($authorId) {
                $q->where('authors.id', $authorId);
            })
            ->paginate(10);
    }
}
