<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer qty
 * @property double price
 * @property integer menu_id
 * @property mixed order_id
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
        return $this->qty * $this ->price;
    }
}
