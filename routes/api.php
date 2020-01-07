<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get('/products', 'ProductsController@getProducts');
Route::get('/products/all', 'ProductsController@getAllProducts')->name('api.getAllProducts');
Route::get('/menus', 'Api\MenusController@index');
Route::post('/orders', 'Api\OrdersController@store');
Route::get('/search/products', 'Api\ProductsController@getProducts')->name('api.search.products');
