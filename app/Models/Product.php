<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Models\User; // Added for seller relationship
use App\Models\ProductType; // Added for type relationship

class Product extends Model
{
    use HasTranslations;

    public $translatable = ['name', 'description'];

    protected $fillable = [
        'seller_id',
        'product_type_id',
        'name',
        'description',
        'price',
        'is_active',
        'payment_methods',
    ];

    protected $casts = [
        'payment_methods' => 'array',
        'is_active' => 'boolean',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function type()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function photos()
    {
        return $this->hasMany(ProductPhoto::class);
    }

    // Fallback accessor for backward compatibility
    public function getThumbnailAttribute()
    {
        if ($this->photos()->exists()) {
            return $this->photos()->first()->photo_path;
        }
        return $this->photo;
    }

    public function getFinalPriceAttribute()
    {
        $feePercentage = (float) \App\Models\Setting::where('key', 'platform_fee')->value('value') ?? 0;
        return $this->price + ($this->price * ($feePercentage / 100));
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }

    public function getReviewCountAttribute()
    {
        return $this->reviews()->count();
    }
}
