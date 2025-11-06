<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product; // Assuming you have a Product model for validation
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    /**
     * Display a listing of the authenticated user's cart items.
     *
     * @param Request $request
     * @return JsonResponse
     */

    public function show(): View
    {
        return view('website.cart');
    }


    public function index(Request $request): JsonResponse | View
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


        return view('website.cart', [
            'cartItems' => $cartItems,
            'subtotal' => round($subtotal, 2)
        ]);
    }

    /**
     * Add a product to the cart or update the quantity if it already exists.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $userId = Auth::id();

        // 1. Authentication Check
        if (!$userId) {
            // Unauthenticated users should be redirected to log in
            return redirect()->route('website.home')->with('error', 'Please log in to add items to your cart.');
        }

        try {
            // 2. Validation
            $validatedData = $request->validate([
                'product_id' => [
                    'required',
                    'integer',
                    // Using your specified product table: 'tbl_products'
                    Rule::exists('tbl_products', 'id'),
                ],
                'quantity' => 'required|integer|min:1',
            ]);

            $quantity = $validatedData['quantity'];

            // 3. Check for existing item
            $cartItem = Cart::where('user_id', $userId)
                ->where('product_id', $validatedData['product_id'])
                ->first();

            // 4. Processing Logic
            if ($cartItem) {
                // Item exists: update quantity
                $oldQuantity = $cartItem->quantity;
                $newQuantity = $oldQuantity + $quantity;

                $cartItem->quantity = $newQuantity;
                $cartItem->save();

                $message = "Successfully added **{$quantity} more item(s)**. Total quantity is now **{$newQuantity}**.";
            } else {
                // Item is new: create a new cart entry
                $cartItem = Cart::create([
                    'user_id' => $userId,
                    'product_id' => $validatedData['product_id'],
                    'quantity' => $quantity,
                ]);

                // Eager load the product for a detailed message
                $cartItem->load('product');
                $productName = $cartItem->product->product_name ?? 'Product';

                $message = "Successfully added **{$productName}** to your cart.";
            }

            // 5. Success Redirect
            return back()->with('success', $message);
        } catch (ValidationException $e) {
            // Handle specific validation errors (e.g., product_id not found)

            // If the failure is due to Rule::exists (product_id is missing/invalid)
            if (isset($e->errors()['product_id']) && str_contains(implode('', $e->errors()['product_id']), 'selected product id is invalid')) {
                $errorMessage = 'The item you tried to add could not be found. It may be out of stock or discontinued.';
            } else {
                // General validation error (e.g., quantity is zero)
                $errorMessage = 'Please ensure the quantity is correct and try again.';
            }

            // Redirect back with validation error (flashing errors for the view partial)
            return back()->withInput()->withErrors($e->errors())->with('error', $errorMessage);
        } catch (Exception $e) {
            // Handle unexpected database or server errors
            Log::error("Cart store error for user {$userId}: " . $e->getMessage());

            return back()->with('error', 'An internal error occurred. Your item could not be added. Please try again.');
        }
    }

    /**
     * Update the quantity of a specific item in the cart.
     *
     * @param Request $request
     * @param int $cartId The primary key 'id' of the tbl_cart record
     * @return JsonResponse
     */
    public function update(Request $request, int $cartId): RedirectResponse
    {
        $userId = Auth::id();

        if (!$userId) {
            return redirect()->route('website.home')->with('error', 'Please log in to manage your cart.');
        }

        // Validate the action type
        $request->validate([
            'action' => ['required', Rule::in(['increment', 'decrement'])],
        ]);

        // Find the cart item, ensuring it belongs to the authenticated user
        $cartItem = Cart::where('id', $cartId)
            ->where('user_id', $userId)
            ->first();

        if (!$cartItem) {
            return back()->with('error', 'Cart item not found or unauthorized.');
        }

        $currentQuantity = $cartItem->quantity;
        $action = $request->input('action');
        $productName = $cartItem->product->product_name ?? 'Item';

        if ($action === 'increment') {
            $cartItem->quantity = $currentQuantity + 1;
            $message = "Quantity for **{$productName}** increased to {$cartItem->quantity}.";
        } elseif ($action === 'decrement') {
            // Prevent quantity from going below 1
            if ($currentQuantity > 1) {
                $cartItem->quantity = $currentQuantity - 1;
                $message = "Quantity for **{$productName}** decreased to {$cartItem->quantity}.";
            } else {
                return back()->with('error', "Quantity for {$productName} cannot be less than 1. Use the remove button to delete the item.");
            }
        } else {
            return back()->with('error', 'Invalid update action.');
        }

        try {
            $cartItem->save();
            return back()->with('success', $message);
        } catch (Exception $e) {
            Log::error("Cart update error: " . $e->getMessage());
            return back()->with('error', 'Failed to update quantity due to a server error.');
        }
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
