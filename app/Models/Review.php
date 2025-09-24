<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'review',
        'helpful_count',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function helpfuls()
    {
        return $this->hasMany(ReviewHelpful::class);
    }

    public function isHelpfulBy($user)
    {
        if (!$user) return false;
        return $this->helpfuls()->where('user_id', $user->id)->exists();
    }
}
