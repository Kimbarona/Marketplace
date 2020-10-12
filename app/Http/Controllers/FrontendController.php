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
        $meal = Meal::whereHas('price', function ($query) {
            $query->where('store_id', 101);
        })
        ->with(['recipeitem.price' => function($query){
            $query->where('store_id', 101);
        }])
        ->get();

        return response()->json(["meals" => $meal], Response::HTTP_OK);
    }

    public function show($meat_type)
    {

        // $meal = Meal::with(['recipeitem'])->where('meat_type', $meat_type)->get();
        $meal = Meal::whereHas('price', function ($query, $meat_type) {
            $query->where('store_id', $meat_type);
        })
        ->with(['recipeitem.price' => function($query){
            $query->where('store_id', 101);
        }])
        ->get();

        return response()->json(["meals" => $meal], Response::HTTP_OK);
    }
}
