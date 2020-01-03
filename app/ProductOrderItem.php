<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer qty
 * @property float price
 * @property mixed order_id
 * @property integer product_id
 * @property mixed product
 * @property double cost
 */
class ProductOrderItem extends Model
{

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
