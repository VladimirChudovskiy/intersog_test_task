<?php

use App\Enums\BookStatus;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Repositories\AuthorRepository;
use App\Repositories\BookRepository;
use App\Repositories\CategoryRepository;
use App\Services\BookParserService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->bookRepository = new BookRepository(new Book());
    $this->authorRepository = new AuthorRepository(new Author());
    $this->categoryRepository = new CategoryRepository(new Category());

    $this->parserService = new BookParserService(
        $this->bookRepository,
        $this->authorRepository,
        $this->categoryRepository
    );
});

test('can parse from json', function () {
    $json = '[
        {
            "title": "Test Book",
            "isbn": "1234567890",
            "pageCount": 200,
            "publishedDate": { "$date": "2023-01-01T00:00:00.000-0700" },
            "thumbnailUrl": "https://example.com/thumbnail.jpg",
            "shortDescription": "A short description",
            "longDescription": "A longer description",
            "status": "PUBLISH",
            "authors": ["John Doe", "Jane Smith"],
            "categories": ["Fiction", "Drama"]
        }
    ]';

    $data = $this->parserService->parseFromJson($json);

    expect($data)->toBeArray()
        ->toHaveCount(1);
    expect($data[0]['title'])->toBe("Test Book");
    expect($data[0]['isbn'])->toBe("1234567890");
});

test('can create a book with authors and categories', function () {
    $bookData = [
        "title" => "Test Book",
        "isbn" => "1234567890",
        "pageCount" => 200,
        "publishedDate" => ['$date' => "2023-01-01T00:00:00.000-0700"],
        "thumbnailUrl" => "https://example.com/thumbnail.jpg",
        "shortDescription" => "A short description",
        "longDescription" => "A longer description",
        "status" => "PUBLISH",
        "authors" => ["John Doe", "Jane Smith"],
        "categories" => ["Fiction", "Drama"]
    ];

    $book = $this->parserService->updateOrCreateBook($bookData);

    expect($book)->toBeInstanceOf(Book::class);
    expect($book->title)->toBe("Test Book");
    expect($book->isbn)->toBe("1234567890");
    expect($book->page_count)->toBe(200);
    expect($book->thumbnail_url)->toBe("https://example.com/thumbnail.jpg");
    expect($book->short_description)->toBe("A short description");
    expect($book->long_description)->toBe("A longer description");
    expect($book->status)->toBe(BookStatus::PUBLISH);

    // Check relationships
    expect($book->authors)->toHaveCount(2);
    expect($book->categories)->toHaveCount(2);

    $authorNames = $book->authors->pluck('name')->toArray();
    expect($authorNames)->toContain("John Doe");
    expect($authorNames)->toContain("Jane Smith");

    $categoryNames = $book->categories->pluck('name')->toArray();
    expect($categoryNames)->toContain("Fiction");
    expect($categoryNames)->toContain("Drama");
});

test('can update an existing book', function () {
    // Create initial book
    $initialData = [
        "title" => "Initial Title",
        "isbn" => "1234567890",
        "pageCount" => 100,
        "status" => "PUBLISH",
        "authors" => ["Initial Author"],
        "categories" => ["Initial Category"]
    ];

    $this->parserService->updateOrCreateBook($initialData);

    // Update the book
    $updatedData = [
        "title" => "Updated Title",
        "isbn" => "1234567890", // Same ISBN
        "pageCount" => 200,
        "status" => "MEAP",
        "authors" => ["New Author"],
        "categories" => ["New Category"]
    ];

    $book = $this->parserService->updateOrCreateBook($updatedData);

    // Check that the book was updated
    expect($book->title)->toBe("Updated Title");
    expect($book->isbn)->toBe("1234567890");
    expect($book->page_count)->toBe(200);
    expect($book->status)->toBe(BookStatus::MEAP);

    // Check relationships were updated
    expect($book->authors)->toHaveCount(1);
    expect($book->categories)->toHaveCount(1);

    $authorNames = $book->authors->pluck('name')->toArray();
    expect($authorNames)->toContain("New Author");
    expect($authorNames)->not->toContain("Initial Author");

    $categoryNames = $book->categories->pluck('name')->toArray();
    expect($categoryNames)->toContain("New Category");
    expect($categoryNames)->not->toContain("Initial Category");
});

test('handles invalid status', function () {
    $bookData = [
        "title" => "Test Book",
        "isbn" => "1234567890",
        "status" => "INVALID_STATUS",
    ];

    $book = $this->parserService->updateOrCreateBook($bookData);

    // Should use default status
    expect($book->status)->toBe(BookStatus::PUBLISH);
});

test('throws exception when isbn is missing', function () {
    $bookData = [
        "title" => "Test Book",
    ];

    expect(fn() => $this->parserService->updateOrCreateBook($bookData))
        ->toThrow(\Exception::class, "ISBN is required");
});

test('handles empty author and category names', function () {
    $bookData = [
        "title" => "Test Book with Empty Relations",
        "isbn" => "9876543210",
        "authors" => ["Valid Author", "", "  "],
        "categories" => ["Valid Category", "", "  "]
    ];

    $book = $this->parserService->updateOrCreateBook($bookData);

    // Check that only valid authors and categories were created
    expect($book->authors)->toHaveCount(1);
    expect($book->categories)->toHaveCount(1);

    $authorNames = $book->authors->pluck('name')->toArray();
    expect($authorNames)->toContain("Valid Author");

    $categoryNames = $book->categories->pluck('name')->toArray();
    expect($categoryNames)->toContain("Valid Category");

    // Verify no empty records were created in the database
    expect(Author::where('name', 'Valid Author')->count())->toBe(1);
    expect(Author::where('name', '')->count())->toBe(0);

    expect(Category::where('name', 'Valid Category')->count())->toBe(1);
    expect(Category::where('name', '')->count())->toBe(0);
});
