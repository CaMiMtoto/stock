<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed name
 */
class Category extends Model
{
    protected $fillable = ['name'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst(strtolower($value));
    }

    public static $FOOD = 2;
    public static $DRINK = 1;
}
