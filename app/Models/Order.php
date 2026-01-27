<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Product;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'buyer_id',
        'seller_id',
        'product_id',
        'quantity',
        'total_price',
        'platform_fee',
        'seller_earning',
        'payment_method',
        'delivery_address',
        'status',
        'guest_info',
        'payout_status',
        'payout_amount',
        'payout_date',
        'payout_method',
        'payout_transaction_id',
        'payout_details',
    ];

    protected $casts = [
        'guest_info' => 'array',
        'payout_date' => 'datetime',
        'payout_details' => 'array',
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}
