<?php

namespace App\Http\Controllers;

use App\Expense;
use App\FinancialReportModel;
use App\Order;
use App\OrderItem;
use App\ProductOrder;
use App\ProductOrderItem;
use App\RequestItem;
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
            $category = 'Food';
            $orderItems = StockTransaction::with('product')
                ->join('products', 'products.id', '=', 'stock_transactions.product_id')
                ->whereDate('stock_transactions.created_at', $date)
                ->where('products.category_id', '=', 2)
                ->whereDate('stock_transactions.created_at', '=', $date)
                ->groupBy('products.id')
                ->select('*')
                ->get();
//            return \response($orderItems,200);
        } else {
            $category = 'Drinks';
            $orderItems = StockTransaction::with('product')
                ->join('products', 'products.id', '=', 'stock_transactions.product_id')
                ->whereDate('stock_transactions.created_at', $date)
                ->where('products.category_id', '=', 1)
                ->whereDate('stock_transactions.created_at', '=', $date)
                ->groupBy('products.id')
                ->select('*')
                ->get();
        }
        return view('admin.reports.product_history', compact('orderItems'))
            ->with([
                'date' => $date,
                'category' => $category
            ]);

    }

    public function salesReports(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $sales = OrderItem::with(['menu', 'order'])
            ->join('menus', 'menus.id', '=', 'order_items.menu_id')
            ->whereDate('order_items.created_at', '>=', $startDate)
            ->whereDate('order_items.created_at', '<=', $endDate)
            ->select('*', 'menus.id', DB::raw('sum(order_items.qty) as quantity'), DB::raw(' (sum(order_items.qty) * order_items.price) as total'))
            ->groupBy('menus.id')
            ->get();
        return view('admin.reports.salesReports', compact('sales'))->with([
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }

    public function financialReports(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $foodTotalCost = OrderItem::with(['menu'])
            ->join('menus', 'menus.id', '=', 'order_items.menu_id')
            ->whereDate('order_items.created_at', '>=', $startDate)
            ->whereDate('order_items.created_at', '<=', $endDate)
            ->select(DB::raw('sum(order_items.cost * order_items.qty) as totalCost'))
            ->first()->totalCost;

        $foodTotalSales = OrderItem::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->select(DB::raw('sum(price * qty) as totalCost'))
            ->first()->totalCost;

        $foodTotalTax = Order::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->select(DB::raw('sum(tax) as tax'))
            ->first()->tax;

        $drinkTotalTax = ProductOrder::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->select(DB::raw('sum(tax) as tax'))
            ->first()->tax;

        $drinkTotalSales = ProductOrderItem::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->select(DB::raw('sum(price * qty) as totalCost'))
            ->first()->totalCost;

        $drinkTotalCost = ProductOrderItem::with(['product'])
            ->join('products', 'products.id', '=', 'product_order_items.product_id')
            ->whereDate('product_order_items.created_at', '>=', $startDate)
            ->whereDate('product_order_items.created_at', '<=', $endDate)
            ->select(DB::raw('sum(product_order_items.cost * product_order_items.qty) as totalCost'))
            ->first()->totalCost;

        $totalCost = $foodTotalCost + $drinkTotalCost;
        $totalSales = $foodTotalSales + $drinkTotalSales;
        $totalTax = $drinkTotalTax + $foodTotalTax;
        $profit = $totalSales - $totalCost;

        $fn = new FinancialReportModel();
        $fn->totalCost = $totalCost;
        $fn->totalSales = $totalSales;
        $fn->totalTax = $totalTax;
        $fn->profit = $profit;

        return view('admin.reports.financialReports', compact('fn'))->with([
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }


    public function requestedItems(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;

        $items = RequestItem::with('product')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get();
        $items = RequestItem::with(['product'])
            ->join('products', 'products.id', '=', 'request_items.product_id')
            ->whereDate('request_items.created_at', '>=', $startDate)
            ->whereDate('request_items.created_at', '<=', $endDate)
            ->select('request_items.product_id','request_items.created_at','request_items.qty as quantity',DB::raw('sum(request_items.unit_price * request_items.qty) as total'),DB::raw('sum(request_items.qty) as totalQty'),DB::raw('sum(request_items.unit_price) as totalPrice'))
            ->groupBy('products.id')
            ->get();
//        return $items;
        return view('admin.reports.requested_items', compact('items'))
            ->with([
                'startDate' => $startDate,
                'endDate' => $endDate
            ]);
    }
}
