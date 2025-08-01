<?php

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can get books by author', function () {
    // Create test authors
    $author = Author::factory()->create(['name' => 'John Doe']);
    $otherAuthor = Author::factory()->create(['name' => 'Jane Smith']);

    // Create books and attach to authors
    $book1 = Book::factory()->create([
        'title' => 'Book One by John',
        'short_description' => 'Description of book one',
        'published_date' => now()->subYear(),
    ]);
    $book1->authors()->attach($author);

    $book2 = Book::factory()->create([
        'title' => 'Book Two by John',
        'short_description' => 'Description of book two',
        'published_date' => now()->subMonths(6),
    ]);
    $book2->authors()->attach($author);

    $book3 = Book::factory()->create([
        'title' => 'Book by Jane',
        'short_description' => 'Description of book three',
    ]);
    $book3->authors()->attach($otherAuthor);

    // Make request to the author's books endpoint
    $response = $this->getJson("/api/authors/{$author->id}/books");

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
        'title' => 'Book One by John',
        'description' => 'Description of book one',
    ]);

    $response->assertJsonFragment([
        'title' => 'Book Two by John',
        'description' => 'Description of book two',
    ]);

    // Assert the response does not contain books by other authors
    $response->assertJsonMissing([
        'title' => 'Book by Jane',
    ]);
});

test('returns 404 when author does not exist', function () {
    // Make request with a non-existent author ID
    $response = $this->getJson('/api/authors/999/books');

    // Assert response is a 404 not found
    $response->assertStatus(404);
});

test('returns empty array when author has no books', function () {
    // Create an author with no books
    $author = Author::factory()->create();

    // Make request to the author's books endpoint
    $response = $this->getJson("/api/authors/{$author->id}/books");

    // Assert response is successful and contains an empty data array
    $response->assertStatus(200)
        ->assertJsonCount(0, 'data');
});
