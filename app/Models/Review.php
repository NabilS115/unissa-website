<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'review',
        'helpful_count'
    ];

    protected $casts = [
        'rating' => 'integer',
        'helpful_count' => 'integer'
    ];

    // Relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Product model  
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Check if review is marked as helpful by a specific user
    public function isHelpfulBy($user)
    {
        if (!$user) return false;
        
        return \DB::table('review_helpfuls')
            ->where('review_id', $this->id)
            ->where('user_id', $user->id)
            ->exists();
    }

    // Get users who marked this review as helpful
    public function helpfulUsers()
    {
        return $this->belongsToMany(User::class, 'review_helpfuls', 'review_id', 'user_id')
                    ->withTimestamps();
    }
}
