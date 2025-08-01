<?php

namespace App\Services;

use App\Enums\BookStatus;
use App\Models\Book;
use App\Repositories\AuthorRepositoryInterface;
use App\Repositories\BookRepositoryInterface;
use App\Repositories\CategoryRepositoryInterface;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BookParserService implements BookParserServiceInterface
{
    /**
     * @var BookRepositoryInterface
     */
    protected $bookRepository;

    /**
     * @var AuthorRepositoryInterface
     */
    protected $authorRepository;

    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * BookParserService constructor.
     *
     * @param BookRepositoryInterface $bookRepository
     * @param AuthorRepositoryInterface $authorRepository
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        BookRepositoryInterface $bookRepository,
        AuthorRepositoryInterface $authorRepository,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->bookRepository = $bookRepository;
        $this->authorRepository = $authorRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Parse books from a JSON URL.
     *
     * @param string $url
     * @return array
     * @throws Exception
     */
    public function parseFromUrl(string $url): array
    {
        try {
            $response = Http::get($url);

            if ($response->failed()) {
                throw new Exception("Failed to fetch data from URL: {$url}. Status code: {$response->status()}");
            }

            return $this->parseFromJson($response->body());
        } catch (Exception $e) {
            Log::error("Error parsing books from URL: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Parse books from a JSON string.
     *
     * @param string $json
     * @return array
     * @throws Exception
     */
    public function parseFromJson(string $json): array
    {
        try {
            $data = json_decode($json, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Invalid JSON: " . json_last_error_msg());
            }

            if (!is_array($data)) {
                throw new Exception("JSON does not contain an array");
            }

            return $data;
        } catch (Exception $e) {
            Log::error("Error parsing JSON: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Update or create a book from parsed data.
     *
     * @param array $bookData
     * @return Book
     * @throws Exception
     */
    public function updateOrCreateBook(array $bookData)
    {
        DB::beginTransaction();

        try {
            // Validate required fields
            if (empty($bookData['isbn'])) {
                throw new Exception("ISBN is required");
            }

            if (empty($bookData['title'])) {
                throw new Exception("Title is required");
            }

            // Prepare book data
            $isbn = $bookData['isbn'];
            $bookAttributes = [
                'title' => $bookData['title'],
                'page_count' => $bookData['pageCount'] ?? null,
                'thumbnail_url' => $bookData['thumbnailUrl'] ?? null,
                'short_description' => $bookData['shortDescription'] ?? null,
                'long_description' => $bookData['longDescription'] ?? null,
            ];

            // Handle published date
            if (isset($bookData['publishedDate']['$date'])) {
                try {
                    $bookAttributes['published_date'] = Carbon::parse($bookData['publishedDate']['$date']);
                } catch (Exception $e) {
                    Log::warning("Invalid published date for ISBN {$isbn}: {$e->getMessage()}");
                }
            }

            // Handle status
            if (isset($bookData['status'])) {
                $status = BookStatus::fromString($bookData['status']);
                if ($status === null) {
                    Log::warning("Unknown status '{$bookData['status']}' for ISBN {$isbn}, using default");
                    $bookAttributes['status'] = BookStatus::PUBLISH;
                } else {
                    $bookAttributes['status'] = $status;
                }
            } else {
                $bookAttributes['status'] = BookStatus::PUBLISH;
            }

            // Create or update the book
            $book = $this->bookRepository->updateOrCreateByIsbn($isbn, $bookAttributes);

            // Handle authors
            if (isset($bookData['authors']) && is_array($bookData['authors'])) {
                $authorIds = [];
                foreach ($bookData['authors'] as $authorName) {
                    $author = $this->authorRepository->findOrCreateByName($authorName);
                    if ($author !== null) {
                        $authorIds[] = $author->id;
                    }
                }
                $book->authors()->sync($authorIds);
            }

            // Handle categories
            if (isset($bookData['categories']) && is_array($bookData['categories'])) {
                $categoryIds = [];
                foreach ($bookData['categories'] as $categoryName) {
                    $category = $this->categoryRepository->findOrCreateByName($categoryName);
                    if ($category !== null) {
                        $categoryIds[] = $category->id;
                    }
                }
                $book->categories()->sync($categoryIds);
            }

            DB::commit();
            return $book;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Error updating/creating book: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Update or create multiple books from parsed data.
     *
     * @param array $booksData
     * @return array
     */
    public function updateOrCreateBooks(array $booksData): array
    {
        $results = [
            'success' => [],
            'errors' => []
        ];

        foreach ($booksData as $index => $bookData) {
            try {
                $book = $this->updateOrCreateBook($bookData);
                $results['success'][] = [
                    'isbn' => $book->isbn,
                    'title' => $book->title
                ];
            } catch (Exception $e) {
                $isbn = $bookData['isbn'] ?? "unknown-{$index}";
                $results['errors'][] = [
                    'isbn' => $isbn,
                    'error' => $e->getMessage()
                ];
            }
        }

        return $results;
    }
}
