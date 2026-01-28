<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ProductType extends Model
{
    use HasTranslations;

    public $translatable = ['name'];

    protected $fillable = ['name'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
