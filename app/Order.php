<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @property \Illuminate\Support\Carbon order_date
 * @property string waiter
 * @property string customer_name
 * @property mixed id
 * @property mixed amout_paid
 * @property mixed payment_mode
 */
class Order extends Model
{
    protected $casts = ['order_date'];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function totalOrderPrice()
    {
        return DB::table("order_items")
            ->select(DB::raw('SUM( price * qty ) as Total'))
            ->where('order_id', '=', $this->id)
            ->first()
            ->Total;
    }

    public function amountDue()
    {
        return $this->totalOrderPrice()- $this->amount_paid;
    }

}
