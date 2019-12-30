<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductOrder;
use App\ProductOrderItem;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductOrderController extends Controller
{
    public function index()
    {
        $waiters = User::with('role')->where('role_id', '=', 5)->get();
        $products = Product::with('category')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->where('categories.name', '!=', 'Food')
            ->select('products.*')
            ->get();
        return view('admin.orders.product_orders', compact('products'))
            ->with(['waiters' => $waiters]);
    }

    public function all(Request $request)
    {
        $columns = array(
            0 => 'created_at',
            1 => 'customer_name',
            2 => 'waiter',
            3 => 'amount_to_pay',
            4 => 'amount_paid',
            6 => 'tax',
            7 => 'payment_status',
            8 => 'payment_mode',
        );

        $totalData = ProductOrder::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
//        $order='created_at';
//        $dir='desc';
        if (empty($request->input('search.value'))) {
            $orders = ProductOrder::with('waiter')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $orders = ProductOrder::with('waiter')->where('customer_name', 'LIKE', "%{$search}%")
                ->orWhere('waiter.name', 'LIKE', "%{$search}%")
                ->orWhere('created_at', 'LIKE', "%{$search}%")
                ->orWhere('payment_status', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = ProductOrder::with('waiter')
                ->where('customer_name', 'LIKE', "%{$search}%")
                ->orWhere('waiter.name', 'LIKE', "%{$search}%")
                ->orWhere('created_at', 'LIKE', "%{$search}%")
                ->orWhere('payment_status', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        if (!empty($orders)) {
            foreach ($orders as $order) {
                $nestedData['id'] = $order->id;
                $nestedData['customer_name'] = $order->customer_name;
                $nestedData['waiter'] = $order->waiter;
                $nestedData['payment_status'] = $order->payment_status;
                $nestedData['payment_mode'] = $order->payment_mode;
                $nestedData['order_status'] = $order->order_status;
                $nestedData['tax'] = $order->tax;
                $nestedData['amount_paid'] = $order->amount_paid;
                $nestedData['amount_to_pay'] = $order->amount_to_pay;
                $nestedData['amount_due'] = $order->amount_to_pay - $order->amount_paid;
                $nestedData['created_at'] = date('j M Y h:i a', strtotime($order->created_at));
                $data[] = $nestedData;

            }
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
//        echo json_encode($json_data);
        return \response()->json($json_data, 200);
    }


    public function printOrder($id)
    {
        $obj = ProductOrder::with("orderItems")->find($id);
        if (!$obj) {
            return \response()->json(["message" => "Not found"], 404);
        }
        return view("admins.printOrder", ['order' => $obj]);
    }


    public function edit(ProductOrder $order)
    {
        $waiters = User::with('role')->where('role_id', '=', 5)->get();
        $products = Product::all();
        $order = $order->load('productOrderItems');
        return view('admin.editOrder', compact('order'))
            ->with(['products' => $products, 'waiters' => $waiters]);
    }

    public function show(ProductOrder $order)
    {
        return response($order, 200);
    }

    public function details(ProductOrder $order)
    {
        $order = $order->load('productOrderItems');
        return view('admin.orderDetails', compact('order'));
    }

    public function mark(Request $request)
    {
        $obj = ProductOrder::find($request->input('id'));
        if (!$obj) {
            return \response()->json(["message" => "Not found"], 404);
        }
        $obj->payment_status = $request->input('payment_status');
//        $obj->order_status = $request->input('order_status');
        $obj->update();
        return \response()->json(null, 204);
    }


    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $order = new ProductOrder();
            $order->customer_name = $request->customer_name;
            $order->waiter_id = $request->waiter;
            $order->payment_status = $request->payment_status;
            $order->amount_paid = $request->amount_paid;
            $order->amount_to_pay = $request->amount_to_pay;
            $order->order_status = 'Pending';
            $order->tax = $request->tax;
            $order->payment_mode = $request->payment_mode;
            $order->save();
            for ($i = 0; $i < count($request->product); $i++) {
                $orderItem = new ProductOrderItem();
                $orderItem->product_id = $request->product[$i];
                $orderItem->order_id = $order->id;
                $orderItem->price = $request->rate[$i];
                $orderItem->qty = $request->quantity[$i];
                $orderItem->save();

                $orderItem->product->qty -= $orderItem->qty;
                $orderItem->product->update();
            }
            DB::commit();
            return response()->json($order, 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json($exception->getMessage(), 400);
        }
    }

    public function update(Request $request, ProductOrder $order)
    {
        try {
            DB::beginTransaction();
            $order->customer_name = $request->customer_name;
            $order->waiter_id = $request->waiter;
            $order->payment_status = $request->payment_status;
            $order->amount_paid = $request->amount_paid;
            $order->amount_to_pay = $request->amount_to_pay;
            $order->order_status = 'Pending';
            $order->tax = $request->tax;
            $order->payment_mode = $request->payment_mode;
            $order->update();
            $order->productOrderItems()->delete();
            for ($i = 0; $i < count($request->product); $i++) {
                $orderItem = new ProductOrderItem();
                $orderItem->product_id = $request->product[$i];
                $orderItem->order_id = $order->id;
                $orderItem->price = $request->rate[$i];
                $orderItem->qty = $request->quantity[$i];
                $orderItem->save();
//
//                $orderItem->product->qty -= $orderItem->qty;
//                $orderItem->product->update();
            }
            DB::commit();
            return redirect()->route('productOrders.index');
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json($exception->getMessage(), 400);
        }
    }

}
