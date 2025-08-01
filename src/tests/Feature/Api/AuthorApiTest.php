<?php

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can get list of authors with book count', function () {
    // Create test authors
    $author1 = Author::factory()->create(['name' => 'John Doe']);
    $author2 = Author::factory()->create(['name' => 'Jane Smith']);

    // Create books and attach to authors
    $book1 = Book::factory()->create();
    $book2 = Book::factory()->create();
    $book3 = Book::factory()->create();

    // Author 1 has 2 books
    $book1->authors()->attach($author1);
    $book2->authors()->attach($author1);

    // Author 2 has 1 book
    $book3->authors()->attach($author2);

    // Make request to the authors endpoint
    $response = $this->getJson('/api/authors');

    // Assert response is successful and has the correct structure
    $response->assertStatus(200)
        ->assertJsonCount(2, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'books_count',
                ]
            ]
        ]);

    // Assert the response contains the expected data
    $response->assertJsonFragment([
        'name' => 'John Doe',
        'books_count' => 2,
    ]);

    $response->assertJsonFragment([
        'name' => 'Jane Smith',
        'books_count' => 1,
    ]);
});

test('can search authors by name', function () {
    // Create test authors
    $author1 = Author::factory()->create(['name' => 'John Doe']);
    $author2 = Author::factory()->create(['name' => 'Jane Smith']);

    // Make request to search authors by name
    $response = $this->getJson('/api/authors?name=John');

    // Assert response is successful and contains only the matching author
    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment([
            'name' => 'John Doe',
        ])
        ->assertJsonMissing([
            'name' => 'Jane Smith',
        ]);
});

test('returns empty array when no authors match search', function () {
    // Create test authors
    $author1 = Author::factory()->create(['name' => 'John Doe']);
    $author2 = Author::factory()->create(['name' => 'Jane Smith']);

    // Make request with a search term that won't match any authors
    $response = $this->getJson('/api/authors?name=NonExistent');

    // Assert response is successful and contains an empty data array
    $response->assertStatus(200)
        ->assertJsonCount(0, 'data');
});
