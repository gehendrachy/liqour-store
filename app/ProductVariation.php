<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    protected $fillable = [
		    'product_id', 'pack', 'size', 'container', 'image'
		];

	protected $hidden = [
        'created_at', 'updated_at'
    ];


	public function product()
	{
		return $this->belongsTo('App\Product','product_id');
	}

	public function inventory_product()
	{
		return $this->hasMany('App\InventoryProduct', 'product_id');
	}	
	
}