<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SellerProfile; // Assuming SellerProfile is in the same namespace or needs to be imported

class StoreType extends Model
{
    protected $fillable = ['name'];

    public function sellerProfiles()
    {
        return $this->hasMany(SellerProfile::class, 'store_type_id');
    }
}
