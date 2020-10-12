<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cooking extends Model
{
    protected $table = 'cookings';
    protected $guarded =
    [
        'meal_id',
        'instruction',
        'nutrition'
    ];

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }
}
