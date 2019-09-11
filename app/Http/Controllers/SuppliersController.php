<?php

namespace App\Http\Controllers;

use App\Supplier;
use Illuminate\Http\Request;

class SuppliersController extends Controller
{

    public function index(Request $request)
    {
        if (empty($request->input('q'))) {
            $suppliers = Supplier::orderBy('id', 'desc')->paginate(10);
        } else {
            $q = $request->input('q');
            $suppliers = Supplier::where('name', 'LIKE', "%{$q}%")
                ->orWhere('email', 'LIKE', "%{$q}%")
                ->orWhere('phone_number', 'LIKE', "%{$q}%")
                ->orWhere('address', 'LIKE', "%{$q}%")
                ->orderBy("id", "desc")
                ->paginate(10);
            $suppliers->appends(['q' => $q]);
        }
        return view('admin.suppliers', compact('suppliers'));
    }


    public function store(Request $request)
    {
        if ($request->id && $request->id > 0) {
            $sup = Supplier::find($request->id);
            if ($sup) {
                $sup->name = $request->name;
                $sup->address = $request->address;
                $sup->phone_number = $request->phone_number;
                $sup->email = $request->email;
                $sup->update();
            }
        } else {
            $sup = new Supplier();
            $sup->name = $request->name;
            $sup->address = $request->address;
            $sup->phone_number = $request->phone_number;
            $sup->email = $request->email;
            $sup->save();
        }
        return response()->json($sup, 200);
    }


    public function show(Supplier $supplier)
    {
        return response()->json($supplier, 200);
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return response()->json(null, 204);
    }
}
