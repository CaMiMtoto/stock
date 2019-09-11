<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed qty
 * @property mixed product_id
 * @property mixed comment
 * @property mixed status
 * @property mixed reason
 * @property mixed product
 */
class Requisition extends Model
{
   public function product(){
       return $this->belongsTo(Product::class);
   }
}
