<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    protected $fillable = [
        'type',
        'title', 
        'message',
        'data',
        'read',
        'email_sent',
        'read_at'
    ];

    protected $casts = [
        'data' => 'array',
        'read' => 'boolean',
        'email_sent' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Create a new order notification
     */
    public static function createOrderNotification(Order $order)
    {
        return self::create([
            'type' => 'new_order',
            'title' => "New Order #{$order->id}",
            'message' => "{$order->customer_name} placed a new order worth B$" . number_format($order->total_price, 2),
            'data' => [
                'order_id' => $order->id,
                'customer_name' => $order->customer_name,
                'customer_email' => $order->customer_email,
                'total_price' => $order->total_price,
                'payment_method' => $order->payment_method,
            ]
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update([
            'read' => true,
            'read_at' => now()
        ]);
    }

    /**
     * Get unread notifications count
     */
    public static function unreadCount()
    {
        return self::where('read', false)->count();
    }
}
