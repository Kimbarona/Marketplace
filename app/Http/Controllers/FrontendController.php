<?php

namespace App\Http\Controllers;

use App\Meal;
use App\Recipe;
use App\Product;
use App\Store;
use App\Price;
use App\RecipeItem;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {
        // $meal = Meal::whereHas('price', function ($query) {
        //     $query->where('store_id', 100);

        // })
        // ->with(['recipeitem.price' => function($query){
        //     $query->where('store_id', 100);

        // }])
        // ->with(['meals' => function($query){
        //     $query->whereIn('meal_id', [300, 301]);

        // }])


        // $recipe = DB::table('meals')->select('meal_id')->get();
        $meal = Meal::with(['recipeitem.price'])
        // ->whereIn('meal_id', [300, 301])
        // ->whereIn('meal_id', [300, 301])
        ->get();

        return response()->json(["meals" => $meal], Response::HTTP_OK);
    }

    public function show($meat_type,$data)
    {

        // $meal = Meal::with(['recipeitem'])->where('meat_type', $meat_type)->get();
        $meal = Meal::whereHas('price', function ($query) use ($data) {
            $query->where('store_id', $data);
        })
        ->with(['recipeitem.price' => function($query) use ($data){
            $query->where('store_id', $data);
        }])
        ->where('store_id', $data)
        ->where('meat_type', $meat_type)
        ->get();

        return response()->json(["meals" => $meal], Response::HTTP_OK);
    }
}
