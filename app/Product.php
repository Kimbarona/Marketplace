<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $guarded =
    [
        'store_code',
        'product_id',
        'product_name',
        'price'
    ];

    // public function price(){

    //     return $this->hasMany(Price::class, '', 'product_id');
    // }

    // public function meal()
    // {
    //     return $this->belongsTo(Meal::class);
    // }

}
