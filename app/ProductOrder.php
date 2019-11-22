<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed amount_to_pay
 * @property mixed amount_paid
 * @property mixed id
 * @property mixed payment_mode
 * @property mixed tax
 * @property string order_status
 * @property mixed payment_status
 * @property mixed waiter
 * @property mixed customer_name
 */
class ProductOrder extends Model
{
   public function amountDue(){
       return $this->amount_to_pay-$this->amount_paid;
   }

    function productOrderItems(){
        return $this->hasMany(ProductOrderItem::class,'order_id','id');
    }
}
