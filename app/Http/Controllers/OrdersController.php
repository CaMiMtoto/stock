<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Order;
use App\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdersController extends BaseController
{
    public function index()
    {
        $orders = Order::paginate(10);
        $menus = Menu::all();
        return view('admin.orders.index', compact('orders'))->with([
            'menus' => $menus
        ]);
    }

    public function create()
    {
        $menus = Menu::all();
        return view('admin.orders.create', compact('menus'));
    }

    public function edit(Order $order)
    {
        $menus = Menu::all();
        return view('admin.orders.edit', compact('order'))->with([
            'menus' => $menus
        ]);
    }

    public function orderDetails(Order $order)
    {
        return view('admin.orders.order_details', compact('order'));
    }

    public function print(Order $order)
    {
        return view('admin.orders.print', compact('order'));
    }

    private function updateProductQty($menu_id, $qty)
    {
        $menu = Menu::find($menu_id);
        foreach ($menu->menuItems as $item) {
            $item->product->qty -= ($item->qty * $qty);
            $item->product->update();
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $order = new Order();
            $order->customer_name = $request->customer_name;
            $order->waiter = $request->waiter;
            $order->payment_mode = $request->payment_mode;
            $order->amount_paid = $request->amount_paid;
            $order->received = $request->delivered;
            $order->status = $request->payment_status;
            $order->system_date = $this->getSystemDate();
            $order->save();
            for ($i = 0; $i < count($request->menu); $i++) {
                $orderItem = new OrderItem();
                $orderItem->menu_id = $request->menu[$i];
                $orderItem->order_id = $order->id;
                $orderItem->price = $request->rate[$i];
                $orderItem->qty = $request->quantity[$i];
                $orderItem->save();
                $this->updateProductQty($orderItem->menu_id, $orderItem->qty);
            }
            DB::commit();
            return response()->json($order, 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json($exception->getMessage(), 400);
//            return redirect()->back()->with(['error'=>'Please , try again or contact system administrator']);
        }
    }

    public function update(Request $request, Order $order)
    {
        try {
            DB::beginTransaction();
            $order->customer_name = $request->customer_name;
            $order->order_date = $request->order_date;
            $order->waiter = $request->waiter;
            $order->payment_mode = $request->payment_mode;
            $order->amount_paid = $request->amount_paid;
            $order->received = $request->delivered;
            $order->status = $request->payment_status;
            $order->save();
//            OrderItem::where('order_id','=',$order->id)->delete();
            $order->orderItems()->delete();
            for ($i = 0; $i < count($request->menu); $i++) {
                $orderItem = new OrderItem();
                $orderItem->menu_id = $request->menu[$i];
                $orderItem->order_id = $order->id;
                $orderItem->price = $request->rate[$i];
                $orderItem->qty = $request->quantity[$i];
                $orderItem->save();
//                $this->updateProductQty($orderItem->menu_id);
            }
            DB::commit();
            return redirect()->route('orders.index');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with(['error' => $exception->getMessage()]);
        }
    }

}
