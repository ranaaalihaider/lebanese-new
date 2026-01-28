<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SellerProfile; // Assuming SellerProfile is in the same namespace or needs to be imported
use Spatie\Translatable\HasTranslations;

class StoreType extends Model
{
    use HasTranslations;

    public $translatable = ['name'];

    protected $fillable = ['name'];

    public function sellerProfiles()
    {
        return $this->hasMany(SellerProfile::class, 'store_type_id');
    }
}
