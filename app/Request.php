<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @property int id
 * @property mixed items
 * @property string prepared_by
 * @property string checked_by
 * @property string department
 * @property mixed date
 * @property int|null approved_by
 * @property string comment
 * @property string status
 */
class Request extends Model
{
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }
    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by', 'id');
    }

    public function requestItems(){
        return $this->hasMany(RequestItem::class);
    }

    public function totalAmount()
    {
        return RequestItem::with('request')
            ->select(DB::raw('SUM(qty*unit_price) as total'))
            ->where('request_id',$this->id)
            ->first()->total;
    }
}
