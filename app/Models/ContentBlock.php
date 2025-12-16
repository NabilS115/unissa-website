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
