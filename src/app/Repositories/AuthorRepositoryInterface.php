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
}
