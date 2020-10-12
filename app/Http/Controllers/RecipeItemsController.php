<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Price;
use App\RecipeItem;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RecipeItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $RecipeItem = RecipeItem::orderBy('created_at', 'desc')->Paginate();

        return response()->json(["recipeitems" => $RecipeItem], Response::HTTP_OK);
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
            'product_id' => 'required',
            'quantity' => 'required',
            'uom' => 'required'
        ]);

        $productId = $request->product_id;
        $productname = DB::table('products')->select('product_name', 'product_image')
        ->where('product_id', $productId)
        ->get();
            foreach($productname as $name)
            {
                $product_name = $name->product_name;
                $product_image = $name->product_image;
            }

        $productPrice = DB::table('prices')->select('price')
        ->where('product_id', $productId)
        ->get();
            foreach($productPrice as $price)
            {
                $product_amount = $price->price;
            }


            $recipeitem = new RecipeItem();
            $recipeitem->recipe_id = $request->recipe_id;
            $recipeitem->product_id = $request->product_id;
            $recipeitem->product_name = $product_name;
            $recipeitem->product_amount = $product_amount;
            $recipeitem->quantity = $request->quantity;
            $recipeitem->uom = $request->uom;
            $recipeitem->product_image = $product_image;


            if ($recipeitem->save()) {
                return response()->json($recipeitem, 200);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required',
            'uom' => 'required'
        ]);
        $recipeitem = RecipeItem::find($id);
        $recipeitem->quantity = $request->quantity;
        $recipeitem->uom = $request->uom;

        if($recipeitem->update()){
            return response()->json($recipeitem, Response::HTTP_OK);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $recipeitem = RecipeItem::findOrfail($id);
        if ($recipeitem->delete()) {

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
