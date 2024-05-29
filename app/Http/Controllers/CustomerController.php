<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;
use App\Services\ProductPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use App;
use App\User;
use App\CustomerAddress;
use App\Order;
use App\VendorOrder;
use App\OrderedProduct;
use App\Wishlist;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Validator;


class CustomerController extends Controller
{
	use HasRoles;

    public function my_account()
    {
    	$customer = User::where('id',Auth::user()->id)->first();
    	$billing_address = $customer->customer_addresses()->where('address_type', 1)->first();
    	$shipping_address = $customer->customer_addresses()->where('address_type', 2)->first();
    	$orders = $customer->customer_orders()->orderBy('created_at','desc')->limit(4)->get();
    	$wishlists = $customer->wishlists;

    	// dd($wishlists);
    	return view('my-account',compact('customer', 'billing_address', 'shipping_address','orders','wishlists'));
    }

    public function account_settings()
    {
    	$customer = User::where('id', Auth::user()->id)->first();
    	$billing_address = $customer->customer_addresses()->where('address_type', 1)->first();
    	$shipping_address = $customer->customer_addresses()->where('address_type', 2)->first();

    	$db_countries = DB::table('countries')->get();

    	// dd($billing_address);
    	return view('account-settings',compact('customer', 'billing_address', 'shipping_address','db_countries'));
    }

   	public function create_update_information(Request $request)
   	{
   		
   		
   		$validator = Validator::make($request->all(), [
			"name" => 'required|max:255',
			"phone" => 'required',
			"billing_name" => 'required',
			"billing_email" => 'required',
			"billing_phone" => 'required',
			"billing_street_address" => 'required',
			"billing_city" => 'required',
			"billing_zip_code" => 'required',
			"billing_country" => 'required',
			"billing_state" => 'required',
			"shipping_name" => 'required',
			"shipping_email" => 'required',
			"shipping_phone" => 'required',
			"shipping_street_address" => 'required',
			"shipping_city" => 'required',
			"shipping_zip_code" => 'required',
			"shipping_country" => 'required',
			"shipping_state" => 'required',
		]);

		if ($validator->fails()) {
			return redirect()
			->back()
			->withErrors($validator)
			->withInput();
		}

		// dd($_POST);

		$user = User::findOrFail(Auth::user()->id);
		$user->name = $request->name;
		$user->phone = $request->phone;
		$userDetailsSaved = $user->save();

		if ($userDetailsSaved) {
			
		

			$billing_details = array("name" => $request->billing_name,
									"email" => $request->billing_email,
									"phone" => $request->billing_phone,
									"street_address" => $request->billing_street_address,
									"apt_ste_bldg" => $request->billing_apt_ste_bldg,
									"city" => $request->billing_city,
									"zip_code" => $request->billing_zip_code,
									"country" => $request->billing_country,
									"state" => $request->billing_state
								);

			$shipping_details = array("name" => $request->shipping_name,
									"email" => $request->shipping_email,
									"phone" => $request->shipping_phone,
									"street_address" => $request->shipping_street_address,
									"apt_ste_bldg" => $request->shipping_apt_ste_bldg,
									"city" => $request->shipping_city,
									"zip_code" => $request->shipping_zip_code,
									"country" => $request->shipping_country,
									"state" => $request->shipping_state
								);

			$billingDetailsSaved = $user->customer_addresses()
								->updateOrCreate(['address_type' => 1], $billing_details);

			$shippingDetailsSaved = $user->customer_addresses()
								->updateOrCreate(['address_type' => 2], $shipping_details);

			if ($billingDetailsSaved && $shippingDetailsSaved) {
				return redirect()->route('customer.my-account')->with('success_status','Your Account Information has been updated Succcessfully!');
			}
		}

   	}

   	public function orders()
   	{
   		$customer = User::where('id',Auth::user()->id)->first();
    	$orders = $customer->customer_orders()->orderBy('created_at','desc')->paginate(15);
    	
    	return view('orders',compact('customer','orders'));
   	}

   	public function view_order($order_no)
   	{
   		$order = Order::where([['customer_id', Auth::user()->id],['order_no', base64_decode($order_no)]])->first();
   		
   		if (!$order) {
   			return redirect()->back()->with('error','Order Detail Not Found.');
   		}
   		$billing_details = json_decode($order->billing_details);
   		$shipping_details = json_decode($order->shipping_details);
   		$vendor_orders = $order->vendor_orders->all();

   		// foreach ($vendor_orders as $vendor_id => $vendor_order) {
   		// 	dd($vendor_order->vendor->vendor_details);
   		// }
   		// dd($vendor_orders);

   		return view('order-details',compact('order','billing_details','shipping_details','vendor_orders'));

   		// dd();
   	}


   	public function wishlist()
   	{
   		$customer = User::where('id', Auth::user()->id)->first();
    	$wishlists = $customer->wishlists;
    	
    	return view('wishlists',compact('customer','wishlists'));
   	}

   	public function add_to_wishlist(Request $request)
   	{
   		
   		if (!Auth::check()){
   			
   			$data = array('status' => 'login-error');
   			
   			echo json_encode($data);

   			exit();
   		}
   		

		if (Auth::user()->hasRole(['Customer'])) {

	   		if ($request->product_id) {

	            $product_id = $request->product_id;

	            $productExists = Wishlist::where([['customer_id',Auth::user()->id],['product_id', $product_id]])->first();

	            if ($productExists) {

	            	$productExists = Wishlist::where([['customer_id', Auth::user()->id],['product_id',$product_id]])->delete();

	            	$data = array('status'=> 'exist');

	            	echo json_encode($data);
	            	exit();

	            }else{

	            	$wishlist_product = Wishlist::create(['customer_id' => Auth::user()->id,'product_id' => $product_id]);

	            	$data = array('status'=> 'success');

	            	echo json_encode($data);
	            	exit();
	            }
	        }else{

	        	$data = array('status'=> 'error');

	        	echo json_encode($data);
	        	exit();
	        }
	    }else{
	    	$data = array('status'=> 'not-a-customer');
			
   			echo json_encode($data);
   			exit();
	    }
   		
   	}

}
