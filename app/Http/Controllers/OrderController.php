<?php

namespace App\Http\Controllers;

use App\Mail\OrderPlacedMail;
use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderItem; // Assuming you have an OrderItem model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Display a listing of orders for the authenticated user.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items')
            ->latest()
            ->get();

        return view('website.orderConfirmation', compact('orders'));
    }

    /**
     * Display the specified order details.
     * Uses Route Model Binding to ensure the order exists.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Contracts\View\View|RedirectResponse
     */
    public function show(Order $order)
    {
        // Policy or manual check to ensure the user owns this order
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('orders.index')->with('error', 'Unauthorized access to order details.');
        }

        // Eager load the order items and the product related to each item
        $order->load(['items.product']);

        return view('website.index', compact('order'));
    }

    /**
     * Handles the checkout process: converting cart items into a permanent order.
     * This method must be protected by a database transaction.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return RedirectResponse
     */
    // public function store(Request $request): RedirectResponse | JsonResponse
    // {
    //     // dd($request->all());
    //     // die();
    //     $userId = Auth::id();

    //     // 1. Validation for Checkout Data
    //     $request->validate([
    //         'shipping_address' => 'required|string|max:255',
    //         'billing_address'  => 'required|string|max:255',
    //         'payment_method'   => 'required|string|in:cash_on_delivery,credit_card,paypal', // Example methods
    //         // Add validation for potential shipping costs here if they were dynamic
    //     ]);

    //     // 2. Fetch User's Cart Data
    //     $cartItems = Cart::where('user_id', $userId)->with('product')->get();

    //     if ($cartItems->isEmpty()) {
    //         return redirect()->route('cart.index')->with('error', 'Your cart is empty. Please add items before checking out.');
    //     }

    //     // Use a database transaction to ensure all or nothing is committed (critical for checkout)
    //     try {
    //         DB::beginTransaction();

    //         // Calculate final totals and prepare OrderItems data
    //         $totalAmount = 0;
    //         $orderItemsData = [];

    //         foreach ($cartItems as $item) {
    //             $product = $item->product;

    //             // CRITICAL: Ensure product still exists and price is set before ordering
    //             if (!$product || $product->price <= 0) {
    //                 DB::rollBack();
    //                 return redirect()->route('cart.index')->with('error', "Product '{$product->product_name}' is unavailable or has an invalid price.");
    //             }

    //             $lockedPrice = $product->price; // Lock the price at the time of purchase
    //             $subtotal = $lockedPrice * $item->quantity;
    //             $totalAmount += $subtotal;

    //             $orderItemsData[] = new OrderItem([
    //                 'product_id' => $item->product_id,
    //                 'quantity'   => $item->quantity,
    //                 'price'      => $lockedPrice, // Price locked at order time
    //             ]);
    //         }

    //         // 3. Create the Order
    //         $order = Order::create([
    //             'user_id'          => $userId,
    //             'order_number'     => 'ORD-' . time() . rand(100, 999), // Simple unique order number
    //             'total_amount'     => $totalAmount,
    //             'payment_status'   => 'pending', // Default status after order creation
    //             'order_status'     => 'processing',
    //             'shipping_address' => $request->input('shipping_address'),
    //             'billing_address'  => $request->input('billing_address'),
    //             'payment_method'   => $request->input('payment_method'),
    //             'created_by'       => $userId,
    //         ]);

    //         // 4. Create the Order Items
    //         $order->items()->saveMany($orderItemsData);

    //         // 5. Clear the Cart (Only upon successful order creation)
    //         Cart::where('user_id', $userId)->delete();

    //         DB::commit();

    //         // 6. Success Response
    //         return redirect()->route('orders.show', $order)->with('success', "Order #{$order->order_number} successfully placed!");
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         // Log the error for developer debugging
    //         Log::error("Order creation failed for user {$userId}: " . $e->getMessage());
    //         // User-friendly error message
    //         return response()->json([
    //             'error' => 'Checkout failed. Please review your details and try again.',
    //             'exception' => $e->getMessage()
    //         ], 500);
    //         // return back()->withInput()->with('error', 'Checkout failed. Please review your details and try again.');
    //     }
    // }

    public function store(Request $request): JsonResponse  | RedirectResponse | View
    {
        $userId = Auth::id();

        try {
            // 1. Validation for Checkout Data
            // We validate the final consolidated address strings and total amount.
            $request->validate([
                'shipping_address' => 'required|string|max:1000', // Matches the hidden field name
                'billing_address'  => 'required|string|max:1000',  // Matches the hidden field name
                'payment_method' => 'required|string|in:cash_on_delivery,paypal',
                'total_amount'     => 'required|numeric|min:0',
            ]);

            // 2. Fetch User's Cart Data
            $cartItems = Cart::where('user_id', $userId)->with('product')->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty. Please add items before checking out.');
            }

            // Start Transaction
            DB::beginTransaction();

            $totalAmount = 0;
            $orderItemsData = [];

            foreach ($cartItems as $item) {
                $product = $item->product;

                if (!$product || $product->price <= 0) {
                    DB::rollBack();
                    return redirect()->route('cart.index')->with('error', "Product '{$product->product_name}' is unavailable or has an invalid price.");
                }

                $lockedPrice = $product->price;
                $subtotal = $lockedPrice * $item->quantity;
                $totalAmount += $subtotal;

                $orderItemsData[] = new OrderItem([
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'unit_price' => $lockedPrice,
                ]);
            }

            // 3. Create the Order
            $order = Order::create([
                'user_id'          => $userId,
                'order_number'     => 'ORD-' . time() . rand(100, 999),
                // Use the validated total amount from the request (safer)
                'total_amount'     => $request->input('total_amount'),
                'payment_status' => ($request->input('payment_method') === 'cash_on_delivery') ? 'pending' : 'paid',
                'order_status'     => 'processing',

                // 💡 CRITICAL FIX: Use the single, complete address string from the request
                'shipping_address' => $request->input('shipping_address'),
                'billing_address'  => $request->input('billing_address'),

                'payment_method'   => $request->input('payment_method'),
                'created_by'       => $userId,
            ]);

            // 4. Create the Order Items
            $order->items()->saveMany($orderItemsData);

            // 5. Clear the Cart
            Cart::where('user_id', $userId)->delete();

            DB::commit();

            // Send email
            Mail::to($order->user->email)->send(new OrderPlacedMail($order));
            // 6. Success Response
            return view('website.orderConfirmation', [
                'orderNumber' => $order->order_number,
                'success' => "Order #{$order->order_number} successfully placed!"
            ]);

            // redirect()->route('orders.show', $order)->with('success', "Order #{$order->order_number} successfully placed!");
        } catch (ValidationException $e) {
            // Log the individual validation errors
            Log::warning("Checkout validation failed: " . json_encode($e->errors()));
            return back()->withInput()->withErrors($e->errors())->with('error', 'Please correct the highlighted fields and try again.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Order creation failed for user {$userId}: " . $e->getMessage());
            // return back()->withInput()->with('error', 'Checkout failed due to a system error. Please try again.');
            return response()->json([
                'error' => 'Checkout failed due to a system error. Please try again.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }
}
