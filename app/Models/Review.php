<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'product_id', 
        'user_id', 
        'rating', 
        'review', 
        'helpful_count'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function helpfulVotes()
    {
        return $this->hasMany(ReviewHelpful::class);
    }
}
