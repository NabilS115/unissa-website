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
        'price'
    ];

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
}

