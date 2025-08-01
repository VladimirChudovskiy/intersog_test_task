<?php

namespace App\Providers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Repositories\AuthorRepository;
use App\Repositories\AuthorRepositoryInterface;
use App\Repositories\BookRepository;
use App\Repositories\BookRepositoryInterface;
use App\Repositories\CategoryRepository;
use App\Repositories\CategoryRepositoryInterface;
use App\Services\BookParserService;
use App\Services\BookParserServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register repositories
        $this->app->bind(BookRepositoryInterface::class, function ($app) {
            return new BookRepository(new Book());
        });

        $this->app->bind(AuthorRepositoryInterface::class, function ($app) {
            return new AuthorRepository(new Author());
        });

        $this->app->bind(CategoryRepositoryInterface::class, function ($app) {
            return new CategoryRepository(new Category());
        });

        // Register parser service
        $this->app->bind(BookParserServiceInterface::class, function ($app) {
            return new BookParserService(
                $app->make(BookRepositoryInterface::class),
                $app->make(AuthorRepositoryInterface::class),
                $app->make(CategoryRepositoryInterface::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
