<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed email
 * @property mixed phone_number
 * @property mixed address
 * @property mixed name
 */
class Supplier extends Model
{
    public function  stocks(){
        return $this->hasMany(Stock::class);
    }
}
