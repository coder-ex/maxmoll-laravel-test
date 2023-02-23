<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('users')->group(function () {
    Route::post('/add', [UserController::class, 'create']);
    Route::get('/{id}', [UserController::class, 'read']);
    Route::put('/edit', [UserController::class, 'update']);
    Route::get('/remove/{id}', [UserController::class, 'delete']);
    Route::get('/', [UserController::class, 'getAll']);
});

Route::prefix('products')->group(function () {
    Route::post('/add', [ProductController::class, 'create']);
    Route::get('/{id}', [ProductController::class, 'read']);
    Route::put('/edit', [ProductController::class, 'update']);
    Route::get('/remove/{id}', [ProductController::class, 'delete']);
    Route::get('/', [ProductController::class, 'getAll']);
});

Route::prefix('orders')->group(function () {
    Route::post('/add', [OrderController::class, 'create']);
    Route::get('/{id}', [OrderController::class, 'read']);
    Route::put('/edit', [OrderController::class, 'update']);
    Route::get('/cancelled/{id}', [OrderController::class, 'cancelled']);
    Route::get('/recancelled/{id}', [OrderController::class, 'recancelled']);
    Route::get('/', [OrderController::class, 'getAll']);
});
