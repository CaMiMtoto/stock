<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Order;
use App\OrderItem;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdersController extends BaseController
{
    public function index(Request $request)
    {
        $menus = Menu::all();
        $waiters = User::with('role')->where('role_id', '=', 5)->get();

        if (empty($request->input('q'))) {
            $orders = Order::orderBy("id", "desc")->paginate(10);
        } else {
            $q = $request->input('q');
            $orders = Order::with('waiter')
                ->where('customer_name', 'LIKE', "%{$q}%")
                ->orWhere('created_at', 'LIKE', "%{$q}%")
                ->orderBy("id", "desc")
                ->paginate(10);
            $orders->appends(['q' => $q]);
        }

        return view('admin.orders.index', compact('orders'))
            ->with([
                'menus' => $menus,
                'waiters' => $waiters
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
        $waiters = User::with('role')->where('role_id', '=', 5)->get();
        return view('admin.orders.edit', compact('order'))->with([
            'menus' => $menus,
            'waiters' => $waiters
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
            $newQty = $item->qty * $qty;
            $item->product->qty -= $newQty;
            $item->product->update();
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $order = new Order();
            $order->customer_name = $request->customer_name;
            $order->waiter_id = $request->waiter;
            $order->payment_mode = $request->payment_mode;
            $order->amount_paid = $request->amount_paid;
            $order->received = $request->delivered;
            $order->status = $request->payment_status;
            $order->tax = $request->input('amount_to_pay') * 18.0 / 100.0;
            $order->system_date = $this->getSystemDate();
            $order->save();
            for ($i = 0; $i < count($request->menu); $i++) {
                $orderItem = new OrderItem();
                $menuId = $request->menu[$i];
                $orderItem->menu_id = $menuId;
                $orderItem->order_id = $order->id;
                $orderItem->price = $request->rate[$i];
                $orderItem->qty = $request->quantity[$i];
                $orderItem->cost = Menu::find($menuId)->menuItems->sum('cost');
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
            $order->tax = $request->input('amount_to_pay') * 18.0 / 100.0;
            $order->waiter_id = $request->waiter;
            $order->payment_mode = $request->payment_mode;
            $order->amount_paid = $request->amount_paid;
            $order->received = $request->delivered;
            $order->status = $request->payment_status;
            $order->save();
//            OrderItem::where('order_id','=',$order->id)->delete();
            $order->orderItems()->delete();
            for ($i = 0; $i < count($request->menu); $i++) {
                $orderItem = new OrderItem();
                $menuId = $request->menu[$i];
                $orderItem->menu_id = $menuId;
                $orderItem->order_id = $order->id;
                $orderItem->price = $request->rate[$i];
                $orderItem->qty = $request->quantity[$i];
                $orderItem->cost = Menu::find($menuId)->menuItems->sum('cost');
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
