<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

/**
 * @property mixed opening
 * @property Date system_date
 * @property integer product_id
 * @property integer closing
 */
class Movement extends Model
{
    protected $casts = [
        'system_date' => 'date'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function available()
    {
        return $this->received() + $this->opening;
    }

    public function received()
    {
        return Stock::with('product')->where('product_id', $this->product_id)->sum('qty');
    }

    public function used($date)
    {
        $orders = Order::with('orderItems')->where('system_date', $date)->get();
        $sum =0;
        foreach ($orders as $order) {
            foreach ($order->orderItems as $item) {
                foreach ($item->menu->menuItems->where('product_id',$this->product_id) as $menuItem) {
                    $sum += $menuItem->qty*$item->qty;
                }
            }
        }
        return $sum;
    }
}
