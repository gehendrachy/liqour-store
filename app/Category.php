<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function product()
    {
    	return $this->hasMany('App\Product');
    }

    protected $table = 'categories';

    public function categoryInventoryProducts()
    {
    	return $this->hasManyThrough('App\InventoryProduct','App\Product','category_id','product_id');
    }

}
