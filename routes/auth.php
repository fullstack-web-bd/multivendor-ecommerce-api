<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VendorRegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/vendor-register', [VendorRegisterController::class, 'register']);
Route::post('/password/code', [PasswordResetController::class, 'sendResetCode']);
Route::post('/password/reset', [PasswordResetController::class, 'resetPassword']);
Route::post('/logout', [LogoutController::class, 'logout'])->middleware('auth:api');
Route::get('/me', [LoginController::class, 'getLoggedInUser'])->middleware('auth:api');