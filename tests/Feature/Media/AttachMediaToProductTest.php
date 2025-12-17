<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Product\Models\Category;
use Modules\Product\Models\Product;

it('attaches uploaded media to a product without tight coupling', function (): void {
    Storage::fake('public');

    // Prepare minimal related data
    $category = Category::query()->create([
        'name' => 'Default',
        'slug' => 'default',
    ]);

    $product = Product::query()->create([
        'name' => 'Test Product',
        'slug' => 'test-product',
        'price' => 10.00,
        'quantity' => 1,
        'stockable' => true,
        'active' => true,
        'category_id' => $category->getKey(),
    ]);

    $file = UploadedFile::fake()->image('photo.jpg', 600, 600);

    $response = $this->postJson('/api/admin/v1/media/upload', [
        'model_type' => Product::class,
        'model_id' => (string) $product->getKey(),
        'collection' => 'images',
        'file' => $file,
    ]);

    $response->assertSuccessful();

    // Refetch and assert media is attached through Spatie collections
    $product->refresh();
    expect($product->getMedia('images'))->toHaveCount(1);
    expect($product->getFirstMediaUrl('images'))->not->toBeEmpty();
});
