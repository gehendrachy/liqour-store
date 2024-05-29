<?php

namespace App\Http\Controllers\Vendor;

use Validator;
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
use App;
use App\User;
use App\Product;
use App\Category;
use App\Variation;
use App\SubVariation;
use App\ProductVariation;
use App\InventoryProduct;
use App\Vendor;

class InventoryProductController extends Controller
{
	public function __construct()
	{
		$this->middleware('role:Vendor|Super Admin');
	}

	public function index($username)
	{
		$checkVendor = User::check_vendor($username);

		if ($checkVendor) {

			// $inventory_products = InventoryProduct::where('user_id',$checkVendor->id)->get()->groupBy('product_id');
			$inventory_products = InventoryProduct::where('user_id',$checkVendor->id)->get()->groupBy('product_id');
			// dd($inventory_products);
			$products = Product::orderBy('order_item')->get();

			return view('admin.inventory-products', array('inventory_products' => $inventory_products, 'products' => $products, 'id' => '0', 'username' => $username));
		}else{
			return redirect()->back()->with('log_status','Permission Denied!');
		}
	}

	public function get_product_variations(Request $request)
	{
		$product_id = $request->product_id;
		$inventory_prod_id = base64_decode($request->inventory_prod_id);
		$dbInventoryProductId = '';
		if($product_id){

			$productVariations = ProductVariation::where('product_id', $product_id)->get();
			$responseText ='<select class="custom-select variation-select2"  name="product_variation_id" required>';

			if ($inventory_prod_id != '') {
				$dbInventoryProductId = InventoryProduct::where('id', $inventory_prod_id)->first()->product_variation_id;
			}

			foreach ($productVariations as $key => $prodVar) {
				$selected = $prodVar->id == $dbInventoryProductId ? "selected" : "";

				if ($prodVar->pack != 1) {
					$variationName = $prodVar->pack.'x - '.$prodVar->size.' '.$prodVar->container.'s';
				}else{
					$variationName = $prodVar->size.' '.$prodVar->container;
				}
				$responseText .= '<option '.$selected. ' value="'.$prodVar->id .'">'.$variationName.'</option>';
			}

			$responseText .= '</select>';

		}else{
			$responseText = '<select class="custom-select" required><option selected disabled>Select Product First</option></select>';
		}

		return $responseText;
	}

	public function createproduct(Request $request)
	{
		// dd($_POST);
		$validator = Validator::make($request->all(), [
			"product_id" => 'required|max:255',
			"product_variation_id" => 'required',
			"stock" => 'required',
			"cost_price" => 'required',
			"retail_price" => 'required',
			// "tax_type" => 'required',
			// "bottle_deposit_type" => 'required'
		]);

		if ($validator->fails()) {
			return redirect()
			->back()
			->withErrors($validator)
			->withInput();
		}

		$productExists = InventoryProduct::where([['user_id',base64_decode($request->user_id)],['product_id',$request->product_id],['product_variation_id',$request->product_variation_id]])->exists();

		$vendor = Vendor::select('tax_rate_1','tax_rate_2','tax_rate_3','bottle_deposit_1_rate','bottle_deposit_2_rate')->where('user_id', base64_decode($request->user_id))->first();

		if ($request->tax_type == 1) {
			$tax_rate = $vendor->tax_rate_1;

		}elseif ($request->tax_type == 2) {
			$tax_rate = $vendor->tax_rate_2;

		}elseif ($request->tax_type == 3) {
			$tax_rate = $vendor->tax_rate_3;
		}

		if ($request->bottle_deposit_type == 1) {
			$bottle_deposit_rate = $vendor->bottle_deposit_1_rate;

		}elseif ($request->bottle_deposit_type == 2) {
			$bottle_deposit_rate = $vendor->bottle_deposit_2_rate;

		}


		if (!$productExists) {
			$productInserted = InventoryProduct::updateOrCreate(
								['user_id'=>base64_decode($request->user_id),
								 'product_id'=>$request->product_id,
								 'product_variation_id'=>$request->product_variation_id],
								['stock' => $request->stock,
								 'sku' => $request->sku,
								 'barcode' => $request->barcode,
								 'cost_price' => $request->cost_price,
								 'retail_price' => $request->retail_price,
								 'tax_type' => $request->tax_type,
								 'bottle_deposit_type' => $request->bottle_deposit_type,
								 'display' => isset($request->display) ? 1 : 0,
								 'updated_at' => date('Y-m-d H:i:s'),
								 'updated_by' => Auth::user()->name]
							);
		}else{
			return redirect()->back()->with('error','Product with this Variation Already Exists!')->withInput();
		}
		if ($productInserted) {
			return redirect()->to('vendor/'.base64_decode($request['username']).'/inventory-products')->with('status', 'Product  Added to Inventory Successfully!');
		}else{
			return redirect()->back()->with('error','Something went Wrong!');
		}
	}

	public function editproduct($username, $id)
	{

		$checkVendor = User::check_vendor($username);

		if ($checkVendor) {
			$inventory_product = InventoryProduct::where('id' , base64_decode($id))->firstOrFail();
			$products = Product::where('display',1)->orderBy('order_item')->get();
			// dd($inventory_product);

			return view('admin.inventory-products', array('inventory_product' => $inventory_product, 'products' => $products, 'id' => base64_decode($id), 'username' => $username  ));
		}else{
			return redirect()->back()->with('log_status','Sorry, You are not authorized!');
		}

	}

	public function updateproduct(Request $request)
	{
		// dd($_POST);
		$validator = Validator::make($request->all(), [
			"product_id" => 'required|max:255',
			"product_variation_id" => 'required',
			"stock" => 'required',
			"cost_price" => 'required',
			"retail_price" => 'required',
			// "tax_type" => 'required',
			// "bottle_deposit_type" => 'required'
		]);

		if ($validator->fails()) {
			return redirect()
			->back()
			->withErrors($validator)
			->withInput();
		}
		// dd($_POST);

		$productExists = InventoryProduct::where([['id','!=',base64_decode($request->id)],['user_id',base64_decode($request->user_id)],['product_id',$request->product_id],['product_variation_id',$request->product_variation_id]])->exists();

		// dd($productExists);
		if (!$productExists) {
			
			// $taxRateArray = ['1' => "tax_rate_1", '2' => "tax_rate_2", '3' => "tax_rate_3"];
			// $bottleRateArray = ['1' =>"bottle_deposit_1_rate", '2' =>"bottle_deposit_2_rate"];
			// $tax_rate_field = $taxRateArray[$request->tax_type];
			$vendor = Vendor::select('tax_rate_1','tax_rate_2','tax_rate_3','bottle_deposit_1_rate','bottle_deposit_2_rate')->where('user_id', base64_decode($request->user_id))->first();

			if ($request->tax_type == 1) {
				$tax_rate = $vendor->tax_rate_1;

			}elseif ($request->tax_type == 2) {
				$tax_rate = $vendor->tax_rate_2;

			}elseif ($request->tax_type == 3) {
				$tax_rate = $vendor->tax_rate_3;
			}

			if ($request->bottle_deposit_type == 1) {
				$bottle_deposit_rate = $vendor->bottle_deposit_1_rate;

			}elseif ($request->bottle_deposit_type == 2) {
				$bottle_deposit_rate = $vendor->bottle_deposit_2_rate;

			}

			// dd($bottle_deposit_rate);

			$productInserted = InventoryProduct::where('id',base64_decode($request->id))->update(
								['product_id' => $request->product_id,
								 'product_variation_id' => $request->product_variation_id,
								 'stock' => $request->stock,
								 'sku' => $request->sku,
								 'barcode' => $request->barcode,
								 'cost_price' => $request->cost_price,
								 'retail_price' => $request->retail_price,
								 'tax_type' => $request->tax_type,
								 'bottle_deposit_type' => $request->bottle_deposit_type,
								 'display' => isset($request->display) ? 1 : 0,
								 'updated_by' => Auth::user()->name,
								 'updated_at' => date('Y-m-d H:i:s')]
							);
		}else{
			return redirect()->back()->with('error','Product with this Variation Already Exists!')->withInput();
		}
		
		if ($productInserted) {
			return redirect()->to('vendor/'.base64_decode($request['username']).'/inventory-products')->with('status', 'Inventory Product Updated!');
		}else{
			return redirect()->back()->with('error','Something went Wrong!');
		}
	}

	public function deleteproduct($username,$id)
	{
		
		$inventory_product = InventoryProduct::where('id' , base64_decode($id))->firstOrFail();

		if ($inventory_product) {

			$inventory_product->delete();

			return redirect()->back()->with('status', 'Product Deleted from your Inventory Successfully!');

		}else{

			return redirect()->back()->with('status', 'Something Went Wrong!');
		}
	}
}
