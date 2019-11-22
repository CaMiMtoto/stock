<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer qty
 * @property double price
 * @property integer menu_id
 * @property mixed order_id
 * @property integer product_id
 */
class OrderItem extends Model
{


    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function subTotal()
    {
        return $this->qty * $this->price;
    }

    public function used($product_id)
    {
        $sum = 0;
        foreach ($this->menu->menuItems->where('product_id', $product_id) as $menuItem) {
            $sum += $menuItem->qty * $this->qty;
        }
        return $sum;
    }

    public function opening($product_id, $inStockQty)
    {
        return $this->used($product_id) + $inStockQty;
    }

    public function available($product_id, $inStockQty,$date)
    {
        return $this->opening($product_id, $inStockQty)+Stock::received($date,$product_id);
    }
}
