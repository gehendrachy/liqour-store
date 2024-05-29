<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    protected $fillable = [
		    'user_id', 'address_type', 'name', 'email', 'phone', 'street_address', 'apt_ste_bldg', 'city', 'state', 'zip_code', 'country'
		];

	protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function customer()
	{
		return $this->belongsTo('App\User');
	}	
}
