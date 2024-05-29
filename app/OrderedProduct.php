<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderedProduct extends Model
{
    protected $fillable = [
		    'vendor_id', 'vendor_order_id', 'product_id', 'product_title', 'inventory_product_id', 'product_variation_id', 'variation_name', 'pack', 'quantity', 'sub_total', 'tax_rate', 'bottle_deposit_rate', 'grand_total', 'status'
		];

	protected $hidden = [
        'created_at', 'updated_at'
    ];

 //    public function ordered_products()
	// {
	// 	return $this->hasMany('App\OrderedProduct','vendor_order_id');
	// }
}
