<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed expiry_date
 * @property mixed price
 * @property mixed qty
 * @property mixed product_id
 * @property mixed supplier_id
 */
class Stock extends Model
{

    public function supplier()
    {
        return $this->belongsTo(Supplier::Class);
    }

    public function product()
    {
        return $this->belongsTo(Product::Class);
    }

    public static function received($date,$productId)
    {
        return Stock::where('product_id', $productId)
            ->whereDate('updated_at', $date)
            ->sum('qty');
    }
}
