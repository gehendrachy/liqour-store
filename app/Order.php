<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
		    'order_no', 'customer_id', 'customer_name', 'customer_email', 'customer_phone', 'billing_details', 'shipping_details', 'status', 'total_price', 'payment_status', 'payment_method', 'delivery_method', 'order_json', 'message'
		];

	protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function vendor_orders()
    {
    	return $this->hasMany('App\VendorOrder','order_id');
    }

    public function vendor_ordered_products()
    {
    	return $this->hasManyThrough('App\OrderedProduct','App\VendorOrder','order_id','vendor_order_id');
    }

    /*public function customer()
	{
		return $this->belongsTo('App\User');
	}*/
}
