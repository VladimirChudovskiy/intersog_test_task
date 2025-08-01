# Book Parser

This module provides functionality to parse book data from a JSON source and update the database.

## Features

- Parse books from a JSON URL or string
- Update or create books based on ISBN
- Handle relationships with authors and categories
- Error handling for various scenarios

## Usage

### Command Line

You can use the provided Artisan command to parse books from a URL:

```bash
php artisan books:parse
```

By default, it will use the URL: https://raw.githubusercontent.com/bvaughn/infinite-list-reflow-examples/refs/heads/master/books.json

You can also specify a custom URL:

```bash
php artisan books:parse https://example.com/books.json
```

### Programmatic Usage

```php
use App\Services\BookParserServiceInterface;

// Inject the service
public function example(BookParserServiceInterface $bookParserService)
{
    // Parse from URL
    $booksData = $bookParserService->parseFromUrl('https://example.com/books.json');
    
    // Update or create books
    $results = $bookParserService->updateOrCreateBooks($booksData);
    
    // Or update a single book
    $bookData = [
        'title' => 'Example Book',
        'isbn' => '1234567890',
        'status' => 'PUBLISH',
        'authors' => ['Author Name'],
        'categories' => ['Category Name']
    ];
    
    $book = $bookParserService->updateOrCreateBook($bookData);
}
```

## Error Handling

The parser handles various error cases:

- Missing ISBN or title
- Invalid JSON format
- Network errors when fetching from URL
- Unknown status values (defaults to PUBLISH)
- Invalid date formats

Errors are logged and can be caught using try-catch blocks.

## Database Structure

The parser works with the following database tables:

- `books`: Stores book information
- `authors`: Stores author information
- `categories`: Stores category information
- `author_book`: Pivot table for book-author relationships
- `book_category`: Pivot table for book-category relationships

## Testing

Unit tests are available in `tests/Unit/BookParserServiceTest.php`.

Run the tests with:

```bash
php artisan test --filter=BookParserServiceTest
```
