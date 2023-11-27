<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
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

Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/search', [ProfileController::class, 'search'])->name('profile.search');
    Route::get('/profile/{username?}', [ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/', [PostController::class, 'index']);
    Route::post('/post', [PostController::class, 'store']);
    Route::get('/post/{uuid}', [PostController::class, 'show']);
    Route::get('/post/{uuid}/edit', [PostController::class, 'edit']);
    Route::put('/post/{uuid}', [PostController::class, 'update']);
    Route::delete('/post/{uuid}', [PostController::class, 'destroy']);

    Route::post('/post/{postUuid}/comment', [CommentController::class, 'store']);
    Route::get('/post/{postUuid}/comment/{commentUuid}/edit', [CommentController::class, 'edit']);
    Route::put('/post/{postUuid}/comment/{commentUuid}', [CommentController::class, 'update']);
    Route::delete('/post/{postUuid}/comment/{commentUuid}', [CommentController::class, 'destroy']);
});

require __DIR__ . '/auth.php';