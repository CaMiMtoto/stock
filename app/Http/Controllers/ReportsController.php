<?php

namespace App\Http\Controllers;

use App\Expense;
use App\Order;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index()
    {
        return view('admin.reports.reports');
    }

    public function expensesReports(Request $request)
    {
        $startDate = $request->start_date;
        $sendDate = $request->end_date;
        $expenses = Expense::whereBetween('date', [$startDate, $sendDate])->get();
        return view('admin.reports.expensesReports', compact('expenses'))->with([
            'start_date' => $startDate,
            'end_date' => $sendDate,
        ]);
    }

    public function salesReports(Request $request)
    {
        $startDate = $request->start_date;
        $sendDate = $request->end_date;
        $sales = Order::with('orderItems')->whereBetween('order_date', [$startDate, $sendDate])->get();
        return view('admin.reports.salesReports', compact('sales'))->with([
            'start_date' => $startDate,
            'end_date' => $sendDate,
        ]);
    }
}
