<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
        'status',
        'customer_name',
        'customer_email',
        'customer_phone',
        'pickup_notes',
        'notes',
    ];

    /**
     * Order status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_PROCESSING = 'processing';
    const STATUS_READY_FOR_PICKUP = 'ready_for_pickup';
    const STATUS_PICKED_UP = 'picked_up';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get all possible order statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_READY_FOR_PICKUP => 'Ready for Pickup',
            self::STATUS_PICKED_UP => 'Picked Up',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    /**
     * Get the user that owns the order
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product associated with the order
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_CONFIRMED => 'blue',
            self::STATUS_PROCESSING => 'purple',
            self::STATUS_READY_FOR_PICKUP => 'orange',
            self::STATUS_PICKED_UP => 'green',
            self::STATUS_CANCELLED => 'red',
            default => 'gray',
        };
    }

    /**
     * Check if order can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_CONFIRMED, self::STATUS_PROCESSING]);
    }
}
