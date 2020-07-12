<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\Requisition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequisitionsController extends Controller
{
    public function index()
    {
        $requisitions = Requisition::with('product')
            ->latest()
            ->paginate(10);
        $categories = Category::all();
        return view('admin.requisitions', ['requisitions' => $requisitions, 'categories' => $categories]);
    }

    public function requests()
    {
        $requisitions = Requisition::with('product')
            ->orderBy('status')
            ->paginate(10);
        $categories = Category::all();
        return view('admin.requested', ['requisitions' => $requisitions, 'categories' => $categories]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|numeric',
            'qty' => 'required|min:1|numeric',
        ]);

        if ($request->id && $request->id > 0) {
            $req = Requisition::find($request->id);
            if ($req) {
                $req->product_id = $request->product_id;
                $req->qty = $request->qty;
                $req->reason = $request->reason;
                $req->status = 'pending';
                $req->update();
            }
        } else {
            $req = new Requisition();
            $req->product_id = $request->product_id;
            $req->qty = $request->qty;
            $req->reason = $request->reason;
            $req->save();
        }
        return response()->json($req, 200);
    }

    public function show(Requisition $requisition)
    {
        $requisition->load('product');
        return response()->json($requisition, 200);
    }


    public function update(Request $request, Requisition $requisition)
    {
        $request->validate([
            'status' => 'required',
            'qty' => 'required|min:1|numeric',
        ]);

        $requisition->status = $request->status;
        $requisition->comment = $request->comment;
        $requisition->qty = $request->qty;
        $requisition->update();
        return response()->json($requisition, 200);
    }

    public function destroy(Requisition $requisition)
    {
        $requisition->delete();
        return response()->json(null, 204);
    }

    public function confirm(Requisition $requisition)
    {
        try {
            DB::beginTransaction();
            $requisition->status = 'confirmed';
            $requisition->update();

            $requisition->product->qty -= $requisition->qty;
            $requisition->product->update();
            DB::commit();
            return response()->json($requisition, 200);
        } catch (\Exception $exception) {
            DB::rollBack();
        }
        return response()->json($exception->getMessage(), 400);
    }
}
