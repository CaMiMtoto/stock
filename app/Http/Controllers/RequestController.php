<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\Request;
use App\RequestItem;
use Exception;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function index()
    {
        $req = Request::paginate(10);
        $products = Product::all();
        return view('admin.requested', compact('products'))
            ->with(['requisitions' => $req, 'categories' => Category::all()]);
    }

    public function store(\Illuminate\Http\Request $request)
    {
//        dd($request);
        DB::beginTransaction();
//        try {
            $re = new Request();
            $re->date = $request->date;
            $re->department = $request->department;
            $re->prepared_by = $request->prepared_by;
            $re->save();

            for ($i = 0; $i < count($request->product_id); $i++) {
                $item = new RequestItem();
                $item->request_id = $re->id;
                $item->qty = $request->qty[$i];
                $item->product_id = $request->product_id[$i];
                $item->unit_price = $request->price[$i];
                $item->save();
            }

            DB::commit();
     /*   } catch (Exception $exception) {

            DB::rollBack();
        }*/
        return redirect()->back();
    }

    public function show(Request $request)
    {
        return $request;
    }

    public function update(\Illuminate\Http\Request $req, Request $request)
    {
        //
    }

    public function destroy(Request $request)
    {
        $request->delete();
        return \response(null, 204);
    }
}
