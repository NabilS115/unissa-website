<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'profile_photo_url',
        'phone',
        'department',
        'payment_method',
        'payment_details',
        'cardholder_name',
        'card_number',
        'card_expiry',
        'card_ccv',
        'billing_address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }
    /**
     * Get all reviews by this user
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    /**
     * Get profile photo URL or generate avatar
     */
    public function getProfilePhotoUrlAttribute($value)
    {
        if ($value) {
            return $value;
        }
    // Fallback SVG avatar fills the square, initial maximized and centered
    $initials = strtoupper($this->initials());
    $bg = '14b8a6'; // teal
    $color = 'fff';
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="128" height="128"><rect width="128" height="128" rx="24" fill="#'.$bg.'"/><text x="50%" y="50%" font-size="84" fill="#'.$color.'" font-family="Arial, Helvetica, sans-serif" font-weight="bold" text-anchor="middle" dominant-baseline="central">'.$initials.'</text></svg>';
    return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Get all orders by this user
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

