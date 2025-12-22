<?php

use Illuminate\Support\Facades\Route;
use Modules\Supplier\Http\Controllers\SupplierController;

// API routes for module: Supplier
Route::middleware('api')->prefix('api')->group(function () {
    Route::middleware('auth:sanctum')->group( function () {
        Route::prefix('admin/v1')->name('admin.')->group(function () {
            Route::get('suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
            Route::get('suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
            Route::apiResource('suppliers', SupplierController::class);
        });
    });

});
