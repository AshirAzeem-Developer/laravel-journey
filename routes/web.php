<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WebsiteController;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ========================
// 👇 FRONTEND ROUTES
// ========================

// ======================================== Home page (Ecommerce website) Routes =======================================
Route::get('/login', function () {
    return redirect('/');
});
Route::get('/', [WebsiteController::class, 'home'])->name('website.home');

// -------- Cart Routes --------
Route::middleware(['auth'])->prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index'); // GET /api/cart
    Route::post('/', [CartController::class, 'store'])->name('cart.store'); // POST /api/cart
    Route::put('/{cartId}', [CartController::class, 'update'])->name('cart.update'); // PUT /api/cart/{id}
    Route::delete('/{cartId}', [CartController::class, 'destroy'])->name('cart.destroy'); // DELETE /api/cart/{id}
});

// ---------------- >> Checkout Routes << ----------------
Route::middleware(['auth'])->prefix('checkout')->group(function () {
    // 1. GET route to show the checkout form (Logic moved to WebsiteController)
    Route::get('/', [WebsiteController::class, 'checkout'])->name('checkout');

    // 2. POST route for Cash on Delivery submission
    Route::post('/', [OrderController::class, 'store'])->name('checkout.store');

    // 3. POST route for PayPal AJAX server-side final processing
    Route::post('/paypal/store', [OrderController::class, 'storePayPal'])->name('checkout.paypal.store');


    // Stripe routes
    Route::post('/stripe/intent', [OrderController::class, 'createStripeIntent'])
        ->name('checkout.stripe.intent');
    Route::post('/stripe/store', [OrderController::class, 'storeStripe'])
        ->name('checkout.stripe.store');
});


// ---------------- >> Order Routes << ----------------
Route::middleware(['auth'])->prefix('orders')->group(function () {
    // Route to view the user's order history
    Route::get('/', [OrderController::class, 'index'])->name('orders.index');

    // Alias for order history (kept for compatibility)
    Route::get('/orderConfirmation', [OrderController::class, 'index'])->name('orders.orderConfirmation');

    // 4. Dedicated confirmation route (Used for redirecting after successful order creation/payment)
    Route::get('/confirmation', [OrderController::class, 'showConfirmation'])->name('orders.confirmation');
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

        // --- Orders Routes ---
        Route::prefix('Orders')->group(function () {
            Route::get('/', [DashboardController::class, 'getOrders'])->name('admin.getAllOrders');
            Route::get('/{order}', [DashboardController::class, 'getOrderDetails'])->name('admin.getAdminOrderDetails');
            Route::put('/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.updateOrderStatus');
        });

        // --- Categories Routes ---
        Route::prefix('Categories')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('admin.getAllCategories');
            Route::post('/', [CategoryController::class, 'store'])->name('admin.storeCategory');
            Route::put('/{category}', [CategoryController::class, 'update'])->name('admin.updateCategory');
            Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('admin.deleteCategory');
        });

        // --- Reports Routes ---
        Route::prefix('reports')->group(function () {
            Route::get('/revenue-by-month', [ReportController::class, 'revenueByMonth'])->name('reports.revenueByMonth');
            Route::get('/revenue-by-year', [ReportController::class, 'revenueByYear'])->name('reports.revenueByYear');
            Route::get('/revenue-by-category', [ReportController::class, 'revenueByCategory'])->name('reports.revenueByCategory');
            Route::get('/revenue-by-category-year', [ReportController::class, 'revenueByCategoryYear'])->name('reports.revenueByCategoryYear');
        });
    });
});

require __DIR__ . '/auth.php'; // keep your existing auth routes here
