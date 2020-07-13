<?php

namespace App\Http\Controllers;

use App\Damage;
use App\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class DamageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $damages = Damage::with('product.category')->latest()
            ->paginate(10);
        return view('admin.damages', compact('damages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $product = Product::find($request->input('product_id'));
        $qty = $request->qty;
        $damage = Damage::create([
            'product_id' => $product->id,
            'qty' => $qty,
            'recorded_by' => auth()->id(),
        ]);

        $product->qty -= $qty;
        $product->update();
        return back()->with('success', 'Damage recorded');
    }

    /**
     * Display the specified resource.
     *
     * @param Damage $damage
     * @return Response
     */
    public function show(Damage $damage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Damage $damage
     * @return Damage
     */
    public function edit(Damage $damage)
    {
        $damage->load('product');
        return $damage;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Damage $damage
     * @return Response
     */
    public function update(Request $request, Damage $damage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Damage $damage
     * @return Response
     */
    public function destroy(Damage $damage)
    {
        //
    }
}
