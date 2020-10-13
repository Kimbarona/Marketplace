<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// front-end
Route::get('meals/{id}/{storeId}', 'FrontendController@show');
Route::get('meals', 'FrontendController@index');

// masterlist
Route::resource('manage-store', 'StoreController')->middleware('auth:sanctum');
Route::resource('manage-product', 'ProductController')->middleware('auth:sanctum');
Route::resource('manage-price', 'PriceController')->middleware('auth:sanctum');
Route::resource('manage-meal', 'MealController')->middleware('auth:sanctum');
Route::resource('manage-recipe', 'RecipeController')->middleware('auth:sanctum');
Route::resource('manage-recipe-items', 'RecipeItemsController')->middleware('auth:sanctum');
Route::resource('manage-user', 'UserController')->middleware('auth:sanctum');

// user aunthentication
Route::post('/register', 'LoginController@register');
Route::post('/login', 'LoginController@login');
Route::post('/logout', 'LoginController@logout');

// importing price
Route::post('import-price-change', 'ImportPriceController@update');
Route::post('import-bulk-price', 'ImportBulkController@import');
