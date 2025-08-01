<?php

namespace App\Models;

use App\Enums\BookStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'isbn',
        'page_count',
        'published_date',
        'thumbnail_url',
        'short_description',
        'long_description',
        'status',
    ];

    protected $casts = [
        'published_date' => 'datetime',
        'status' => BookStatus::class,
    ];

    /**
     * The authors that belong to the book.
     */
    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class);
    }

    /**
     * The categories that belong to the book.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
