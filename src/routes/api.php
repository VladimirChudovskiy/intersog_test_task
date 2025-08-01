<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\AuthorBooksController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Book routes
Route::get('/books', [BookController::class, 'index'])->name('books.index');

// Author routes
Route::get('/authors', [AuthorController::class, 'index'])->name('authors.index');

// Books by author route
Route::get('/authors/{author}/books', [AuthorBooksController::class, 'index'])->name('authors.books.index');
