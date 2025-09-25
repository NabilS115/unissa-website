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
        'type'
    ];

    // Relationship with Review model
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}

