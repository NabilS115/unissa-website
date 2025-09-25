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
        'helpful_count',
    ];

    protected $casts = [
        'rating' => 'integer',
        'helpful_count' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function helpfuls()
    {
        return $this->hasMany(ReviewHelpful::class);
    }

    public function isHelpfulBy($user)
    {
        if (!$user) return false;
        
        return \DB::table('review_helpful')
            ->where('review_id', $this->id)
            ->where('user_id', $user->id)
            ->exists();
    }

    public function helpfulUsers()
    {
        return $this->belongsToMany(User::class, 'review_helpful', 'review_id', 'user_id')
                    ->withTimestamps();
    }
}
