<?php

use Illuminate\Support\Facades\Route;
use Modules\Media\Http\Controllers\MediaController;

/*
|--------------------------------------------------------------------------
| Media API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for the Media module.
|
*/
Route::middleware('api')->prefix('api')->group(function (): void {
    Route::prefix('admin/v1/media')->name('admin.')->group(function (): void {
        Route::post('/upload', [MediaController::class, 'upload']);
        Route::delete('/{id}', [MediaController::class, 'delete']);
        Route::get('/', [MediaController::class, 'getMedia']);
        Route::delete('/temp/delete', [MediaController::class, 'deleteTemp']);
        Route::delete('/temp/delete-multiple', [MediaController::class, 'deleteMultipleTemp']);
    });
});
