<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecipeItem extends Model
{
    protected $table = 'recipe_items';
    protected $guarded =
    [
        'recipe_id',
        'product_id',
        'product_name',
        'product_amount',
        'quantity',
        'uom',
        'product_image'
    ];

    public function meals()
    {
        return $this->belongsTo('App\Meal');
    }

    public function price()
    {
        return $this->hasOne('App\Price', 'product_id', 'product_id');
    }
}
