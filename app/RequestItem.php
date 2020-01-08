<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @property  double unit_price
 * @property integer product_id
 * @property integer qty
 * @property int request_id
 */
class RequestItem extends Model
{
    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


}
