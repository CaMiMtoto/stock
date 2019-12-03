<?php

namespace App\Http\Controllers;

use App\OrderItem;
use App\ProductOrderItem;
use App\Shift;
use App\Stock;
use App\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShiftController extends BaseController
{
    public function index()
    {
        $todayShift = Shift::whereNull('end_time')->orderBy('id', 'desc')->first();
        $shifts = Shift::orderBy('id', 'desc')->paginate(10);
        return view('admin.shifts', compact('shifts'))
            ->with(['todayShift' => $todayShift]);
    }


    public function store(Request $request)
    {

        if ($request->shift == 'on') {
            $shift = new Shift();
            $shift->start_time = now();
            $shift->user_open = Auth::id();
            $shift->save();

        } else {
            $shift = Shift::whereNull('end_time')->orderBy('id', 'desc')->first();
            $interval = 7;
            if ($shift->start_time->addMilliseconds($interval) < now()) {
                DB::beginTransaction();
                $shift->end_time = now();
                $shift->user_close = Auth::id();
                $shift->update();
                $this->saveStockTransaction($shift);
                DB::commit();
                return redirect()->route('shifts');
            }
            return redirect()
                ->route('shifts')
                ->with(['error' => "You cannot close shift before $interval hours"]);
        }
        return redirect()->route('shifts');
    }

    private function saveStockTransaction($shift)
    {
        $this->saveFoodTransactions($shift);
        $this->saveDrinkTransactions($shift);
    }

    private function saveFoodTransactions($shift){
        $orderItems = OrderItem::with('menu')
            ->whereDate('created_at', now()->toDate())
            ->get();
        foreach ($orderItems as $orderItem) {
            foreach ($orderItem->menu->menuItems as $menuItem) {
                $qtySold = $menuItem->qty * $orderItem->qty;
//                $stockTrans = StockTransaction::with('product')
//                    ->whereDate('created_at', '<', now()->toDate())
//                    ->where([
//                        ['product_id', '=', $menuItem->product_id],
//                    ])->first();

                $tran = new StockTransaction();
                $tran->product_id = $menuItem->product_id;
                $prevTrans = StockTransaction::with('product')
                    ->whereDate('created_at', '<', now()->toDate())
                    ->where([
                        ['product_id', '=', $menuItem->product_id],
                    ])->first();
                $received = Stock::with('product')
                    ->whereDate('created_at', '=', now()->toDate())
                    ->where('product_id', '=', $menuItem->product_id)->sum('qty');
                $tran->received = $received;
                $tran->user_id = Auth::id();
                $tran->shift_id = $shift->id;
                if ($prevTrans == null) {
                    $tran->opening = 0;
                } else {
                    $tran->opening = $prevTrans->closing;
                }
                $tran->closing = $tran->opening + $received - $qtySold;
                $tran->sold = $qtySold;
                $tran->save();


            }
        }
    }

    private function saveDrinkTransactions($shift)
    {
        $orderItems = ProductOrderItem::with('product')
            ->whereDate('created_at', now()->toDate())
            ->get();
        foreach ($orderItems as $orderItem) {
                $qtySold = $orderItem->qty;
                $tran = new StockTransaction();
                $tran->product_id = $orderItem->product_id;
                $prevTrans = StockTransaction::with('product')
                    ->whereDate('created_at', '<', now()->toDate())
                    ->where([
                        ['product_id', '=', $orderItem->product_id],
                    ])->first();
                $received = Stock::with('product')
                    ->whereDate('created_at', '=', now()->toDate())
                    ->where('product_id', '=', $orderItem->product_id)->sum('qty');
                $tran->received = $received;
                $tran->user_id = Auth::id();
                $tran->shift_id = $shift->id;
                if ($prevTrans == null) {
                    $tran->opening = 0;
                } else {
                    $tran->opening = $prevTrans->closing;
                }
                $tran->closing = $tran->opening + $received - $qtySold;
                $tran->sold = $qtySold;
                $tran->save();
        }
    }
}
