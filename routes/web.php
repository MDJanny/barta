<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;

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
    Route::get('/', [PostController::class, 'index']);
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::get('/profile/edit', [ProfileController::class, 'edit']);
    Route::put('/profile/edit', [ProfileController::class, 'update']);
    Route::get('/logout', [AuthController::class, 'logout']);

    // Route::get('/post/create', [PostController::class, 'create']);
    Route::post('/post/create', [PostController::class, 'store']);
    Route::get('/post/{uuid}', [PostController::class, 'show']);
    Route::get('/post/{uuid}/edit', [PostController::class, 'edit']);
    Route::put('/post/{uuid}/edit', [PostController::class, 'update']);
    Route::delete('/post/{uuid}/delete', [PostController::class, 'destroy']);
});

Route::group(['middleware' => ['guest']], function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'postlogin']);
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'postregister']);
});