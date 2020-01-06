<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\Request;
use Exception;
use Illuminate\Http\Response;

class RequestController extends Controller
{
    public function index()
    {
        $req = Request::paginate(10);
        $products = Product::all();
        return view('admin.requested', compact('products'))
            ->with(['requisitions' => $req,'categories'=>Category::all()]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Support\Facades\Request $request
     * @return void
     */
    public function store(\Illuminate\Support\Facades\Request $request)
    {
        //
    }

    public function show(Request $request)
    {
        return $request;
    }

    public function edit(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Support\Facades\Request $req
     * @param Request $request
     * @return void
     */
    public function update(\Illuminate\Support\Facades\Request $req, Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function destroy(Request $request)
    {
        $request->delete();
        return \response(null, 204);
    }
}
