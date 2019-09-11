<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Order::paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        return view('admin.orders.create');
    }
    public function orderDetails(Order $order)
    {
        return view('admin.orders.order_details',compact('order'));
    }

    public function print(Order $order)
    {
        return view('admin.orders.print',compact('order'));
    }

}
