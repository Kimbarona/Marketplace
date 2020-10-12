<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->Paginate(100000);
        return response()->json($products, Response::HTTP_OK);
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
            'product_id' => 'required|min:3|unique:products,product_id',
            'product_name' => 'required|min:3',
            'product_category' => 'required'

        ]);

        $products = new Product();
        $products->product_id = $request->product_id;
        $products->product_name = $request->product_name;
        $products->category_name = $request->product_category;
        $path = $request->file('product_image')->store('product_images');
        $products->product_image = $path;

        if ($products->save()) {
            return response()->json($products, 200);
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
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product, $id)
    {
        $request->validate([
            'product_name' => 'required|min:3|unique:products,product_id',
            'product_category' => 'required'
        ]);

        $products = Product::find($id);
        $products->product_name = $request->product_name;
        $products->category_name = $request->product_category;
        $oldPath = $products->product_image;

        if ($request->hasFile('product_image')) {

            $path = $request->file('product_image')->store('product_images');
            $products->product_image = $path;

            Storage::delete($oldPath);
        }

        if ($products->update()) {
            return response()->json($products, Response::HTTP_OK);
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
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, $id)
    {
        $product = Product::findOrfail($id);
        if ($product->delete()) {

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
