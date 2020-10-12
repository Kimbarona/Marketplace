<?php

namespace App\Http\Controllers;

use App\Price;
use App\Product;
use App\Store;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $store = DB::table('stores')->select('store_id', 'store_name')->paginate(100000);

        $products = DB::table('products')->select('product_id', 'product_name')->paginate(100000);

        $price = Price::orderBy('created_at', 'desc')->Paginate(100000);

        return response()->json(["price" => $price, "sid" => $store, "pid" => $products], Response::HTTP_OK);
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
            'store_id' => 'required',
            'product_id' => 'required|unique:prices,product_id,NULL,store_id,store_id,' . $request->store_id,
            'price' => 'required'

        ]);

        $price = new Price();
        $price->store_id = $request->store_id;
        $price->product_id = $request->product_id;
        $price->price = $request->price;

        if ($price->save()) {
            return response()->json($price, Response::HTTP_OK);
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
     * @param  \App\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function show(Price $price)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function edit(Price $price)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Price $price, $id)
    {
        $request->validate([
            'price' => 'required'
        ]);

        $price = Price::find($id);
        $price->store_id = $request->store_id;
        $price->product_id = $request->product_id;
        $price->price = $request->price;

        if ($price->update()) {
            return response()->json($price, Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Some error occured, Please try again!',
                'status_code' => 500
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function destroy(Price $price, $id)
    {
        $price = Price::findOrfail($id);
        if ($price->delete()) {

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
