<?php

namespace App\Http\Controllers;

use App\Expense;
use App\OrderItem;
use App\ProductOrderItem;
use App\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function productsHistory(Request $request)
    {
        $date = $request->date;
        if ($request->category == 'Food') {
            $orderItems = StockTransaction::with('product')->whereDate('created_at', $date)
                ->get();
            return view('admin.reports.product_history', compact('orderItems'))
                ->with([
                    'date' => $date
                ]);
        }
        $orderItems = StockTransaction::with('product')
            ->join('products','products.id','=','stock_transactions.product_id')
            ->whereDate('stock_transactions.created_at', $date)
            ->where('products.category_id','=',1)
            ->select('*')
            ->get();
//        return response($orderItems,200);
        return view('admin.reports.product_history_drinks', compact('orderItems'))
            ->with([
                'date' => $date
            ]);

    }

    public function salesReports(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $sales = OrderItem::with('menu')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
        return view('admin.reports.salesReports')->with([
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }
}
