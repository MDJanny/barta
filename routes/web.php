<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', fn () => view('home'));
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::get('/profile/edit', [ProfileController::class, 'edit']);
    Route::put('/profile/edit', [ProfileController::class, 'update']);
    Route::get('/logout', [AuthController::class, 'logout']);
});

Route::group(['middleware' => ['guest']], function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'postlogin']);
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'postregister']);
});