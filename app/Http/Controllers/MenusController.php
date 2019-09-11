<?php

namespace App\Http\Controllers;

use App\Category;
use App\Menu;
use App\MenuItem;
use Illuminate\Http\Request;

class MenusController extends Controller
{
    public function index()
    {
        $menus = Menu::orderBy('created_at', 'desc')->paginate(10);
        $categories = Category::all();
        return view('admin.menus', ['categories' => $categories, 'menus' => $menus]);
    }


    public function store(Request $request)
    {
        if ($request->id == 0) {
            Menu::create([
                'name' => $request->name,
                'price' => $request->price
            ]);
        } else {
            $menu = Menu::find($request->id);
            $menu->name = $request->name;
            $menu->price = $request->price;
            $menu->update();
        }
        return response()->json(null, 200);
    }


    public function show(Menu $menu)
    {
        return response()->json($menu, 200);
    }


    public function menuItems(Menu $menu)
    {
        $menuItems = $menu->menuItems()->get();
        return view('admin.menus_items', ['menuItems' => $menuItems, 'menu' => $menu]);
    }

    public function addMenuItem(Request $request, Menu $menu)
    {
        $request->validate([
            'product_id' => 'required|numeric',
            'qty' => 'required|numeric',
            'measure' => 'required',
            'cost' => 'required|numeric',
        ]);

        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu->id;
        $menuItem->product_id = $request->product_id;
        $menuItem->qty = $request->qty;
        $menuItem->measure = $request->measure;
        $menuItem->cost = $request->cost;
        $menuItem->save();

        $menuItems = $menu->menuItems()->get();
        return view('admin.menus_items', ['menuItems' => $menuItems, 'menu' => $menu]);
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return response()->json(null, 200);
    }

    public function removeItem(MenuItem $menuItem)
    {
        $menuItem->delete();
        return response()->json(null, 200);
    }
}
