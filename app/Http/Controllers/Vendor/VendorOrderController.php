<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;
use Validator;
use App;
use App\User;
use App\Vendor;
use App\Category;
use App\Order;
use App\VendorOrder;
use App\OrderedProduct;

class VendorOrderController extends Controller
{
    public function __construct()
	{
		$this->middleware('role:Vendor|Super Admin');
	}

	public function index($username)
	{
		$checkVendor = User::check_vendor($username);

		if ($checkVendor) {

			$vendor = User::where('username',$username)->firstOrFail();
			$vendor_orders = $vendor->vendor_orders()->orderBy('created_at','desc')->get();
			// foreach ($vendor_orders as $key => $vendor_order) {
			// 	echo $vendor_order->order->order_no.'<br>';
			// }
			// dd($vendor_orders);

			return view('admin.vendor-orders', array('vendor' => $vendor, 'vendor_orders' => $vendor_orders, 'username' => $username));
		}else{
			return redirect()->back()->with('log_status','Permission Denied!');
		}
	}

	public function view_order($username, $vendor_order_id)
	{
		$checkVendor = User::check_vendor($username);

		if ($checkVendor) {

			$vendor = User::where('username', $username)->firstOrFail();

			$vendor_order = VendorOrder::where([['vendor_id', $vendor->id],['id', $vendor_order_id]])->first();
			
			if (isset($vendor_order)) {
				
				$billing_details = json_decode($vendor_order->order->billing_details);
				$shipping_details = json_decode($vendor_order->order->shipping_details);

				return view('admin.vendor-order-details', compact('vendor', 'vendor_order', 'billing_details', 'shipping_details', 'username'));
			}else{
				return redirect()->back()->with('log_status','Unauthorized Access!');
			}
			

			// dd($vendor_order->order->);
			

			
		}else{
			return redirect()->back()->with('log_status','Permission Denied!');
		}

	}

	public function change_ordered_product_status(Request $request)
	{
		$ordered_product = OrderedProduct::where('id', $request->id)->first();
		if ($ordered_product) {

			$ordered_product->status = $request->status;
			$statusChanged = $ordered_product->save();

			if ($statusChanged) {
				
				$vendor_order = VendorOrder::where('id', $ordered_product->vendor_order_id)->first();

				$same_vendor_order_ordered_products = $vendor_order->ordered_products()->where('status', '!=', $request->status)->get();

				if ($same_vendor_order_ordered_products->count() == 0) {
					$vendor_order->status = $request->status;
					$vendor_order->save();
				}

				$data = array('status'=> 'success');
			}else{

				$data = array('status'=> 'error');
			}

		}else{
			$data = array('status'=> 'error');
		}

		echo json_encode($data);

	}

	public function change_vendor_orders_status($username, $vendor_order_id, $status)
	{
		$checkVendor = User::check_vendor($username);
		// dd($checkVendor);

		if ($checkVendor) {
			$vendor_order = VendorOrder::where([['id', $vendor_order_id],['vendor_id',$checkVendor->id]])->first();
			if (isset($vendor_order)) {

				$vendor_order->status = $status;
				$vendor_order->save();

				$ordered_products = $vendor_order->ordered_products()->where([['vendor_order_id',$vendor_order_id],['status', '<', 3]])->update(['status' => $status]);
			}else{
				return redirect()->back()->with('log_status','Unauthorized Access!');
			}
			

			return redirect()->back()->with('success','Status updated Successfully');
		}else{
			return redirect()->back()->with('log_status','Permission Denied!');
		}
		
		// dd($vendor_order);
	}
}
