<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/get-users', [ProfileController::class, 'getUsers'])->middleware(['auth', 'verified'])->name('getUsers');
Route::get('/users/{userId}', [UserController::class, 'show'])->middleware(['auth', 'verified'])->name('show');
Route::put('/users/{userId}', [UserController::class, 'update'])->middleware(['auth', 'verified'])->name('update');
Route::delete('/users/{userId}', [UserController::class, 'destroy'])->middleware(['auth', 'verified'])->name('destroy');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
