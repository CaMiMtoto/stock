<?php

namespace App\Http\Controllers;

use App\MenuItem;
use Illuminate\Http\Request;

class MenuItemsController extends Controller
{
    public  function updateMenuItem(Request $request,MenuItem $item){
        $item->qty=$request->qty;
        $item->cost=$request->cost;
        $item->update();
        return response($item,200);
    }
}
