<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BrandController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('v1/auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});
Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::apiResource('brands', BrandController::class);
    Route::get('/brands/{brand}/products', [BrandController::class, 'products']);

    Route::apiResource('categories', CategoryController::class);
    Route::get('/categories/{category}/children', [CategoryController::class, 'children']);
    Route::get('/categories/{category}/parent', [CategoryController::class, 'parent']);
    Route::get('/categories/{category}/products', [CategoryController::class, 'products']);

    Route::apiResource('products', ProductController::class);

    Route::post('/orders', [OrderController::class, 'store']);
    Route::post('/payment/verify', [OrderController::class, 'verify']);
});
