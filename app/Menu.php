<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed id
 * @property mixed price
 * @property mixed name
 */
class Menu extends Model
{
    protected $guarded = [];
    protected $appends = ['formattedPrice'];

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price);
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
