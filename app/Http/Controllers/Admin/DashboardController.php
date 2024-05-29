<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
use App\OrderedProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:dashboard-list', ['only' => ['index']]);
    }

    public function index()
    {
        return view('admin.dashboard');
    }

    public function get_packs()
    {
        $ordered_products = OrderedProduct::all();
        foreach ($ordered_products as $ordered_product) {
            $dbOrderedProduct = OrderedProduct::find($ordered_product->id);

            $str = $ordered_product->variation_name;
            preg_match_all('!\d+!', $str, $matches);
            $space_count = count($matches[0]);
            
            if ($space_count == 1) {
                $dbOrderedProduct->pack = 1;
            }else{
                
                $dbOrderedProduct->pack = $matches[0][0];
            }

            $dbOrderedProduct->save();
            
        }
        dd('success');
    }

    public function vendors()
    {
    	$vendors = User::role('Vendor')->get();
        return view('admin.vendors', array('vendors' => $vendors));	
    }

    public function vendor_dashboard($username)
    {
        $checkVendor = User::check_vendor($username);

        if ($checkVendor) {
            $vendor = User::where('username', $username)->first();
            $vendor_details = $vendor->vendor_details;
            return view('admin.vendor-dashboard',compact('vendor','vendor_details'));
        }else{
            return redirect()->back()->with('log_status','Permission Denied!');
        }
        
    }
}
