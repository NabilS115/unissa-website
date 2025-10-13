<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_url',
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
        // Get the raw attribute value
        $imagePath = $this->attributes['image_url'] ?? null;
        
        // If it's already a full URL, return it
        if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
            return $imagePath;
        }
        
        // If it's a file path and exists in storage, return the URL
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            // Use Laravel's url helper to generate the full URL
            return url('storage/' . $imagePath);
        }
        
        return asset('images/placeholder.jpg');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($gallery) {
            // Only delete if it's a file path (not a URL)
            if ($gallery->image_url && !filter_var($gallery->image_url, FILTER_VALIDATE_URL) && Storage::disk('public')->exists($gallery->image_url)) {
                Storage::disk('public')->delete($gallery->image_url);
            }
        });
    }
}