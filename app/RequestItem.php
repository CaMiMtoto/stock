<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestItem extends Model
{
    public function request(){
        return $this->belongsTo(Request::class);
    }
}
