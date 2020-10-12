<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $table = 'recipes';
    protected $guarded =
    [
        'recipe_id',
        'recipe_name'
    ];

    // public function recipeitem()
    // {
    //     return $this->hasMany(RecipeItem::class, 'recipeitem_id', '');
    // }

    // public function meal()
    // {
    //     return $this->belongsTo(Meal::class);
    // }

    // public function recipeitem()
    // {
    //     return $this->hasMany(RecipeItem::class);
    // }

    // public function recipe()
    // {
    //     return $this->hasOne(Meal::class, '', 'meal_id');
    // }

    // public function price()
    // {

    //     return $this->hasMany(Price::class, 'product_id', 'product_id');
    // }
}
