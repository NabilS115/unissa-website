<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserOrderController extends Controller
{
    /**
     * Display the user's order history
     */
    public function index()
    {
        $orders = Order::with('product')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.orders.index', compact('orders'));
    }

    /**
     * Display a specific order
     */
    public function show(Order $order)
    {
        // Ensure user can only view their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $order->load('product');

        return view('user.orders.show', compact('order'));
    }

    /**
     * Cancel an order (if allowed)
     */
    public function cancel(Order $order)
    {
        // Ensure user can only cancel their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Only allow cancellation using the model's method
        if (!$order->canBeCancelled()) {
            return redirect()
                ->route('user.orders.show', $order)
                ->with('error', 'Cannot cancel this order. It may already be in progress or completed.');
        }

        // Update order status to cancelled and payment status to refunded
        $order->update([
            'status' => Order::STATUS_CANCELLED,
            'payment_status' => Order::PAYMENT_STATUS_REFUNDED
        ]);

        // Log the automatic payment status update
        Log::info('Customer cancelled order - automatically updated payment status', [
            'order_id' => $order->id,
            'customer_id' => $order->user_id,
            'old_status' => $order->getOriginal('status'),
            'new_status' => Order::STATUS_CANCELLED,
            'old_payment_status' => $order->getOriginal('payment_status'),
            'new_payment_status' => Order::PAYMENT_STATUS_REFUNDED
        ]);

        // Restore stock if product tracks stock
        if ($order->product && $order->product->track_stock) {
            $order->product->increment('stock_quantity', $order->quantity);
        }

        return redirect()
            ->route('user.orders.index')
            ->with('success', 'Order cancelled successfully. Stock has been restored.');
    }
}