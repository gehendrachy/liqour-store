<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorOrder extends Model
{
    protected $fillable = [
		    'order_id', 'vendor_id', 'status', 'payment_status', 'payment_method', 'sub_total_exc_tax', 'tax_total', 'sub_total_inc_tax', 'delivery_fee', 'grand_total', 'order_json'
		];

	protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function ordered_products()
	{
		return $this->hasMany('App\OrderedProduct','vendor_order_id');
	}

	public function vendor()
	{
		return $this->belongsTo('App\User','vendor_id');
	}

	public function order()
	{
		return $this->belongsTo('App\Order','order_id');
	}
}
