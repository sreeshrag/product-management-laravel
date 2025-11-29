<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('products')->group(function () {
    Route::get('/', [ProductApiController::class, 'index']);
    Route::post('/', [ProductApiController::class, 'store']);
    Route::get('/{id}', [ProductApiController::class, 'show']);
    Route::put('/{id}', [ProductApiController::class, 'update']);
    Route::post('/{id}', [ProductApiController::class, 'update']); 
    Route::delete('/{id}', [ProductApiController::class, 'destroy']);
});
