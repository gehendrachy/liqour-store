<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryProduct extends Model
{
    protected $fillable = [
		    'user_id', 'product_id', 'product_variation_id', 'display', 'sku', 'barcode', 'stock', 'size', 'case_quantity', 'cost_price', 'retail_price', 'bottle_deposit_type', 'tax_type', 'is_tax_1', 'is_tax_2', 'is_tax_3', 'is_bottle_deposit_1', 'is_bottle_deposit_2', 'is_foodstamp', 'on_hand', 'sold', 'created_by', 'updated_by'
		];

	protected $hidden = [
        'created_at', 'updated_at'
    ];

	public function product_variation()
	{
		return $this->belongsTo('App\ProductVariation','product_variation_id');
	}

	public function product()
	{
		return $this->belongsTo('App\Product','product_id');
	}

	public function store()
	{
		return $this->belongsTo('App\User','user_id');
	}	
}
