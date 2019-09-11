<?php
/**
 * Created by PhpStorm.
 * User: CaMi
 * Date: 8/29/2019
 * Time: 11:37 AM
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Menu;
use App\Order;
use App\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    private function updateProductQty($menu_id)
    {
        $menu = Menu::find($menu_id);
        foreach ($menu->menuItems as $item) {
            $item->product->qty -= $item->qty;
            $item->product->update();
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $order = new Order();
            $order->customer_name = $request->customerName;
            $order->order_date = $request->orderDate;
            $order->waiter = $request->waiter;
            $order->save();

            foreach ($request->orders as $item) {
                $orderItem = new OrderItem();
                $orderItem->menu_id = $item['menu']['id'];
                $orderItem->order_id = $order->id;
                $orderItem->price = $item['menu']['price'];
                $orderItem->qty = $item['qty'];
                $orderItem->save();
                $this->updateProductQty($orderItem->menu_id);
            }
            DB::commit();
            return response()->json($order, 201);
        } catch (\Exception $exception) {
            DB::rollBack();
            return \response()->json($exception->getMessage(), 400);
        }
    }
}