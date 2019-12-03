<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float|int sold
 * @property int closing
 * @property int opening
 * @property integer  shift_id
 * @property int|null user_id
 * @property integer received
 * @property integer product_id
 */
class StockTransaction extends Model
{
    public function product(){
        return $this->belongsTo(Product::class);
    }
}
