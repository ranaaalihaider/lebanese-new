<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SellerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'store_type_id',
        'store_name',
        'store_description',
        'store_photo',
        'earnings',
        'pickup_location',
        'language_preference',
        'store_tagline',
        'latitude',
        'longitude',
        'bank_name',
        'account_number',
        'account_title',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function storeType()
    {
        return $this->belongsTo(StoreType::class);
    }
}
