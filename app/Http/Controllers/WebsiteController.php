<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class WebsiteController extends Controller
{

    public function home()
    {
        $data['categories'] = Category::with(['products' => function ($q) {
            $q->where('isActive', 1); // sirf active products
        }])->get();

        return view('website.index', compact('data'));
    }
    public function checkout()
    {
        $userId = Auth::id();

        // Fetch the cart items and eager-load the product data
        $cartItems = Cart::where('user_id', $userId)
            ->with('product')
            ->get();

        // Calculate the subtotal
        $subtotal = $cartItems->sum(function ($item) {
            // Use optional() for safety in case the product relation is null
            return optional($item->product)->price * $item->quantity;
        });

        return view('website.checkout', [
            'cartItems' => $cartItems,
            'subtotal' => round($subtotal, 2)
        ]);
    }
}
