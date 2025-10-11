<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders.
     */
    public function index()
    {
        $orders = Auth::user()->orders()
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:100',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'pickup_notes' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:cash,online',
            // Credit card fields (required only for online payments, but not stored)
            'card_number' => 'required_if:payment_method,online|nullable|string',
            'card_expiry' => 'required_if:payment_method,online|nullable|string',
            'card_cvv' => 'required_if:payment_method,online|nullable|string',
            'cardholder_name' => 'required_if:payment_method,online|nullable|string|max:255',
            'same_as_customer' => 'nullable|boolean',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        
        // Check if product is available for ordering
        if (!$product->isAvailable()) {
            return back()->withErrors(['product_id' => 'This product is currently not available for ordering.']);
        }
        
        // Check stock availability if tracking stock
        if ($product->track_stock && $product->stock_quantity < $validated['quantity']) {
            return back()->withErrors(['quantity' => "Only {$product->stock_quantity} items available in stock."]);
        }
        
        $unitPrice = $product->price;
        $totalPrice = $unitPrice * $validated['quantity'];
        
        // Reduce stock if tracking
        if ($product->track_stock) {
            if (!$product->reduceStock($validated['quantity'])) {
                return back()->withErrors(['quantity' => 'Insufficient stock available.']);
            }
        }

        // Set payment status based on payment method
        $paymentStatus = $validated['payment_method'] === 'cash' 
            ? Order::PAYMENT_STATUS_PENDING  // Cash will be paid on pickup
            : Order::PAYMENT_STATUS_PAID; // Online payments are marked as paid immediately (simulated payment)

        $order = Order::create([
            'user_id' => Auth::id(),
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'unit_price' => $unitPrice,
            'total_price' => $totalPrice,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'pickup_notes' => $validated['pickup_notes'],
            'notes' => $validated['notes'],
            'status' => Order::STATUS_PENDING,
            'payment_method' => $validated['payment_method'],
            'payment_status' => $paymentStatus,
        ]);

        // Set success message based on payment method
        $successMessage = $validated['payment_method'] === 'online' 
            ? 'Your order has been placed and payment processed successfully! We\'ll prepare your order for pickup.'
            : 'Your order has been placed successfully! Payment will be collected at pickup.';

        return redirect()->route('orders.show', $order)
            ->with('success', $successMessage);
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        // Ensure user can only view their own orders (or admin can view all)
        if (!Auth::user()->isAdmin() && $order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load('product', 'user');
        
        return view('orders.show', compact('order'));
    }

    /**
     * Cancel an order (if allowed).
     */
    public function cancel(Order $order)
    {
        // Ensure user can only cancel their own orders
        if ($order->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access to this order.');
        }

        if (!$order->canBeCancelled()) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        $order->update(['status' => Order::STATUS_CANCELLED]);

        return back()->with('success', 'Order has been cancelled successfully.');
    }
}
