<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $table = 'stores';
    protected $guarded = ['store_id', 'store_name', 'store_location'];

    // public function meal()
    // {
    //     return $this->belongsTo(Meal::class);
    // }


}
