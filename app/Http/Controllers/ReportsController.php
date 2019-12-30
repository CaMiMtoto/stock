<?php

namespace App\Http\Controllers;

use App\Expense;
use App\OrderItem;
use App\ProductOrderItem;
use App\StockTransaction;
use http\Env\Response;
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
            $category='Food';
            $orderItems = StockTransaction::with('product')
                ->join('products','products.id','=','stock_transactions.product_id')
                ->whereDate('stock_transactions.created_at', $date)
                ->where('products.category_id','=',2)
                ->whereDate('stock_transactions.created_at','=',$date)
                ->groupBy('products.id')
                ->select('*')
                ->get();
//            return \response($orderItems,200);
        }else{
            $category='Drinks';
            $orderItems = StockTransaction::with('product')
                ->join('products','products.id','=','stock_transactions.product_id')
                ->whereDate('stock_transactions.created_at', $date)
                ->where('products.category_id','=',1)
                ->whereDate('stock_transactions.created_at','=',$date)
                ->groupBy('products.id')
                ->select('*')
                ->get();
        }
        return view('admin.reports.product_history', compact('orderItems'))
            ->with([
                'date' => $date,
                'category'=>$category
            ]);

    }

    public function salesReports(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $sql = 'select i.created_at, m.name as name, sum(i.qty) as quantity, i.price as price, (sum(i.qty) * i.price) as Total from order_items as i inner join menus as m on i.menu_id = m.id where DATE(i.created_at) between "' . $startDate . '" and "' . $endDate . '"group by i.menu_id';

        $sales = OrderItem::with(['menu','order'])
            ->join('menus','menus.id','=','order_items.menu_id')
//            ->whereBetween('order_items.created_at',[$startDate,$endDate])
            ->whereDate('order_items.created_at','>=',$startDate)
            ->whereDate('order_items.created_at','<=',$endDate)
            ->select('*','menus.id', DB::raw('sum(order_items.qty) as quantity'), DB::raw(' (sum(order_items.qty) * order_items.price) as total'))
            ->groupBy('menus.id')
            ->get();
//        return \response($sales,200);
        return view('admin.reports.salesReports',compact('sales'))->with([
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }
}
