<?php

namespace App\Services;

interface BookParserServiceInterface
{
    /**
     * Parse books from a JSON URL.
     *
     * @param string $url
     * @return array
     */
    public function parseFromUrl(string $url): array;

    /**
     * Parse books from a JSON string.
     *
     * @param string $json
     * @return array
     */
    public function parseFromJson(string $json): array;

    /**
     * Update or create a book from parsed data.
     *
     * @param array $bookData
     * @return mixed
     */
    public function updateOrCreateBook(array $bookData);

    /**
     * Update or create multiple books from parsed data.
     *
     * @param array $booksData
     * @return array
     */
    public function updateOrCreateBooks(array $booksData): array;
}
