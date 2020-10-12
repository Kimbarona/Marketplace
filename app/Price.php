<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $table = 'prices';
    protected $fillable =
    [
        'store_id',
        'product_id',
        'price'
    ];

    public function recipeitems()
    {
        return $this->belongsTo('App\RecipeItem');
    }
}
