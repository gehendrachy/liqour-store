<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentReport extends Model
{
    protected $fillable = [
    	'payment_id', 'sales_report_id', 'vendor_id', 'vendor_name', 'vendor_code', 'total_amount', 'return_refund', 'adjustment', 'commission', 'net_amount', 'paid_amount', 'due_amount', 'status', 'paid_by', 'created_by', 'updated_by'
    ];

    public function sales_report()
    {
    	return $this->belongsTo('App\SalesReport','sales_report_id');
    }

    public function vendor()
    {
    	return $this->belongsTo('App\User','vendor_id');
    }
}
