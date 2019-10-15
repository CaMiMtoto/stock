<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $casts = ['start_time' => 'datetime', 'end_time' => 'datetime'];
    protected $fillable = ['end_time', 'start_time', 'user_close'];

    public function openedBy()
    {
        return User::where('id', $this->user_open)->first();
    }

    public function closedBy()
    {
        return User::where('id', $this->user_close)->first();
    }

    public static function getCurrentShift()
    {
        return Shift::whereNull('end_time')->orderBy('id', 'desc')->first();
    }
}
