<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\MenuResource;
use App\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenusController extends Controller
{
    public function index()
    {
        return response()->json(Menu::all(),200);
    }

    public function show(Menu $menu)
    {
        return new MenuResource($menu);
    }

    public function update(Menu $menu, Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $menu->update($data);
        return new MenuResource($menu);
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return response(null, 204);
    }
}
