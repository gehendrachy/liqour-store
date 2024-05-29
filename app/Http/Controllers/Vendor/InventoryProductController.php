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

			if (!isset($checkVendor->vendor_details)) {
				return redirect()->route('vendor.vendor-settings.list',['username' => $username])->with('error','Please Update Vendor Details first');
			}

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
		$tax_type_index = 0;
		if($product_id){

			$productVariations = ProductVariation::where('product_id', $product_id)->get();
			$tableResponse ='';
			
			foreach ($productVariations as $key => $prodVar) {

				if ($prodVar->pack != 1) {
					$variationName = $prodVar->pack.'x - '.$prodVar->size.' '.$prodVar->container.'s';
				}else{
					$variationName = $prodVar->size.' '.$prodVar->container;
				}

				$dbInventoryProduct = InventoryProduct::where([['user_id', session()->get('vendorID')],['product_id',$product_id],['product_variation_id', $prodVar->id]])->first();


				$add_product_checkbox = isset($dbInventoryProduct) ? 'checked' : '' ;
				$active_status = isset($dbInventoryProduct) ? '' : 'disabled' ;
				$required_status = isset($dbInventoryProduct) ? 'required' : '';
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
				if (isset($dbInventoryProduct)) {
					$tax_type_index = $dbInventoryProduct->tax_type;
				}
				
				$tableResponse .= '<tr>
									<td class="text-center">
										<input class="active_status" type="checkbox" name="variation_id[]" value="'.$prodVar->id.'" '.$add_product_checkbox.'>
									</td>
                                    <td> 
                                    	<strong>'.$variationName.'</strong> 
                                    </td>
                                    <td width="10%">
                                        <input type="text" name="cost_price['.$prodVar->id.']" class="form-control decimal-input req_not_req'.$prodVar->id.' active_inactive'.$prodVar->id.'" placeholder="CP" value="'.$cost_price.'" '.$active_status.' '.$required_status.'>
                                    </td>
                                    <td width="10%">
                                        <input type="text" name="retail_price['.$prodVar->id.']" class="form-control decimal-input req_not_req'.$prodVar->id.' active_inactive'.$prodVar->id.'" placeholder="RP" value="'.$retail_price.'" '.$active_status.' '.$required_status.'>
                                    </td>
                                    <td width="10%">
                                        
                                        <input type="text" name="stock['.$prodVar->id.']" class="form-control number-input req_not_req'.$prodVar->id.' active_inactive'.$prodVar->id.'" placeholder="Stock" value="'.$stock.'" '.$active_status.' '.$required_status.'>   
                                    </td>
                                    <td width="10%">
                                        <input type="text" name="sku['.$prodVar->id.']" class="form-control active_inactive'.$prodVar->id.'" placeholder="SKU" value="'.$sku.'" '.$active_status.'>
                                    </td>
                                    <td width="10%">
                                        <input type="text" name="barcode['.$prodVar->id.']" class="form-control active_inactive'.$prodVar->id.'" placeholder="Barcode" value="'.$barcode.'" '.$active_status.'>
                                    </td>
                                    <td class="text-center">
                                        <input class="variation_display'.$prodVar->id.' active_inactive'.$prodVar->id.'" type="checkbox" name="display['.$prodVar->id.']" value="1" '.$display.' '.$active_status.'>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text" style="background-color: #e1e8ed">

                                                            <input type="checkbox" name="bottle_deposit_type['.$prodVar->id.']" value="1" class="bottle_deposit_type_checkbox active_inactive'.$prodVar->id.'" '.$bottle_deposit_type_1.' '.$active_status.'>

                                                        </div>
                                                    </div>
                                                    <span class="form-control disabled">Type 1 </span>
                                                

                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text" style="background-color: #e1e8ed">
                                                            
                                                            <input type="checkbox" name="bottle_deposit_type['.$prodVar->id.']" value="2" class="bottle_deposit_type_checkbox active_inactive'.$prodVar->id.'" '.$bottle_deposit_type_2.' '.$active_status.'>

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

				
				// $tableResponse .= '<option '.$selected. ' value="'.$prodVar->id .'">'.$variationName.'</option>';
			}

			// $tableResponse .= '</select>';

		}else{
			$tableResponse = '<tr>
								<td class="text-center" colspan="9" style="background-color: #a4797e !important; color: white;"><strong >Select Product First</strong></td>
							</tr>';
		}

		$response = array('tax_type_index' => $tax_type_index, 'tableResponse' => $tableResponse);

		echo json_encode($response);
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

	public function create_update_product(Request $request)
	{
		
		// dd($_POST);
		$validator = Validator::make($request->all(), [
			"product_id" => 'required|max:255',
			"variation_id" => 'required',
			"variation_id.*" => 'required',
			"cost_price.*" => 'required',
			"retail_price.*" => 'required',
			"stock.*" => 'required',
			// "tax_type" => 'required',
			// "bottle_deposit_type" => 'required'
		]);


		if ($validator->fails()) {
			return redirect()
			->back()
			->withErrors($validator)
			->with('error', 'Please Select at least one Variation!')
			->withInput();
		}

		// dd($_POST);

		// $productExists = InventoryProduct::where([['id','!=',base64_decode($request->id)],['user_id',base64_decode($request->user_id)],['product_id',$request->product_id],['product_variation_id',$request->product_variation_id]])->exists();

		// dd($productExists);
		
		
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

		$variation_id = $request->variation_id;
		$cost_price = $request->cost_price;
		$retail_price = $request->retail_price;
		$stock = $request->stock;
		$sku = $request->sku;
		$barcode = $request->barcode;
		$display = isset($request->display) ? $request->display : array();
		$bottle_deposit_type = isset($request->bottle_deposit_type) ? $request->bottle_deposit_type : array();

		for ($i=0; $i < count($variation_id); $i++) { 

			if (isset($request->bottle_deposit_type) && $request->bottle_deposit_type == 1) {
				$bottle_deposit_rate = $vendor->bottle_deposit_1_rate;

			}elseif (isset($request->bottle_deposit_type) && $request->bottle_deposit_type == 2) {
				$bottle_deposit_rate = $vendor->bottle_deposit_2_rate;

			}


			$productInsertUpdate = InventoryProduct::updateOrCreate(
								[
								 'user_id'=>base64_decode($request->user_id),
								 'product_id'=> $request->product_id,
								 'product_variation_id'=> $variation_id[$i]
								],

								[
								 'stock' => $stock[$variation_id[$i]],
								 'sku' => $sku[$variation_id[$i]],
								 'barcode' => $barcode[$variation_id[$i]],
								 'cost_price' => $cost_price[$variation_id[$i]],
								 'retail_price' => $retail_price[$variation_id[$i]],
								 'tax_type' => isset($request->tax_type) ? $request->tax_type : NULL,
								 'bottle_deposit_type' => isset($bottle_deposit_type[$variation_id[$i]]) ? $bottle_deposit_type[$variation_id[$i]] : NULL, 
								 'display' => isset($display[$variation_id[$i]]) ? 1 : 0,
								 'updated_at' => date('Y-m-d H:i:s'),
								 'updated_by' => Auth::user()->name
								]
							);
		}

		// if (!$productExists) {
		// }else{
		// 	return redirect()->back()->with('error','Product with this Variation Already Exists!')->withInput();
		// }

		if ($productInsertUpdate) {
			$deleteProducts = InventoryProduct::where([['user_id', base64_decode($request->user_id)], ['product_id', $request->product_id]])
												->whereNotIn('product_variation_id', $variation_id)
												->delete();
			

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

	public function delete_all_inventory_products($username,$id)
	{
		$checkVendor = User::check_vendor($username);

		if ($checkVendor) {


			$inventory_products = InventoryProduct::where([['user_id',$checkVendor->id], ['product_id' , base64_decode($id)]])->get();
			// dd($inventory_products);

			if ($inventory_products) {

				InventoryProduct::where([['user_id',$checkVendor->id], ['product_id' , base64_decode($id)]])->delete();

				return redirect()->back()->with('status', 'Product Deleted from your Inventory Successfully!');

			}else{

				return redirect()->back()->with('status', 'Something Went Wrong!');
			}

		}else{
			return redirect()->back()->with('log_status','Permission Denied!');
		}
		
	}
}
