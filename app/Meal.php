<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    protected $table = 'meals';
    protected $guarded =
    [
        'store_id',
        'meal_id',
        'recipe_id',
        'meal_name',
        'meat_type',
        'meal_description',
        'meal_serving',
        'meal_preparation',
        'estimated_amount',
        'meal_image'
    ];


    // protected  $primaryKey = 'meat_type';

    public $incrementing = false;


    public function recipeitem()
    {
        return $this->hasMany('App\RecipeItem', 'recipe_id', 'recipe_id');
    }

    public function price()
    {
        return $this->hasMany('App\Price', 'store_id', 'store_id');
    }

    // public function price()
    // {
    //     return $this->hasManyThrough(Price::class, RecipeItem::class, 'recipe_id','product_id', 'recipe_id','product_id');
    // }
}
