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
}
