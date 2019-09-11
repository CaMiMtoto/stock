<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed cost
 * @property mixed measure
 * @property mixed qty
 * @property mixed product_id
 * @property mixed menu_id
 */
class MenuItem extends Model
{
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
