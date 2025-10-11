<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    /**
     * Show the checkout page for a product
     */
    public function show(Product $product, Request $request)
    {
        // Check if product is available for ordering
        if (!$product->isAvailable()) {
            return redirect()->route('product.detail', $product)
                ->withErrors(['product' => 'This product is currently not available for ordering.']);
        }

        // Get quantity from request (default to 1)
        $quantity = max(1, min(100, (int) $request->get('quantity', 1)));
        
        // Check stock availability if tracking stock
        if ($product->track_stock && $product->stock_quantity < $quantity) {
            return redirect()->route('product.detail', $product)
                ->withErrors(['quantity' => "Only {$product->stock_quantity} items available in stock."]);
        }

        $unitPrice = $product->price;
        $totalPrice = $unitPrice * $quantity;

        return view('checkout.show', compact('product', 'quantity', 'unitPrice', 'totalPrice'));
    }

    /**
     * Process the checkout and create order
     */
    public function process(Request $request)
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
     * Show the checkout page for cart items
     */
    public function showCart(Request $request)
    {
        $cartItems = \App\Models\Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty. Add some items before checking out.');
        }

        // Check availability for all cart items
        foreach ($cartItems as $item) {
            if (!$item->product->isAvailable()) {
                return redirect()->route('cart.index')
                    ->withErrors(['product' => "Product '{$item->product->name}' is no longer available. Please remove it from your cart."]);
            }
            
            // Check stock if tracking
            if ($item->product->track_stock && $item->product->stock_quantity < $item->quantity) {
                return redirect()->route('cart.index')
                    ->withErrors(['stock' => "Only {$item->product->stock_quantity} of '{$item->product->name}' available in stock."]);
            }
        }

        $totalPrice = $cartItems->sum('total_price');
        $totalItems = $cartItems->sum('quantity');

        return view('checkout.cart', compact('cartItems', 'totalPrice', 'totalItems'));
    }

    /**
     * Process the cart checkout and create order
     */
    public function processCart(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'payment_method' => 'required|in:cash,online',
            'card_number' => 'required_if:payment_method,online|nullable|string|min:13|max:19',
            'card_expiry' => 'required_if:payment_method,online|nullable|string|size:5',
            'card_cvv' => 'required_if:payment_method,online|nullable|string|min:3|max:4',
            'cardholder_name' => 'required_if:payment_method,online|nullable|string|max:255',
        ]);

        $cartItems = \App\Models\Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        // Re-check availability and stock
        foreach ($cartItems as $item) {
            if (!$item->product->isAvailable()) {
                return redirect()->route('cart.index')
                    ->withErrors(['product' => "Product '{$item->product->name}' is no longer available."]);
            }
            
            if ($item->product->track_stock && $item->product->stock_quantity < $item->quantity) {
                return redirect()->route('cart.index')
                    ->withErrors(['stock' => "Insufficient stock for '{$item->product->name}'."]);
            }
        }

        $totalPrice = $cartItems->sum('total_price');
        $paymentStatus = $validated['payment_method'] === 'online' ? Order::PAYMENT_PAID : Order::PAYMENT_PENDING;

        // Create order with cart items
        $orderData = [
            'user_id' => Auth::id(),
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'total_price' => $totalPrice,
            'status' => Order::STATUS_PENDING,
            'payment_method' => $validated['payment_method'],
            'payment_status' => $paymentStatus,
        ];

        // Add cart items details to order
        $orderData['items'] = $cartItems->map(function($item) {
            return [
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'quantity' => $item->quantity,
                'unit_price' => $item->product->price,
                'total_price' => $item->total_price
            ];
        })->toArray();

        $order = Order::create($orderData);

        // Update stock quantities if tracking
        foreach ($cartItems as $item) {
            if ($item->product->track_stock) {
                $item->product->decrement('stock_quantity', $item->quantity);
            }
        }

        // Clear the cart after successful order
        \App\Models\Cart::where('user_id', Auth::id())->delete();

        $successMessage = $validated['payment_method'] === 'online' 
            ? 'Your order has been placed and payment processed successfully! We\'ll prepare your order for pickup.'
            : 'Your order has been placed successfully! Payment will be collected at pickup.';

        return redirect()->route('orders.show', $order)
            ->with('success', $successMessage);
    }
}