<?php

namespace App\Repositories;

interface AuthorRepositoryInterface extends RepositoryInterface
{
    /**
     * Find an author by name.
     *
     * @param string $name
     * @return mixed
     */
    public function findByName(string $name);

    /**
     * Find or create an author by name.
     *
     * @param string $name
     * @return mixed
     */
    public function findOrCreateByName(string $name);

    /**
     * Search authors by name with book count.
     *
     * @param string|null $name
     * @return mixed
     */
    public function searchAuthors(?string $name = null);
}
