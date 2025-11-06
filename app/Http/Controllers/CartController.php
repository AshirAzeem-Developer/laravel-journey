<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product; // Assuming you have a Product model for validation
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class CartController extends Controller
{
    /**
     * Display a listing of the authenticated user's cart items.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // Get the ID of the currently authenticated user
        $userId = Auth::id();

        if (!$userId) {
            // Handle cases where the user is not logged in (e.g., return 401 Unauthorized)
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Fetch all cart items for the user, eager-loading the related product details
        $cartItems = Cart::where('user_id', $userId)
            ->with('product') // Use the relationship defined in your Cart model
            ->get();

        // Calculate the subtotal (simple example)
        $subtotal = $cartItems->sum(function ($item) {
            // Assuming the Product model has a 'price' attribute
            return optional($item->product)->price * $item->quantity;
        });

        return response()->json([
            'data' => $cartItems,
            'subtotal' => round($subtotal, 2)
        ]);
    }

    /**
     * Add a product to the cart or update the quantity if it already exists.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $userId = Auth::id();

        // Use Form Requests for more complex validation in a real application
        $validatedData = $request->validate([
            'product_id' => [
                'required',
                'integer',
                // Ensure the product exists in the 'products' table (or whatever your product table is named)
                Rule::exists('tbl_products', 'id'),
            ],
            'quantity' => 'required|integer|min:1',
        ]);

        // Try to find the cart item for this user and product
        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $validatedData['product_id'])
            ->first();

        if ($cartItem) {
            // If the item exists, update its quantity (e.g., add to existing quantity)
            $newQuantity = $cartItem->quantity + $validatedData['quantity'];
            $cartItem->quantity = $newQuantity;
            $cartItem->save();

            return redirect()->back()->with('success', 'Cart quantity updated successfully.');
        } else {
            // If the item does not exist, create a new cart entry
            $cartItem = Cart::create([
                'user_id' => $userId,
                'product_id' => $validatedData['product_id'],
                'quantity' => $validatedData['quantity'],
            ]);

            return redirect()->back()->with([
                'success' => 'Product added to cart successfully.',
                'cart_item' => $cartItem->load('product')
            ]);

            // return response()->json([
            //     'message' => 'Product added to cart successfully.',
            //     'cart_item' => $cartItem->load('product')
            // ], 201);
        }
    }

    /**
     * Update the quantity of a specific item in the cart.
     *
     * @param Request $request
     * @param int $cartId The primary key 'id' of the tbl_cart record
     * @return JsonResponse
     */
    public function update(Request $request, int $cartId): JsonResponse
    {
        $validatedData = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Find the cart item, ensuring it belongs to the authenticated user
        $cartItem = Cart::where('id', $cartId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Cart item not found or unauthorized.'], 404);
        }

        // Update the quantity
        $cartItem->quantity = $validatedData['quantity'];
        $cartItem->save();

        return response()->json([
            'message' => 'Cart item quantity updated.',
            'cart_item' => $cartItem->load('product')
        ]);
    }

    /**
     * Remove a specific item from the cart.
     *
     * @param int $cartId The primary key 'id' of the tbl_cart record
     * @return JsonResponse
     */
    public function destroy(int $cartId): RedirectResponse
    {
        $userId = Auth::id();

        if (!$userId) {
            return redirect()->route('website.home')->with('error', 'Please log in to manage your cart.');
        }

        // Find the cart item, ensuring it belongs to the authenticated user
        $deleted = Cart::where('id', $cartId)
            ->where('user_id', $userId)
            ->delete();

        if ($deleted) {
            // Redirect back to the page with a success message
            return back()->with('success', 'Item successfully removed from cart.');
        }

        // Redirect back with an error if the item wasn't found or wasn't authorized
        return back()->with('error', 'Cart item not found or unauthorized to remove.');
    }
}
