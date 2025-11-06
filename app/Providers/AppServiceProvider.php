<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // Register a View Composer for your header.blade.php
        View::composer('website.layouts.header', function ($view) {
            // Ensure the user is logged in before querying the cart
            if (Auth::check()) {
                // Eager load the 'product' relationship to access details like name and price
                $cartItems = Cart::where('user_id', Auth::id())
                    ->with('product')
                    ->get();

                $cartCount = $cartItems->sum('quantity');

                // Calculate total price using the related Product model's price
                $cartTotal = $cartItems->sum(function ($item) {
                    // Ensure the product exists and has a price attribute
                    return optional($item->product)->price * $item->quantity;
                });
            } else {
                $cartItems = collect(); // Empty collection if not logged in
                $cartCount = 0;
                $cartTotal = 0;
            }

            // Pass the calculated data to the header view
            $view->with([
                'actualCartItems' => $cartItems,
                'actualCartCount' => $cartCount,
                'actualCartTotal' => $cartTotal,
            ]);
        });
        //
        Schema::defaultStringLength(191);
    }
}
