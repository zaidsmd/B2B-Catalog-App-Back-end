<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Product\Models\Category;
use Modules\Product\Models\Product;

uses(RefreshDatabase::class);

it('sorts products by category name ascending', function (): void {
    $catA = Category::query()->create([
        'name' => 'Alpha',
        'slug' => 'alpha',
    ]);
    $catB = Category::query()->create([
        'name' => 'Beta',
        'slug' => 'beta',
    ]);

    $p1 = Product::query()->create([
        'name' => 'Product 1',
        'slug' => 'product-1',
        'price' => 10,
        'quantity' => 5,
        'stockable' => true,
        'active' => true,
        'category_id' => $catB->id, // Beta
    ]);

    $p2 = Product::query()->create([
        'name' => 'Product 2',
        'slug' => 'product-2',
        'price' => 12,
        'quantity' => 3,
        'stockable' => true,
        'active' => true,
        'category_id' => $catA->id, // Alpha
    ]);

    $response = $this->getJson('/api/admin/v1/products?sortBy=category_name&sortDir=asc');

    $response->assertSuccessful();
    $data = $response->json('data');

    expect($data)->toBeArray()->and(count($data))->toBeGreaterThanOrEqual(2);
    // First product should belong to Alpha category when sorted ascending by category name
    expect($data[0]['category_name'])->toBe('Alpha');
});

it('sorts products by category name descending', function (): void {
    $catA = Category::query()->create([
        'name' => 'Alpha',
        'slug' => 'alpha',
    ]);
    $catB = Category::query()->create([
        'name' => 'Beta',
        'slug' => 'beta',
    ]);

    Product::query()->create([
        'name' => 'Product 1',
        'slug' => 'product-1',
        'price' => 10,
        'quantity' => 5,
        'stockable' => true,
        'active' => true,
        'category_id' => $catB->id, // Beta
    ]);

    Product::query()->create([
        'name' => 'Product 2',
        'slug' => 'product-2',
        'price' => 12,
        'quantity' => 3,
        'stockable' => true,
        'active' => true,
        'category_id' => $catA->id, // Alpha
    ]);

    $response = $this->getJson('/api/admin/v1/products?sortBy=category_name&sortDir=desc');

    $response->assertSuccessful();
    $data = $response->json('data');

    expect($data)->toBeArray()->and(count($data))->toBeGreaterThanOrEqual(2);
    // First product should belong to Beta category when sorted descending by category name
    expect($data[0]['category_name'])->toBe('Beta');
});
