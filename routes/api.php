<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ShopsController;
use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Ping route.
Route::get('/test', [TestController::class, 'index']);

Route::middleware('auth')->group(function () {
    Route::apiResource('categories', CategoriesController::class);
    Route::get('categories/dropdown/data', [CategoriesController::class, 'dropdown']);

    Route::apiResource('brands', BrandsController::class);
    Route::get('brands/dropdown/data', [BrandsController::class, 'dropdown']);

    Route::get('shops/dropdown/data', [ShopsController::class, 'dropdown']);

    // Route::apiResource('products', ProductsController::class);
});
