<?php

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can get list of books', function () {
    // Create test books with authors
    $author1 = Author::factory()->create(['name' => 'John Doe']);
    $author2 = Author::factory()->create(['name' => 'Jane Smith']);

    $book1 = Book::factory()->create([
        'title' => 'Test Book One',
        'short_description' => 'Description of book one',
        'published_date' => now()->subYear(),
    ]);
    $book1->authors()->attach($author1);

    $book2 = Book::factory()->create([
        'title' => 'Test Book Two',
        'short_description' => 'Description of book two',
        'published_date' => now()->subMonths(6),
    ]);
    $book2->authors()->attach([$author1->id, $author2->id]);

    // Make request to the books endpoint
    $response = $this->getJson('/api/books');

    // Assert response is successful and has the correct structure
    $response->assertStatus(200)
        ->assertJsonCount(2, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'authors',
                    'published_date',
                ]
            ]
        ]);

    // Assert the response contains the expected data
    $response->assertJsonFragment([
        'title' => 'Test Book One',
        'description' => 'Description of book one',
    ]);

    $response->assertJsonFragment([
        'title' => 'Test Book Two',
        'description' => 'Description of book two',
    ]);
});

test('can search books by title', function () {
    // Create test books
    $book1 = Book::factory()->create(['title' => 'Test Book One']);
    $book2 = Book::factory()->create(['title' => 'Another Book']);

    // Make request to search books by title
    $response = $this->getJson('/api/books?title=Test');

    // Assert response is successful and contains only the matching book
    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment([
            'title' => 'Test Book One',
        ])
        ->assertJsonMissing([
            'title' => 'Another Book',
        ]);
});

test('can search books by description', function () {
    // Create test books
    $book1 = Book::factory()->create([
        'short_description' => 'This is a test description',
    ]);
    $book2 = Book::factory()->create([
        'short_description' => 'This is a different description',
    ]);

    // Make request to search books by description
    $response = $this->getJson('/api/books?description=test');

    // Assert response is successful and contains only the matching book
    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment([
            'description' => 'This is a test description',
        ])
        ->assertJsonMissing([
            'description' => 'This is a different description',
        ]);
});

test('can search books by author name', function () {
    // Create test authors and books
    $author1 = Author::factory()->create(['name' => 'John Doe']);
    $author2 = Author::factory()->create(['name' => 'Jane Smith']);

    $book1 = Book::factory()->create(['title' => 'Book by John']);
    $book1->authors()->attach($author1);

    $book2 = Book::factory()->create(['title' => 'Book by Jane']);
    $book2->authors()->attach($author2);

    // Make request to search books by author name
    $response = $this->getJson('/api/books?author=John');

    // Assert response is successful and contains only the matching book
    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment([
            'title' => 'Book by John',
        ])
        ->assertJsonMissing([
            'title' => 'Book by Jane',
        ]);
});

test('can search books by author id', function () {
    // Create test authors and books
    $author1 = Author::factory()->create();
    $author2 = Author::factory()->create();

    $book1 = Book::factory()->create(['title' => 'Book by Author 1']);
    $book1->authors()->attach($author1);

    $book2 = Book::factory()->create(['title' => 'Book by Author 2']);
    $book2->authors()->attach($author2);

    // Make request to search books by author ID
    $response = $this->getJson('/api/books?author_id=' . $author1->id);

    // Assert response is successful and contains only the matching book
    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment([
            'title' => 'Book by Author 1',
        ])
        ->assertJsonMissing([
            'title' => 'Book by Author 2',
        ]);
});
