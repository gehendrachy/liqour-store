<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesReport extends Model
{
    protected $fillable = [
    	'vendor_id', 'report_date', 'vendor_name', 'vendor_code', 'total_sales', 'return_refund', 'total_net_sales', 'commission', 'commission_percentage', 'total_payment_to_vendor', 'status', 'created_by', 'updated_by'
    ];

    public function vendor()
    {
    	return $this->belongsTo('App\User','vendor_id');
    }

    public function payment_reports()
    {
    	return $this->hasMany('App\PaymentReport','sales_report_id');
    }
    
}
