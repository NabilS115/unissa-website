<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentBlock extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'type',
        'content',
        'page',
        'section',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get content by key with fallback to default
     */
    public static function get($key, $default = '')
    {
        $block = static::where('key', $key)
                      ->where('is_active', true)
                      ->first();
        
        return $block ? $block->content : $default;
    }

    /**
     * Set content for a key
     */
    public static function set($key, $content, $type = 'text', $page = 'homepage', $section = null)
    {
        // Handle image removal - if content is null or empty for image fields, 
        // either delete the record or set to empty string
        if (empty($content) && (str_contains($key, 'image') || str_contains($key, '_image'))) {
            // For image fields, delete the record entirely when removing
            static::where('key', $key)->delete();
            return null;
        }

        // Ensure content is never null - use empty string as minimum
        $content = $content ?? '';

        return static::updateOrCreate(
            ['key' => $key],
            [
                'content' => $content,
                'type' => $type,
                'page' => $page,
                'section' => $section,
                'is_active' => true
            ]
        );
    }
}
