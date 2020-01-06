<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    public function requestItems(){
        return $this->hasMany(RequestItem::class);
    }
}
