<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'status', 'phone', 'address', 'gender', 'city', 'region', 'country', 'wishlist','username'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function inventory_products()
    {
        return $this->hasMany('App\InventoryProduct','user_id');
    }

    public function vendor_details()
    {
        return $this->hasOne('App\Vendor','user_id');
    }

    public function customer_addresses()
    {
        return $this->hasMany('App\CustomerAddress');
    }

    public function customer_orders()
    {
        return $this->hasMany('App\Order', 'customer_id');
    }

    public function vendor_orders()
    {
        return $this->hasMany('App\VendorOrder', 'vendor_id');
    }

    public function wishlists()
    {
        return $this->hasMany('App\Wishlist', 'customer_id');
    }

    public static function check_vendor($username)
    {
        $vendor = Self::where('username', $username)->first();
        // dd($vendor->hasRole(['Vendor']));

        if (Auth::user()->hasRole(['Super Admin']) || Auth::user()->id == $vendor->id) {
            session()->put('vendorID', $vendor->id);
            session()->put('username', $username);
            return $vendor;
            
        }else{
            session()->put('vendorID', 0);
            return false;
        }
    }
}
