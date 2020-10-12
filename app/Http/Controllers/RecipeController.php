<?php

namespace App\Http\Controllers;

use App\Recipe;
use App\Meal;
use App\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = DB::table('products')->select('product_id', 'product_name')->paginate(100000);

        $recipe = Recipe::orderBy('created_at', 'desc')->Paginate(100000);

        return response()->json(["recipe" => $recipe, "products" => $products], Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *;
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'recipe_id' => 'required|min:3|unique:recipes,recipe_id',
            'recipe_name' => 'required'
        ]);


        // $productId = $request->product_id;
        // $productname = DB::table('products')->select('product_name', 'product_image')
        // ->where('product_id', $productId)
        // ->get();
        //     foreach($productname as $name)
        //     {
        //         $product_name = $name->product_name;
        //         $product_image = $name->product_image;
        //     }

        // $productPrice = DB::table('prices')->select('price')
        // ->where('product_id', $productId)
        // ->get();
        //     foreach($productPrice as $price)
        //     {
        //         $product_amount = $price->price;
        //     }

        $recipe = new Recipe();
        $recipe->recipe_id = $request->recipe_id;
        $recipe->recipe_name = $request->recipe_name;

        if ($recipe->save()) {
            return response()->json($recipe, 200);
        } else {
            return response()->json([
                'message' => 'Some error occured, Please try again!',
                'status_code' => 500
            ], 500);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function show(Recipe $recipe)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function edit(Recipe $recipe)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recipe $recipe, $id)
    {
        $request->validate([
            'recipe_id' => 'required',
            'recipe_name' => 'required',
        ]);
        $recipe = Recipe::find($id);
        $recipe->recipe_id = $request->recipe_id;
        $recipe->recipe_name = $request->recipe_name;

        if($recipe->update()){
            return response()->json($recipe, Response::HTTP_OK);
        }else{
            return response()->json([
                'message' => 'Some error occured, Please try again!',
                'status_code' => 500
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recipe $recipe, $id)
    {
        $recipe = Recipe::findOrfail($id);
        if ($recipe->delete()) {

            return response()->json([
                'message' => 'Store deleted successfully!',
                'status_code' => 200
            ], 200);
        } else {
            return response()->json([
                'message' => 'Some error occured, Please try again!',
                'status_code' => 500
            ], 500);
        }
    }

}
