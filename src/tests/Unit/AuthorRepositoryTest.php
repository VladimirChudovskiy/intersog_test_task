<?php

use App\Models\Author;
use App\Models\Book;
use App\Repositories\AuthorRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->authorRepository = new AuthorRepository(new Author());
});

test('can search authors by name', function () {
    // Create test authors
    $author1 = Author::factory()->create(['name' => 'John Doe']);
    $author2 = Author::factory()->create(['name' => 'Jane Doe']);
    $author3 = Author::factory()->create(['name' => 'Bob Smith']);

    // Search for authors with 'Doe' in the name
    $results = $this->authorRepository->searchAuthors('Doe');

    // Assert that we found the right authors
    expect($results->count())->toBe(2);
    expect($results->pluck('id')->toArray())->toContain($author1->id);
    expect($results->pluck('id')->toArray())->toContain($author2->id);
    expect($results->pluck('id')->toArray())->not->toContain($author3->id);
});

test('returns all authors when no search term is provided', function () {
    // Create test authors
    $author1 = Author::factory()->create();
    $author2 = Author::factory()->create();
    $author3 = Author::factory()->create();

    // Get all authors
    $results = $this->authorRepository->searchAuthors();

    // Assert that we found all authors
    expect($results->count())->toBe(3);
    expect($results->pluck('id')->toArray())->toContain($author1->id);
    expect($results->pluck('id')->toArray())->toContain($author2->id);
    expect($results->pluck('id')->toArray())->toContain($author3->id);
});

test('includes book count in search results', function () {
    // Create test authors
    $author1 = Author::factory()->create(['name' => 'John Doe']);
    $author2 = Author::factory()->create(['name' => 'Jane Doe']);

    // Create books and attach to authors
    $book1 = Book::factory()->create();
    $book2 = Book::factory()->create();
    $book3 = Book::factory()->create();

    // Author 1 has 2 books
    $book1->authors()->attach($author1);
    $book2->authors()->attach($author1);

    // Author 2 has 1 book
    $book3->authors()->attach($author2);

    // Search for authors with 'Doe' in the name
    $results = $this->authorRepository->searchAuthors('Doe');

    // Assert that the book count is correct
    expect($results->count())->toBe(2);

    $author1Result = $results->firstWhere('id', $author1->id);
    $author2Result = $results->firstWhere('id', $author2->id);

    expect($author1Result->books_count)->toBe(2);
    expect($author2Result->books_count)->toBe(1);
});
