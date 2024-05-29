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
		$dbInventoryProductId = '';

		if($product_id){

			$productVariations = ProductVariation::where('product_id', $product_id)->get();
			$responseText ='';

			foreach ($productVariations as $key => $prodVar) {

				if ($prodVar->pack != 1) {
					$variationName = $prodVar->pack.'x - '.$prodVar->size.'<br> '.$prodVar->container.'s';
				}else{
					$variationName = $prodVar->size.'<br> '.$prodVar->container;
				}
				
				$dbInventoryProduct = InventoryProduct::where([['user_id', session()->get('vendorID')],['product_id',$product_id],['product_variation_id', $prodVar->id]])->first();

				$add_product = isset($dbInventoryProduct) ? 'checked' : '' ;
				$active_status = isset($dbInventoryProduct) ? '' : 'disabled' ;
				$sku = isset($dbInventoryProduct) ? $dbInventoryProduct->sku : '' ;
				$stock = isset($dbInventoryProduct) ? $dbInventoryProduct->stock : '' ;
				$cost_price = isset($dbInventoryProduct) ? $dbInventoryProduct->cost_price : '' ;
				$retail_price = isset($dbInventoryProduct) ? $dbInventoryProduct->retail_price : '' ;
				$barcode = isset($dbInventoryProduct) ? $dbInventoryProduct->barcode : '' ;
				$display = isset($dbInventoryProduct) && $dbInventoryProduct->display == 1 ? 'checked' : '' ;
				$tax_type_1 = $tax_type_2 = $tax_type_3 = '';
				$bottle_deposit_type_1 = $bottle_deposit_type_2 = '';

				if (isset($dbInventoryProduct) && $dbInventoryProduct->tax_type == 1) {
					$tax_type_1 = 'checked' ;
				}elseif (isset($dbInventoryProduct) && $dbInventoryProduct->tax_type == 2) {
					$tax_type_2 = 'checked' ;
				}elseif (isset($dbInventoryProduct) && $dbInventoryProduct->tax_type == 3) {
					$tax_type_3 = 'checked' ;
				}

				if (isset($dbInventoryProduct) && $dbInventoryProduct->bottle_deposit_type == 1) {
					$bottle_deposit_type_1 = 'checked' ;
				}elseif (isset($dbInventoryProduct) && $dbInventoryProduct->bottle_deposit_type == 2) {
					$bottle_deposit_type_2 = 'checked' ;
				}

				$responseText .= '<tr>
									<td><input class="active_status" type="checkbox" name="add_product['.$key.']" value="'.$key.'" '.$add_product.'></td>
                                    <td> 
                                    	<strong>'.$variationName.'</strong> 
                                    	<input type="hidden" name="product_id['.$key.']" value="'.$prodVar->id.'">
                                    </td>
                                    <td>
                                        <input type="text" name['.$key.']="cost_price" class="form-control active_inactive'.$key.'" placeholder="Cost Price" required value="'.$cost_price.'" '.$active_status.'>
                                    </td>
                                    <td>
                                        <input type="text" name="retail_price['.$key.']" class="form-control active_inactive'.$key.'" placeholder="Retail Price" required value="'.$retail_price.'" '.$active_status.'>
                                    </td>
                                    <td>
                                        
                                        <input type="text" name="stock['.$key.']" class="form-control active_inactive'.$key.'" placeholder="Stock" required value="'.$stock.'" '.$active_status.'>   
                                    </td>
                                    <td>
                                        <input type="text" name="sku['.$key.']" class="form-control active_inactive'.$key.'" placeholder="SKU" value="'.$sku.'" '.$active_status.'>
                                    </td>
                                    <td>
                                        <input type="text" name="barcode['.$key.']" class="form-control active_inactive'.$key.'" placeholder="Barcode" value="'.$barcode.'" '.$active_status.'>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="display['.$key.']" value="1" '.$display.' '.$active_status.'>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text" style="background-color: #e1e8ed">

                                                            <input type="checkbox" name="tax_type['.$key.']" value="1" class="tax_type_checkbox" '.$tax_type_1.' '.$active_status.'>

                                                        </div>
                                                    </div>
                                                    <span class="form-control disabled">Type 1 </span>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="input-group">

                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text" style="background-color: #e1e8ed">

                                                            <input type="checkbox" name="tax_type['.$key.']" value="2" class="tax_type_checkbox" '.$tax_type_2.' '.$active_status.'>

                                                        </div>
                                                    </div>
                                                    <span class="form-control disabled">Type 2 </span>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text" style="background-color: #e1e8ed">

                                                            <input type="checkbox" name="tax_type['.$key.']" value="3" class="tax_type_checkbox" '.$tax_type_3.' '.$active_status.'>

                                                        </div>
                                                    </div>
                                                    <span class="form-control disabled">Type 3 </span>
                                                </div>
                                            </div>
                                            <hr>
                                            

                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text" style="background-color: #e1e8ed">

                                                            <input type="checkbox" name="bottle_deposit_type['.$key.']" value="1" class="bottle_deposit_type_checkbox" '.$bottle_deposit_type_1.' '.$active_status.'>

                                                        </div>
                                                    </div>
                                                    <span class="form-control disabled">Type 1 </span>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="input-group">

                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text" style="background-color: #e1e8ed">
                                                            
                                                            <input type="checkbox" name="bottle_deposit_type['.$key.']" value="2" class="bottle_deposit_type_checkbox" '.$bottle_deposit_type_2.' '.$active_status.'>

                                                        </div>
                                                    </div>
                                                    <span class="form-control disabled">Type 2 </span>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>
                                    </td>

                                </tr>';

				// $selected = $prodVar->id == $dbInventoryProductId ? "selected" : "";

				
				// $responseText .= '<option '.$selected. ' value="'.$prodVar->id .'">'.$variationName.'</option>';
			}

			// $responseText .= '</select>';

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

	public function editbulkproduct($username, $product_id)
	{

		$checkVendor = User::check_vendor($username);

		if ($checkVendor) {

			$product = Product::where('id',base64_decode($product_id))->first();
			
			// $product_variations = $product->product_variations;

			$related_inventory_products_ids = $product->inventory_products()->where('user_id',$checkVendor->id)->get()->pluck('id')->all();

			// dd($related_inventory_products_ids);

			// $inventory_product = InventoryProduct::where('id' , base64_decode($id))->firstOrFail();
			$products = Product::where('display',1)->orderBy('order_item')->get();
			// dd($inventory_product);

			return view('admin.inventory-products', array( 'products' => $products, 'id' => -1, 'username' => $username, 'product' => $product, 'related_inventory_products_ids' => $related_inventory_products_ids  ));
		}else{
			return redirect()->back()->with('log_status','Sorry, You are not authorized!');
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
		dd($_POST);
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
