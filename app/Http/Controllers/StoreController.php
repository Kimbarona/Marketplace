<?php

namespace App\Http\Controllers;

use App\Store;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $store = Store::orderBy('created_at', 'desc')->Paginate(100000);
        return response()->json($store, Response::HTTP_OK);
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
            'store_id' => 'required|min:3|unique:stores,store_id',
            'store_name' => 'required|min:3|max:50|unique:stores,store_name',
            'store_location' => 'required|min:3|max:100',
        ]);

        $store = new Store();
        $store->store_id = $request->store_id;
        $store->store_name = $request->store_name;
        $store->store_location = $request->store_location;

        if ($store->save()) {
            return response()->json($store, 200);
        } else {
            return response()->json([
                'message' => 'Some error occured, Please try again!',
                'status_id' => 500
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Store $store, $id)
    {
        $request->validate([
            'store_id' => 'required|min:3',
            'store_name' => 'required|min:3|max:50',
            'store_location' => 'required|min:3|max:100',
        ]);
        $store = Store::find($id);
        // $store->store_id = $request->store_id;
        $store->store_name = $request->store_name;
        $store->store_location = $request->store_location;

        if ($store->update()) {
            return response()->json($store, 200);
        } else {
            return response()->json([
                'message' => 'Some error occured, Please try again!',
                'status_id' => 500
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $store = Store::findOrfail($id);
        if ($store->delete()) {

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
