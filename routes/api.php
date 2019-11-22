<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get('/products', 'ProductsController@getProducts');
Route::get('/products/all', 'ProductsController@getAllProducts');
Route::get('/menus', 'Api\MenusController@index');
Route::post('/orders', 'Api\OrdersController@store');
