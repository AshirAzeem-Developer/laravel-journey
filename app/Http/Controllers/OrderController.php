<?php

namespace App\Http\Controllers;

use App\Mail\OrderPlacedMail;
use App\Mail\OrderStatusMail;
use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderItem;
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
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class OrderController extends Controller
{



    public function index(): View
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items')
            ->latest()
            ->get();

        return view('website.orderConfirmation', compact('orders'));
    }

    public function show(Order $order): RedirectResponse|View
    {
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('orders.index')->with('error', 'Unauthorized access to order details.');
        }

        $order->load(['items.product']);
        return view('website.index', compact('order'));
    }

    public function store(Request $request): RedirectResponse | View
    {
        try {
            $request->validate([
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'address1' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'postcode' => 'required|string|max:50',
                'phone_number' => 'required|string|max:50',
                'country' => 'required|string|max:255',
                'shipping_address' => 'required|string|max:1000',
                'billing_address' => 'required|string|max:1000',
                'total_amount' => 'required|numeric|min:0',
                'shipping_cost_amount' => 'required|numeric|min:0',
                'order_notes' => 'nullable|string|max:500'
            ]);
        } catch (ValidationException $e) {
            return back()->withInput()->withErrors($e->errors())
                ->with('order_error', 'Please correct the highlighted fields and try again.');
        }

        $request->merge(['payment_method' => 'cash_on_delivery']);
        return $this->processOrderCreation($request, 'pending');
    }


    public function storePayPal(Request $request): JsonResponse
    {
        try {
            // Validate all required fields
            $validated = $request->validate([
                'paypal_order_id' => 'required|string|max:255',
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'address1' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'postcode' => 'required|string|max:50',
                'phone_number' => 'required|string|max:50',
                'country' => 'required|string|max:255',
                'shipping_address' => 'required|string|max:1000',
                'billing_address' => 'required|string|max:1000',
                'total_amount' => 'required|numeric|min:0',
                'shipping_cost_amount' => 'required|numeric|min:0',
                'order_notes' => 'nullable|string|max:500'
            ]);

            Log::info('PayPal Order Request Received', [
                'user_id' => Auth::id(),
                'paypal_order_id' => $validated['paypal_order_id'],
                'total_amount' => $validated['total_amount']
            ]);
        } catch (ValidationException $e) {
            Log::warning('PayPal validation failed', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'error' => 'Validation Failed',
                'messages' => $e->errors()
            ], 422);
        }

        $request->merge(['payment_method' => 'paypal']);

        try {
            $result = $this->processOrderCreation($request, 'paid');

            // Handle cart empty scenario
            if ($result instanceof RedirectResponse) {
                return response()->json([
                    'success' => false,
                    'error' => 'Your cart is empty. Please add items before checking out.'
                ], 400);
            }

            // Extract order number from the result
            $orderNumber = null;
            if ($result instanceof JsonResponse) {
                $data = $result->getData(true);
                $orderNumber = $data['orderNumber'] ?? null;
            }

            if (!$orderNumber) {
                throw new Exception('Order number not generated');
            }

            Log::info('PayPal Order Created Successfully', [
                'order_number' => $orderNumber,
                'user_id' => Auth::id()
            ]);

            // Return success with redirect URL
            return response()->json([
                'success' => true,
                'orderNumber' => $orderNumber,
                'redirect_url' => route('orders.confirmation', [
                    'orderNumber' => $orderNumber,
                    'success' => 'Order successfully placed!'
                ])
            ], 200);
        } catch (Exception $e) {
            Log::error('PayPal Order Creation Failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Checkout failed due to a system error. Please try again.',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }


    private function processOrderCreation(Request $request, string $paymentStatus): View | RedirectResponse | JsonResponse
    {
        $userId = Auth::id();

        try {
            // Fetch cart items
            $cartItems = Cart::where('user_id', $userId)->with('product')->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')
                    ->with('error', 'Your cart is empty. Please add items before checking out.');
            }

            DB::beginTransaction();

            $totalAmount = 0;
            $orderItemsData = [];

            // Process cart items
            foreach ($cartItems as $item) {
                $product = $item->product;

                if (!$product || $product->price <= 0) {
                    DB::rollBack();
                    throw new Exception("Product '{$product->product_name}' is unavailable or has an invalid price.");
                }

                $lockedPrice = $product->price;
                $totalAmount += ($lockedPrice * $item->quantity);

                $orderItemsData[] = new OrderItem([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $lockedPrice,
                ]);
            }

            // Verify total amount
            $requestedTotal = (float) $request->input('total_amount');
            $shippingCost = (float) $request->input('shipping_cost_amount');
            $calculatedTotal = $totalAmount + $shippingCost;

            if (abs($requestedTotal - $calculatedTotal) > 0.01) {
                DB::rollBack();
                Log::error('Total amount mismatch', [
                    'user_id' => $userId,
                    'client_total' => $requestedTotal,
                    'server_total' => $calculatedTotal
                ]);
                throw new Exception('Order total mismatch. Please refresh and try again.');
            }

            // In processOrderCreation() method, update order creation:
            $order = Order::create([
                'user_id' => $userId,
                'order_number' => 'ORD-' . time() . rand(100, 999),
                'total_amount' => $calculatedTotal,
                'payment_status' => $paymentStatus,
                'order_status' => 'processing',
                'shipping_address' => $request->input('shipping_address'),
                'billing_address' => $request->input('billing_address'),
                'payment_method' => $request->input('payment_method'),
                'transaction_id' => $request->input('paypal_order_id') ?? $request->input('payment_intent_id') ?? null,
                'created_by' => $userId,
            ]);

            // Create order items
            $order->items()->saveMany($orderItemsData);

            // Clear cart
            Cart::where('user_id', $userId)->delete();

            DB::commit();

            // Send confirmation email
            try {
                Mail::to(Auth::user()->email)->send(new OrderPlacedMail($order));
            } catch (Exception $e) {
                Log::warning('Failed to send order confirmation email', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
            }

            // Return appropriate response based on payment method
            if ($request->input('payment_method') === 'cash_on_delivery') {
                return view('website.orderConfirmation', [
                    'orderNumber' => $order->order_number,
                    'success' => "Order #{$order->order_number} successfully placed!"
                ]);
            }
            // Update return logic to include Stripe
            if (in_array($request->input('payment_method'), ['paypal', 'stripe'])) {
                return response()->json(['orderNumber' => $order->order_number], 200);
            } else {
                return response()->json(['error' => 'Unsupported payment method'], 400);
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->input('payment_method') === 'cash_on_delivery') {
                return back()->withInput()
                    ->with('order_error', 'Checkout failed due to a system error. Please try again.');
            }

            throw $e;
        }
    }

    public function showConfirmation(Request $request): View
    {
        $orderNumber = $request->query('orderNumber');
        $successMessage = $request->query('success', 'Order successfully placed!');

        return view('website.orderConfirmation', [
            'orderNumber' => $orderNumber,
            'success' => $successMessage
        ]);
    }

    /**
     * Create Stripe Payment Intent (Server creates the payment)
     */
    public function createStripeIntent(Request $request): JsonResponse
    {
        try {
            // Validate minimal required data
            $request->validate([
                'total_amount' => 'required|numeric|min:0',
                'shipping_cost_amount' => 'required|numeric|min:0',
            ]);

            $userId = Auth::id();

            // Verify cart and calculate server-side total
            $cartItems = Cart::where('user_id', $userId)->with('product')->get();

            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Your cart is empty.'
                ], 400);
            }

            // Calculate server total
            $totalAmount = 0;
            foreach ($cartItems as $item) {
                $product = $item->product;
                if (!$product || $product->price <= 0) {
                    return response()->json([
                        'success' => false,
                        'error' => "Product unavailable."
                    ], 400);
                }
                $totalAmount += ($product->price * $item->quantity);
            }

            $shippingCost = (float) $request->input('shipping_cost_amount');
            $calculatedTotal = $totalAmount + $shippingCost;
            $requestedTotal = (float) $request->input('total_amount');

            // Verify totals match
            if (abs($requestedTotal - $calculatedTotal) > 0.01) {
                return response()->json([
                    'success' => false,
                    'error' => 'Total mismatch. Please refresh.'
                ], 400);
            }

            // Create Stripe Payment Intent
            Stripe::setApiKey(config('services.stripe.secret'));

            $paymentIntent = PaymentIntent::create([
                'amount' => round($calculatedTotal * 100), // Convert to cents
                'currency' => 'usd',
                'metadata' => [
                    'user_id' => $userId,
                    'order_type' => 'ecommerce'
                ],
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            Log::info('Stripe Payment Intent Created', [
                'user_id' => $userId,
                'intent_id' => $paymentIntent->id,
                'amount' => $calculatedTotal
            ]);

            return response()->json([
                'success' => true,
                'clientSecret' => $paymentIntent->client_secret,
                'intentId' => $paymentIntent->id
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'messages' => $e->errors()
            ], 422);
        } catch (ApiErrorException $e) {
            Log::error('Stripe API Error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'Payment service error. Please try again.'
            ], 500);
        } catch (Exception $e) {
            Log::error('Stripe Intent Creation Failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'Failed to initialize payment.'
            ], 500);
        }
    }


    /**
     * Handle Stripe payment confirmation and order creation
     */
    public function storeStripe(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'payment_intent_id' => 'required|string|max:255',
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'address1' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'postcode' => 'required|string|max:50',
                'phone_number' => 'required|string|max:50',
                'country' => 'required|string|max:255',
                'shipping_address' => 'required|string|max:1000',
                'billing_address' => 'required|string|max:1000',
                'total_amount' => 'required|numeric|min:0',
                'shipping_cost_amount' => 'required|numeric|min:0',
                'order_notes' => 'nullable|string|max:500'
            ]);

            // Verify payment with Stripe
            Stripe::setApiKey(config('services.stripe.secret'));
            $paymentIntent = PaymentIntent::retrieve($validated['payment_intent_id']);

            if ($paymentIntent->status !== 'succeeded') {
                return response()->json([
                    'success' => false,
                    'error' => 'Payment not completed. Status: ' . $paymentIntent->status
                ], 400);
            }

            Log::info('Stripe Payment Verified', [
                'user_id' => Auth::id(),
                'intent_id' => $paymentIntent->id,
                'status' => $paymentIntent->status
            ]);

            $request->merge(['payment_method' => 'stripe']);

            $result = $this->processOrderCreation($request, 'paid');

            if ($result instanceof RedirectResponse) {
                return response()->json([
                    'success' => false,
                    'error' => 'Cart is empty.'
                ], 400);
            }

            $orderNumber = null;
            if ($result instanceof JsonResponse) {
                $data = $result->getData(true);
                $orderNumber = $data['orderNumber'] ?? null;
            }

            if (!$orderNumber) {
                throw new Exception('Order number not generated');
            }

            Log::info('Stripe Order Created Successfully', [
                'order_number' => $orderNumber,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'orderNumber' => $orderNumber,
                'redirect_url' => route('orders.confirmation', [
                    'orderNumber' => $orderNumber,
                    'success' => 'Order successfully placed!'
                ])
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Validation Failed',
                'messages' => $e->errors()
            ], 422);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe Verification Error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'Payment verification failed.'
            ], 500);
        } catch (Exception $e) {
            Log::error('Stripe Order Creation Failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'error' => 'Order creation failed.'
            ], 500);
        }
    }

    public function updateStatus(Request $request, Order $order)
    {
        // 1. Validation (Place this at the start for immediate error handling)
        $request->validate([
            // Note: The 'in' rule effectively validates the status values.
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        // 2. Get new status and old status
        $newStatus = $request->input('status');
        $oldStatus = $order->order_status;

        // A. Check if the status has actually changed (Improved Flow)
        if ($oldStatus === $newStatus) {
            return redirect()->back()->with('info', "Order #{$order->order_number} status is already set to {$newStatus}.");
        }

        // B. Define Allowed Status Flow
        $allowedTransitions = [
            'pending'    => ['processing', 'cancelled'],
            'processing' => ['shipped', 'cancelled'],
            'shipped'    => ['delivered', 'cancelled'],
            // Delivered and Cancelled are final states, allowing no further transitions.
            'delivered'  => [],
            'cancelled'  => [],
        ];

        if (!isset($allowedTransitions[$oldStatus]) || !in_array($newStatus, $allowedTransitions[$oldStatus])) {
            return redirect()->back()->with(
                'error',
                "Order #{$order->order_number} status cannot transition from '{$oldStatus}' to '{$newStatus}'."
            );
        }

        // 3. Update the order status in the database
        $order->order_status = $newStatus;
        $order->save();

        // 4. Determine User and Send the status update email (Using Eloquent Relationship)
        // Assuming a 'user' relationship exists on the Order model: $order->belongsTo(User::class);
        $user = $order->user;
        $emailMessage = ''; // Initialize the message part

        // Ensure the user exists and has an email before attempting to send
        if ($user && $user->email) {
            try {
                // Pass the updated order instance to the Mailable
                Mail::to($user->email)->send(new OrderStatusMail($order));
                $emailMessage = ' and a status update email was sent.';
            } catch (Exception $e) {
                Log::error('Failed to send order status update email', [
                    'order_id' => $order->id,
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
                $emailMessage = ' but the status update email failed to send.';
            }
        } else {
            // This covers cases where $user is null OR $user->email is empty/null
            $emailMessage = ' but the user has no associated email or account.';
        }

        // 5. Success redirect
        return redirect()->back()->with(
            'success',
            "Order #{$order->order_number} status successfully updated to **" . ucfirst($newStatus) . "**" . $emailMessage
        );
    }
}
