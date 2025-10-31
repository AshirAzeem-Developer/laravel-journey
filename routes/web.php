<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});





Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard/{section}', [DashboardController::class, 'showSection'])
    ->name('dashboard.section')
    // Optionally constrain the valid values for 'section' for security/robustness
    ->where('section', 'users|products|categories|orders|sessions|jobs');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// =================== >>  Users Routes << ==============================

Route::prefix('dashboard/users')->middleware('auth')->group(function () {
    Route::post('/', [UsersController::class, 'store'])->name('users.store');
    Route::get('/{id}', [UsersController::class, 'show'])->name('users.show');
    Route::match(['put', 'patch'], '/{id}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('/{id}', [UsersController::class, 'destroy'])->name('users.destroy');
});


// ==================== >>> Products Routes <<< ============================


Route::prefix('products')->middleware('auth')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::post('/', [ProductController::class, 'store'])->name('products.store');
    Route::get('/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::match(['put', 'patch'], '/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});


require __DIR__ . '/auth.php';
