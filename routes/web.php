<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

// ========================
// 👇 FRONTEND ROUTES
// ========================

// Home page (Ecommerce website)
Route::get('/', function () {
    return view('website.index'); // this should be your frontend home
})->name('website.home');



// ========================
// 👇 ADMIN ROUTES
// ========================
Route::prefix('admin_dashboard')->group(function () {
    Route::get('/login', [DashboardController::class, 'show'])->name('adminLogin');
    // Protected Admin Routes
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('adminDashboard');

        Route::get('/{section}', [DashboardController::class, 'showSection'])
            ->name('dashboard.section')
            ->where('section', 'users|products|categories|orders|sessions|jobs');

        // --- Profile Routes ---
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // --- Users Routes ---
        Route::prefix('users')->group(function () {
            Route::post('/', [UsersController::class, 'store'])->name('users.store');
            Route::get('/{id}', [UsersController::class, 'show'])->name('users.show');
            Route::match(['put', 'patch'], '/{id}', [UsersController::class, 'update'])->name('users.update');
            Route::delete('/{id}', [UsersController::class, 'destroy'])->name('users.destroy');
        });

        // --- Products Routes ---
        Route::prefix('/Products')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('products.index');
            Route::post('/', [ProductController::class, 'store'])->name('products.store');
            Route::get('/{product}', [ProductController::class, 'show'])->name('products.show');
            Route::match(['put', 'patch'], '/{product}', [ProductController::class, 'update'])->name('products.update');
            Route::delete('/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        });
    });
});

require __DIR__ . '/auth.php'; // keep your existing auth routes here
