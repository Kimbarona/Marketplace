<?php

namespace App\Http\Controllers;

use App\Meal;
use App\Store;
use App\Recipe;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class MealController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $recipe = DB::table('recipes')->select('recipe_id', 'recipe_name')->paginate(100000);
        $meal = Meal::with('recipeitem')->Paginate();

        $meat = DB::table('products')
            ->select('product_name')
            ->whereIn('category_name', array("Pork", "Beef", "Chicken", "Fish"))->paginate(100000);

        return response()->json(["meal" => $meal, "recipe" => $recipe, "meat" => $meat], Response::HTTP_OK);
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'meal_id' => 'required',
            'recipe_id' => 'required',
            'meal_id' => 'required|unique:meals,meal_id',
            'meal_name' => 'required',
            'meal_serving' => 'required',
            'meal_preparation' => 'required',
            'uom' => 'required',
            'estimated_amount' => 'required',
            'meal_description' => 'required',
            'meal_instruction' => 'required',
            'meal_nutrition' => 'required',
            'meal_image' => 'required',
            'meat_type' => 'required'
        ]);

        $meal = new Meal();
        $meal->meal_id = $request->meal_id;
        $meal->recipe_id = $request->recipe_id;
        $meal->meal_name = $request->meal_name;
        $meal->meat_type = $request->meat_type;
        $meal->meal_description = $request->meal_description;
        $meal->meal_serving = $request->meal_serving;
        $Uom= $request->uom;
        $meal->meal_preparation = $request->meal_preparation.'-'.$Uom;
        $meal->estimated_amount = $request->estimated_amount;
        $meal->meal_instruction = $request->meal_instruction;
        $meal->meal_nutrition = $request->meal_nutrition;
        $path = $request->file('meal_image')->store('meal_images');
        $meal->meal_image = $path;

        if ($meal->save()) {
            return response()->json($meal, 200);
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
     * @param  \App\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $meal = DB::table("meals")
        ->select('id')
        ->where('id', $id)->get();
        return response()->json(["meals" => $meal], Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function edit(Meal $meal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Meal $meal, $id)
    {
        $request->validate([
            'meal_id' => 'required',
            'recipe_id' => 'required',
            'meal_name' => 'required',
            'meal_serving' => 'required',
            'meal_preparation' => 'required',
            'estimated_amount' => 'required',
            'meal_description' => 'required',
            'meal_instruction' => 'required',
            'meal_nutrition' => 'required',
            'meal_image' => 'required',
            'meat_type' => 'required'
        ]);

        $meal = Meal::find($id);
        $meal->meal_id = $request->meal_id;
        $meal->recipe_id = $request->recipe_id;
        $meal->meal_name = $request->meal_name;
        $meal->meat_type = $request->meat_type;
        $meal->meal_description = $request->meal_description;
        $meal->meal_serving = $request->meal_serving;
        $meal->meal_preparation = $request->meal_preparation;
        $meal->estimated_amount = $request->estimated_amount;
        $meal->meal_instruction = $request->meal_instruction;
        $meal->meal_nutrition = $request->meal_nutrition;
        $oldPath = $meal->meal_image;

        if ($request->hasFile('meal_image')) {

            $path = $request->file('meal_image')->store('meal_images');
            $meal->meal_image = $path;

            Storage::delete($oldPath);
        }

        if ($meal->update()) {
            return response()->json($meal, Response::HTTP_OK);
        } else {
            Storage::delete($path);
            return response()->json([
                'message' => 'Some error occured, Please try again!',
                'status_code' => 500
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Meal $meal, $id)
    {
        $meal = Meal::findOrfail($id);
        if ($meal->delete()) {

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


