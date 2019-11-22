<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @property \Illuminate\Support\Carbon order_date
 * @property string waiter
 * @property string customer_name
 * @property mixed id
 * @property boolean payment_mode
 * @property string status
 * @property mixed received
 * @property mixed amount_paid
 * @property mixed order_status
 * @property mixed payment_status
 * @property mixed amount_to_pay
 * @property mixed tax
 */
class Order extends Model
{
    protected $casts = ['system_date' => 'date'];

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
        return $this->totalOrderPrice() - $this->amount_paid;
    }

}
