<?php

use App\Models\Author;
use App\Models\Book;
use App\Repositories\BookRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->bookRepository = new BookRepository(new Book());
});

test('can search books by title', function () {
    // Create test books
    $book1 = Book::factory()->create(['title' => 'Test Book One']);
    $book2 = Book::factory()->create(['title' => 'Test Book Two']);
    $book3 = Book::factory()->create(['title' => 'Another Book']);

    // Search for books with 'Test' in the title
    $results = $this->bookRepository->searchBooks('Test');

    // Assert that we found the right books
    expect($results->count())->toBe(2);
    expect($results->pluck('id')->toArray())->toContain($book1->id);
    expect($results->pluck('id')->toArray())->toContain($book2->id);
    expect($results->pluck('id')->toArray())->not->toContain($book3->id);
});

test('can search books by description', function () {
    // Create test books
    $book1 = Book::factory()->create(['short_description' => 'This is a test description']);
    $book2 = Book::factory()->create(['long_description' => 'This is another test description']);
    $book3 = Book::factory()->create(['short_description' => 'This is a different description']);

    // Search for books with 'test' in the description
    $results = $this->bookRepository->searchBooks(null, 'test');

    // Assert that we found the right books
    expect($results->count())->toBe(2);
    expect($results->pluck('id')->toArray())->toContain($book1->id);
    expect($results->pluck('id')->toArray())->toContain($book2->id);
    expect($results->pluck('id')->toArray())->not->toContain($book3->id);
});

test('can search books by author name', function () {
    // Create test authors
    $author1 = Author::factory()->create(['name' => 'John Doe']);
    $author2 = Author::factory()->create(['name' => 'Jane Smith']);

    // Create test books and attach authors
    $book1 = Book::factory()->create();
    $book1->authors()->attach($author1);

    $book2 = Book::factory()->create();
    $book2->authors()->attach($author2);

    // Search for books by author name
    $results = $this->bookRepository->searchBooks(null, null, 'John');

    // Assert that we found the right books
    expect($results->count())->toBe(1);
    expect($results->pluck('id')->toArray())->toContain($book1->id);
    expect($results->pluck('id')->toArray())->not->toContain($book2->id);
});

test('can search books by author id', function () {
    // Create test authors
    $author1 = Author::factory()->create();
    $author2 = Author::factory()->create();

    // Create test books and attach authors
    $book1 = Book::factory()->create();
    $book1->authors()->attach($author1);

    $book2 = Book::factory()->create();
    $book2->authors()->attach($author2);

    // Search for books by author ID
    $results = $this->bookRepository->searchBooks(null, null, null, $author1->id);

    // Assert that we found the right books
    expect($results->count())->toBe(1);
    expect($results->pluck('id')->toArray())->toContain($book1->id);
    expect($results->pluck('id')->toArray())->not->toContain($book2->id);
});

test('can get books by author', function () {
    // Create test authors
    $author = Author::factory()->create();

    // Create test books and attach authors
    $book1 = Book::factory()->create();
    $book1->authors()->attach($author);

    $book2 = Book::factory()->create();
    $book2->authors()->attach($author);

    $book3 = Book::factory()->create();

    // Get books by author
    $results = $this->bookRepository->getBooksByAuthor($author->id);

    // Assert that we found the right books
    expect($results->count())->toBe(2);
    expect($results->pluck('id')->toArray())->toContain($book1->id);
    expect($results->pluck('id')->toArray())->toContain($book2->id);
    expect($results->pluck('id')->toArray())->not->toContain($book3->id);
});
