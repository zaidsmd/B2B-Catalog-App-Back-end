<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\Admin\CategoryController;
use Modules\Product\Http\Controllers\Admin\ProductController;
use Modules\Product\Http\Controllers\Public\ProductController as ProductControllerMobilev1;

// API routes for module: Product
Route::middleware('api')->prefix('api')->group(function (): void {

    Route::middleware('auth:sanctum')->group( function () {
        Route::middleware('api.mobile')->group(function (){
            Route::prefix('v1/')->name('v1.')->group(function (): void {
                Route::get('products', [ProductControllerMobilev1::class, 'index'])->name('products.index');
            });
        });
        Route::prefix('admin/v1')->name('admin.')->group(function (): void {
            Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
            Route::apiResource('products', ProductController::class);
            Route::apiResource('categories', CategoryController::class);
            Route::get('product-categories', [CategoryController::class, 'options']);
            Route::get('products-media/{product}/{collection?}', [ProductController::class, 'getMedia']);
        });
    });
});
