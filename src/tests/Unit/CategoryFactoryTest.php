<?php

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can create a category using factory', function () {
    $category = Category::factory()->create();

    expect($category)->toBeInstanceOf(Category::class);
    expect($category->name)->not->toBeEmpty();
});

test('can create multiple categories using factory', function () {
    $categories = Category::factory()->count(3)->create();

    expect($categories)->toHaveCount(3);
    expect($categories->first())->toBeInstanceOf(Category::class);
});

test('can override default values', function () {
    $customName = 'Custom Category Name';
    $category = Category::factory()->create(['name' => $customName]);

    expect($category->name)->toBe($customName);
});
