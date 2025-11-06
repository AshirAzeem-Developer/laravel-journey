<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ========================
// 👇 FRONTEND ROUTES
// ========================

// ======================================== Home page (Ecommerce website) Routes =======================================
Route::get('/', function () {

    $data = [
        'categories' => Category::all(),
        'products' => Product::all(),
    ];

    return view('website.index', $data);
})->name('website.home');

// -------- Cart Routes --------
Route::middleware(['auth'])->prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index'); // GET /api/cart
    Route::post('/', [CartController::class, 'store'])->name('cart.store'); // POST /api/cart
    Route::put('/{cartId}', [CartController::class, 'update'])->name('cart.update'); // PUT /api/cart/{id}
    Route::delete('/{cartId}', [CartController::class, 'destroy'])->name('cart.destroy'); // DELETE /api/cart/{id}
});


// ---------------- >> Order Routes << ----------------
Route::middleware(['auth'])->prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orderConfirmation', [OrderController::class, 'index'])->name('orders.orderConfirmation');
});

Route::middleware(['auth'])->prefix('checkout')->group(function () {
    Route::get('/', function () {

        $userId = Auth::id();

        $cartItems = Cart::where('user_id', $userId)
            ->with('product') // Use the relationship defined in your Cart model
            ->get();

        // Calculate the subtotal (simple example)
        $subtotal = $cartItems->sum(function ($item) {
            // Assuming the Product model has a 'price' attribute
            return optional($item->product)->price * $item->quantity;
        });


        return view('website.checkout', [
            'cartItems' => $cartItems,
            'subtotal' => round($subtotal, 2)
        ]);
    })->name('checkout');
    Route::post('/', [OrderController::class, 'store'])->name('checkout.store');
});




// ========================
// 👇 ADMIN ROUTES
// ========================
Route::prefix('admin_dashboard')->group(function () {
    Route::get('/login', [DashboardController::class, 'show'])->name('adminLogin');

    // Route for Registering Admin User
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('adminRegister');



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
        Route::prefix('Products')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('products.index');
            Route::post('/', [ProductController::class, 'store'])->name('products.store');
            Route::put('/{product}', [ProductController::class, 'update'])->name('products.update');
            // Route::match(['put', 'patch'], '/{product}', [ProductController::class, 'update'])->name('products.update');
            Route::delete('/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        });
    });
});

require __DIR__ . '/auth.php'; // keep your existing auth routes here
