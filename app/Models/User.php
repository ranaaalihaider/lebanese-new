<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'otp_code',
        'otp_expires_at',
        'role',
        'status',
        'house_no',
        'street',
        'city',
        'state',
        'postal_code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
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
            'otp_expires_at' => 'datetime',
        ];
    }

    public function sellerProfile()
    {
        return $this->hasOne(SellerProfile::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function sellerOrders()
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    public function buyerOrders()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function ratings()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function wishlistProducts()
    {
        return $this->belongsToMany(Product::class, 'wishlists', 'user_id', 'product_id');
    }

    public function hasWishlisted($productId)
    {
        return $this->wishlists()->where('product_id', $productId)->exists();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Reviews received by this seller (via their products)
    public function sellerReviews()
    {
        return $this->hasManyThrough(Review::class, Product::class, 'seller_id', 'product_id');
    }

    public function getSellerRatingAttribute()
    {
        return round($this->sellerReviews()->avg('rating') ?? 0, 1);
    }

    public function getSellerReviewCountAttribute()
    {
        return $this->sellerReviews()->count();
    }
}
