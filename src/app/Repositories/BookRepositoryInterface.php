<?php

namespace App\Repositories;

interface BookRepositoryInterface extends RepositoryInterface
{
    /**
     * Find a book by ISBN.
     *
     * @param string $isbn
     * @return mixed
     */
    public function findByIsbn(string $isbn);

    /**
     * Update or create a book by ISBN.
     *
     * @param string $isbn
     * @param array $data
     * @return mixed
     */
    public function updateOrCreateByIsbn(string $isbn, array $data);

    /**
     * Search books by title, description, author name, or author ID.
     *
     * @param string|null $title
     * @param string|null $description
     * @param string|null $author
     * @param int|null $authorId
     * @return mixed
     */
    public function searchBooks(?string $title = null, ?string $description = null, ?string $author = null, ?int $authorId = null);

    /**
     * Get books by author ID.
     *
     * @param int $authorId
     * @return mixed
     */
    public function getBooksByAuthor(int $authorId);
}
