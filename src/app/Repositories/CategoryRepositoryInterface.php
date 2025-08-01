<?php

namespace App\Repositories;

interface CategoryRepositoryInterface extends RepositoryInterface
{
    /**
     * Find a category by name.
     *
     * @param string $name
     * @return mixed
     */
    public function findByName(string $name);

    /**
     * Find or create a category by name.
     *
     * @param string $name
     * @return mixed
     */
    public function findOrCreateByName(string $name);
}
