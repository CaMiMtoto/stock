<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed id
 * @property mixed items
 * @property mixed prepared_by
 * @property mixed department
 * @property mixed date
 */
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
