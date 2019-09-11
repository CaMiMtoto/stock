<?php

namespace App\Http\Controllers;

use App\Expense;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $expenses = Expense::paginate(10);
        return view('admin.expenses', compact('expenses'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'description' => 'required'
        ]);

        $id = $request->id;
        if ($id > 0) {
            $expense = Expense::find($id);
        } else {
            $expense = new Expense();
        }
        $expense->date = $request->date;
        $expense->amount = $request->amount;
        $expense->description = $request->description;
        $expense->save();
        return \response()->json($expense, 201);
    }

    public function show(Expense $expense)
    {
        return \response()->json($expense, 200);
    }


    public function destroy(Expense $expense)
    {
        try {
            $expense->delete();
        } catch (\Exception $e) {
            return \response()->json($e->getMessage(), 400);
        }
        return \response()->json(null, 204);
    }
}
