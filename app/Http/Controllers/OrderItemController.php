<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class OrderItemController extends Controller
{
    /**
     * Display a listing of all Order Items (Typically restricted to Admin).
     *
     * @return View|RedirectResponse
     */
    public function index(): View|RedirectResponse
    {
        // NOTE: In a real application, you must use a Gate or Policy
        // here to restrict access to administrators only.

        if (!Auth::check()) {
            return redirect()->route('website.home')->with('error', 'Authentication required to view order items.');
        }

        // Fetch all order items, eager loading parent order and product details.
        $orderItems = OrderItem::with(['order', 'product.attachments'])
            ->latest()
            ->paginate(50); // Use pagination for large tables

        return view('website.index', compact('orderItems'));
    }

    /**
     * Display the specified Order Item.
     *
     * @param OrderItem $orderItem
     * @return View|RedirectResponse
     */
    public function show(OrderItem $orderItem): View|RedirectResponse
    {
        // Policy Check: Ensure the user is authorized to see this item.
        // Option 1: Must be an admin
        // Option 2: Must be the user who owns the parent order
        if ($orderItem->order->user_id !== Auth::id() && Auth::user()->designation !== 'admin') {
            return back()->with('error', 'You are not authorized to view this order item.');
        }

        // Eager load necessary relationships
        $orderItem->load(['order', 'product.attachments']);

        return view('website.index', compact('orderItem'));
    }

    // Since Order Items are created automatically during checkout
    // and should not be modified by the end-user,
    // we omit store(), update(), and destroy() methods here.
}
