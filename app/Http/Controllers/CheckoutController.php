<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;
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
            'payment_method' => 'required|in:cash,online,bank_transfer',
            // Credit card fields (required only for online payments, but not stored)
            'card_number' => 'required_if:payment_method,online|nullable|string',
            'card_expiry' => 'required_if:payment_method,online|nullable|string',
            'card_cvv' => 'required_if:payment_method,online|nullable|string',
            'cardholder_name' => 'required_if:payment_method,online|nullable|string|max:255',
            // Bank transfer fields (required only for bank transfer payments)
            'bank_name' => 'required_if:payment_method,bank_transfer|nullable|string|max:100',
            'bank_reference' => 'required_if:payment_method,bank_transfer|nullable|string|max:100',
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
        $paymentStatus = match($validated['payment_method']) {
            'cash' => Order::PAYMENT_STATUS_PENDING,        // Cash paid on pickup
            'bank_transfer' => Order::PAYMENT_STATUS_PENDING, // Requires manual verification
            'online' => Order::PAYMENT_STATUS_PENDING,      // Requires actual payment processing
            default => Order::PAYMENT_STATUS_PENDING
        };

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
            'payment_reference' => $validated['payment_method'] === 'bank_transfer' && isset($validated['bank_reference']) 
                ? $validated['bank_name'] . ' - ' . $validated['bank_reference'] 
                : null,
        ]);

        // Send order confirmation email
        try {
            Mail::to($order->customer_email)->send(new OrderConfirmationMail($order));
        } catch (\Exception $e) {
            // Log email error but don't fail the order
            \Log::error('Failed to send order confirmation email: ' . $e->getMessage());
        }

        // Set success message based on payment method
        $successMessage = match($validated['payment_method']) {
            'online' => 'Your order has been placed! Credit card payment will be processed securely. We\'ll notify you when ready for pickup. Contact: +673 8123456',
            'bank_transfer' => 'Your order has been placed! Please transfer $' . number_format($totalPrice, 2) . ' to UNISSA Café via BIBD (Account: [Your Real Account Number]). Use your phone number as reference. WhatsApp confirmation to +673 8123456',
            'cash' => 'Your order has been placed successfully! Please bring exact amount ($' . number_format($totalPrice, 2) . ') when collecting your order. Contact: +673 8123456',
            default => 'Your order has been placed successfully! Contact us at +673 8123456 for any questions.'
        };

        return redirect()->route('unissa-cafe.homepage')
            ->with('success', $successMessage . " Order ID: #{$order->id}");
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
            'payment_method' => 'required|in:cash,online,bank_transfer',
            // Credit card fields (required only for online payments)
            'card_number' => 'required_if:payment_method,online|nullable|string|min:13|max:19',
            'card_expiry' => 'required_if:payment_method,online|nullable|string|size:5',
            'card_cvv' => 'required_if:payment_method,online|nullable|string|min:3|max:4',
            'cardholder_name' => 'required_if:payment_method,online|nullable|string|max:255',
            // Bank transfer fields (required only for bank transfer payments)
            'bank_name' => 'required_if:payment_method,bank_transfer|nullable|string|max:100',
            'bank_reference' => 'required_if:payment_method,bank_transfer|nullable|string|max:100',
            'pickup_notes' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
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

        $paymentStatus = match($validated['payment_method']) {
            'cash' => Order::PAYMENT_STATUS_PENDING,        // Cash paid on pickup
            'bank_transfer' => Order::PAYMENT_STATUS_PENDING, // Requires manual verification
            'online' => Order::PAYMENT_STATUS_PENDING,      // Requires actual payment processing
            default => Order::PAYMENT_STATUS_PENDING
        };
        $orders = [];

        // Create separate orders for each cart item
        foreach ($cartItems as $item) {
            $orderData = [
                'user_id' => Auth::id(),
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->product->price,
                'total_price' => $item->total_price,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'pickup_notes' => $validated['pickup_notes'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'status' => Order::STATUS_PENDING,
                'payment_method' => $validated['payment_method'],
                'payment_status' => $paymentStatus,
            ];

            // Add payment reference for bank transfer
            if ($validated['payment_method'] === 'bank_transfer' && isset($validated['bank_reference'])) {
                $orderData['payment_reference'] = $validated['bank_name'] . ' - ' . $validated['bank_reference'];
            }

            $order = Order::create($orderData);
            $orders[] = $order;

            // Send order confirmation email for each order
            try {
                Mail::to($order->customer_email)->send(new OrderConfirmationMail($order));
            } catch (\Exception $e) {
                // Log email error but don't fail the order
                \Log::error('Failed to send order confirmation email for order #' . $order->id . ': ' . $e->getMessage());
            }

            // Update stock quantities if tracking
            if ($item->product->track_stock) {
                $item->product->decrement('stock_quantity', $item->quantity);
            }
        }

        $totalPrice = $cartItems->sum('total_price');
        $orderCount = count($orders);

        // Clear the cart after successful order
        \App\Models\Cart::where('user_id', Auth::id())->delete();

        $successMessage = match($validated['payment_method']) {
            'online' => 'Your orders have been placed! Credit card payment will be processed securely. We\'ll notify you when ready for pickup. Contact: +673 8123456',
            'bank_transfer' => 'Your orders have been placed! Please transfer $' . number_format($totalPrice, 2) . ' to UNISSA Café via BIBD (Account: [Your Real Account Number]). Use your phone number as reference. WhatsApp confirmation to +673 8123456',
            'cash' => 'Your orders have been placed successfully! Please bring exact amount ($' . number_format($totalPrice, 2) . ') when collecting your order. Contact: +673 8123456',
            default => 'Your orders have been placed successfully! Contact us at +673 8123456 for any questions.'
        };

        return redirect()->route('unissa-cafe.homepage')
            ->with('success', $successMessage . " {$orderCount} order(s) created with total amount: $" . number_format($totalPrice, 2));
    }
}