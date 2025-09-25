<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'image_path',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    public function getImageUrlAttribute()
    {
        // If it's already a full URL, return it
        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            return $this->image_path;
        }
        
        // If it's a file path and exists in storage, return the URL
        if ($this->image_path && Storage::disk('public')->exists($this->image_path)) {
            return url('storage/' . $this->image_path);
        }
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=0d9488&color=fff';
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($vendor) {
            // Only delete if it's a file path (not a URL)
            if ($vendor->image_path && !filter_var($vendor->image_path, FILTER_VALIDATE_URL) && Storage::disk('public')->exists($vendor->image_path)) {
                Storage::disk('public')->delete($vendor->image_path);
            }
        });
    }
}
