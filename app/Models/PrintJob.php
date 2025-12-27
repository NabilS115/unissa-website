<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PrintJob extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'filename',
        'original_filename',
        'file_path',
        'file_type',
        'file_size',
        'paper_size',
        'color_option',
        'paper_type',
        'copies',
        'orientation',
        'price_per_page',
        'total_price',
        'page_count',
        'status',
        'notes',
        'admin_notes'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'price_per_page' => 'decimal:2',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Accessors
    public function getFileSizeFormattedAttribute(): string
    {
        $size = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $size >= 1024 && $i < 3; $i++) {
            $size /= 1024;
        }
        
        return round($size, 2) . ' ' . $units[$i];
    }

    public function getFileUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    public function getStatusDisplayAttribute(): string
    {
        return match($this->status) {
            'uploaded' => 'File Uploaded',
            'processing' => 'Processing',
            'ready' => 'Ready for Pickup',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->status)
        };
    }

    public function getColorOptionDisplayAttribute(): string
    {
        return $this->color_option === 'black_white' ? 'Black & White' : 'Color';
    }

    public function getPaperSizeDisplayAttribute(): string
    {
        return $this->paper_size;
    }

    public function getPaperTypeDisplayAttribute(): string
    {
        return match($this->paper_type) {
            'regular' => 'Regular Paper',
            'photo' => 'Photo Paper',
            'cardstock' => 'Cardstock',
            default => ucfirst($this->paper_type)
        };
    }

    // Static methods for pricing
    public static function getPricePerPage(string $colorOption, string $paperType): float
    {
        $prices = [
            'black_white' => [
                'regular' => 0.10,
                'photo' => 0.50,
                'cardstock' => 0.25,
            ],
            'color' => [
                'regular' => 0.25,
                'photo' => 1.00,
                'cardstock' => 0.50,
            ]
        ];

        return $prices[$colorOption][$paperType] ?? 0.10;
    }

    // Boot method for auto-calculating price
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($printJob) {
            if (!$printJob->price_per_page) {
                $printJob->price_per_page = self::getPricePerPage(
                    $printJob->color_option, 
                    $printJob->paper_type
                );
            }
            
            $printJob->total_price = $printJob->price_per_page * $printJob->page_count * $printJob->copies;
        });
    }

    // Static method to link print jobs to orders after checkout
    public static function linkToOrder($order, $cartNotes = null)
    {
        if (!$cartNotes) return;
        
        // Extract print job ID from cart notes
        if (preg_match('/Print Job ID: (\d+)/', $cartNotes, $matches)) {
            $printJobId = $matches[1];
            $printJob = self::find($printJobId);
            
            if ($printJob && $printJob->user_id === $order->user_id) {
                $printJob->update([
                    'order_id' => $order->id,
                    'status' => 'processing'
                ]);
            }
        }
    }
}
