<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'desc',
        'category',
        'img',
        'type',
        'price',
        'is_active',
        'status',
        'stock_quantity',
        'track_stock',
        'low_stock_threshold',
        'last_restocked_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'track_stock' => 'boolean',
        'stock_quantity' => 'integer',
        'low_stock_threshold' => 'integer',
        'last_restocked_at' => 'datetime'
    ];

    /**
     * Product status constants
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_OUT_OF_STOCK = 'out_of_stock';
    const STATUS_DISCONTINUED = 'discontinued';

    /**
     * Get all available statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_OUT_OF_STOCK => 'Out of Stock',
            self::STATUS_DISCONTINUED => 'Discontinued',
        ];
    }

    // Relationship with Review model
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Relationship with Order model
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Relationship with FeaturedProduct model
    public function featuredProduct()
    {
        return $this->hasOne(FeaturedProduct::class);
    }

    /**
     * Scope for active products
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope for available products (active and in stock if tracked)
     */
    public function scopeAvailable($query)
    {
        return $query->active()
            ->where(function($q) {
                $q->where('track_stock', false)
                  ->orWhere(function($subQ) {
                      $subQ->where('track_stock', true)
                           ->where('stock_quantity', '>', 0);
                  });
            });
    }

    /**
     * Scope for out of stock products
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('track_stock', true)
                    ->where('stock_quantity', '<=', 0);
    }

    /**
     * Scope for low stock products
     */
    public function scopeLowStock($query)
    {
        return $query->where('track_stock', true)
                    ->whereRaw('stock_quantity <= low_stock_threshold')
                    ->where('stock_quantity', '>', 0);
    }

    /**
     * Check if product is available for ordering
     */
    public function isAvailable(): bool
    {
        if (!$this->is_active || $this->status !== self::STATUS_ACTIVE) {
            return false;
        }

        if ($this->track_stock && $this->stock_quantity <= 0) {
            return false;
        }

        return true;
    }

    /**
     * Check if product is in stock
     */
    public function isInStock(): bool
    {
        if (!$this->track_stock) {
            return true; // Not tracking stock, assume in stock
        }

        return $this->stock_quantity > 0;
    }

    /**
     * Check if product is low on stock
     */
    public function isLowStock(): bool
    {
        if (!$this->track_stock) {
            return false;
        }

        return $this->stock_quantity > 0 && $this->stock_quantity <= $this->low_stock_threshold;
    }

    /**
     * Get availability status text
     */
    public function getAvailabilityStatusAttribute(): string
    {
        if (!$this->is_active) {
            return 'Inactive';
        }

        return match($this->status) {
            self::STATUS_ACTIVE => $this->track_stock ? 
                ($this->stock_quantity > 0 ? 'In Stock' : 'Out of Stock') : 'Available',
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_OUT_OF_STOCK => 'Out of Stock',
            self::STATUS_DISCONTINUED => 'Discontinued',
            default => 'Unknown',
        };
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        if (!$this->is_active) {
            return 'gray';
        }

        return match($this->status) {
            self::STATUS_ACTIVE => $this->isInStock() ? 'green' : 'red',
            self::STATUS_INACTIVE => 'gray',
            self::STATUS_OUT_OF_STOCK => 'red',
            self::STATUS_DISCONTINUED => 'red',
            default => 'gray',
        };
    }

    /**
     * Reduce stock quantity (for when order is placed)
     */
    public function reduceStock(int $quantity): bool
    {
        if (!$this->track_stock) {
            return true; // Not tracking stock, always succeed
        }

        if ($this->stock_quantity < $quantity) {
            return false; // Insufficient stock
        }

        $this->decrement('stock_quantity', $quantity);

        // Auto-update status if out of stock
        if ($this->fresh()->stock_quantity <= 0) {
            $this->update(['status' => self::STATUS_OUT_OF_STOCK]);
        }

        return true;
    }

    /**
     * Increase stock quantity (for restocking)
     */
    public function increaseStock(int $quantity): void
    {
        $this->increment('stock_quantity', $quantity);
        
        // Update restock timestamp
        $this->update(['last_restocked_at' => now()]);

        // Reactivate if it was out of stock
        if ($this->status === self::STATUS_OUT_OF_STOCK) {
            $this->update(['status' => self::STATUS_ACTIVE]);
        }
    }
}

