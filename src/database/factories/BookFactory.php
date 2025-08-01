<?php

namespace Database\Factories;

use App\Enums\BookStatus;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'isbn' => fake()->unique()->isbn13(),
            'page_count' => fake()->numberBetween(50, 1000),
            'published_date' => fake()->dateTimeThisDecade(),
            'thumbnail_url' => fake()->imageUrl(200, 300, 'books'),
            'short_description' => fake()->paragraph(),
            'long_description' => fake()->paragraphs(3, true),
            'status' => fake()->randomElement([BookStatus::PUBLISH, BookStatus::MEAP]),
        ];
    }
}
