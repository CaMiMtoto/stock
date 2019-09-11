<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/products', 'ProductsController@getProducts');
Route::get('/menus', 'Api\MenusController@index');
Route::post('/orders', 'Api\OrdersController@store');
