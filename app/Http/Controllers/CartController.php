<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use App;
use App\User;
use App\Order;
use App\VendorOrder;
use App\OrderedProduct;
use App\Category;
use App\Product;
use App\InventoryProduct;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class CartController extends Controller
{
 	public function cart()
    {

        // $str = '40oz Bottles';
        // echo substr_count($str, ' ');
        // preg_match_all('!\d+!', $str, $matches);
        // dd($matches);

        $cart = (array)session()->get('cart');
        // dd($cart);
        $cartTotalPrice = session()->get('total_price');
        // dd($cartTotalPrice);
        $cartByVendor = collect($cart)->groupBy('vendor_id')->all();
        // dd(array_key_exists(2, $cartByVendor));
        // dd($cartByVendor);
        // foreach ($cartByVendor as $key => $vendorItems) {
        //     foreach ($vendorItems as $key => $item) {
        //         echo $item['cart_id']."______++++++______";
        //     }
        // }
        // dd($cartByVendor);
        return view('cart',compact('cartByVendor'));
    }

    public function delivery_address()
    {
    	$cart = (array)session()->get("cart");

        if (empty($cart)) {
        	return redirect()->back()->with('error','Cart Empty!!');
        }

    	return view('delivery_address');
    }

    public function order_review(Request $request)
    {
    	dd($request);
    	$validatedData = $request->validate([
            'bill_name' => 'required|max:255',
            'bill_email' => 'required|email|max:225',
            'bill_phone' => 'required|max:225',
            'bill_address' => 'required|max:225',
            'bill_country' => 'required|max:225',
            'bill_region' => 'required|max:225',
            'bill_postal_code' => 'required|max:225',
            'ship_name' => 'required|max:255',
            'ship_email' => 'required|email|max:225',
            'ship_phone' => 'required|max:225',
            'ship_address' => 'required|max:225',
            'ship_country' => 'required|max:225',
            'ship_region' => 'required|max:225',
            'ship_postal_code' => 'required|max:225',
        ]);

        $billingDetails = array(
            'bill_name' => $request->bill_name,
            'bill_email' => $request->bill_email,
            'bill_phone' => $request->bill_phone,
            'bill_address' => $request->bill_address,
            'bill_country' => $request->bill_country,
            'bill_region' => $request->bill_region,
            'bill_postal_code' => $request->bill_postal_code
        );

        $shippingDetails = array(
            'ship_name' => $request->ship_name,
            'ship_email' => $request->ship_email,
            'ship_phone' => $request->ship_phone,
            'ship_address' => $request->ship_address,
            'ship_country' => $request->ship_country,
            'ship_region' => $request->ship_region,
            'ship_postal_code' => $request->ship_postal_code
        );

        session()->put('billingDetails', $billingDetails);
        session()->put('shippingDetails', $shippingDetails);

        return view('order_review',compact('billingDetails','shippingDetails'));
    	// dd($_POST);	
    }

    public function addToCart(Request $request){

    	$cart = (array)session()->get("cart");
    	$cartTotalPrice = (float)session()->get("total_price");

    	if ($request->inventory_id) {
            
            $cart_ids =  array_column($cart, 'cart_id');
            $cart_key = (int)array_search($request->inventory_id, $cart_ids);

			$inventoryProduct = InventoryProduct::where("id",$request->inventory_id)->first();

            $productTitle = $inventoryProduct->product->product_name;

            $dbStock = $inventoryProduct->stock;
            // dd($cart_key);
            $in_cart = count($cart) == 0 ? 0 : $cart[$cart_key]['cart_orderedQty'];
            $availableStock = $dbStock - $in_cart;
            // dd($availableStock);

			if ($dbStock >= $request->orderedQty) {

    			$pTotal = 0;
    			$pTotal += $request->tPrice;
    			$cartTotalPrice = $cartTotalPrice +  $pTotal;
    			$item = array();
    			$item = $cart;
    			$count = count($item);

    			if ($count == 0) {
    				$cartItem = array('cart_id' => (int)$request->inventory_id, 
                                  'product_id' => (int)$request->product_id, 
                                  'product_variation_id' => (int)$request->product_variation_id, 
                                  'vendor_id' => (int)$request->vendor_id, 
    							  'product_title' => addslashes($productTitle),
    							  'cart_orderedQty' =>(int)$request->orderedQty,
    							  'cart_subTotal' => (float)$request->tPrice,
    							);

    				session()->push("cart", $cartItem);
    				session()->put("total_price", $cartTotalPrice);

    				$data = array('status'=> 'success', 'totalQty'=>count(session()->get('cart')) , 'totalPrice' => $cartTotalPrice);
    				
    				echo json_encode($data);

    			}else{
    				
    				for($i=0; $i < $count; $i++){

    					if ($request->inventory_id == $item[$i]['cart_id'] && $request->product_variation_id == $item[$i]['product_variation_id']) {
    						
    						$orderedQtyTemp = (int)$item[$i]['cart_orderedQty']+ (int)$request->orderedQty;
    						
                            if ($orderedQtyTemp > $dbStock) {

                                $cartTotalPrice -= $pTotal;

                                $data = array('status'=> 'stockerror', 'stock' => $availableStock, 'in_cart' => $in_cart, 'orderedTotal' => $cart[$i]["cart_orderedQty"]);
                                
                                echo json_encode($data);
                                exit();

                            }else{

                                $cart[$i]["cart_orderedQty"] = $orderedQtyTemp;
                                $cart[$i]['cart_subTotal'] = (float)$item[$i]['cart_subTotal'] + (float)$request->tPrice;

                                session()->put("cart", $cart);
                                session()->put("total_price", $cartTotalPrice);
                                session()->save();

                                $data = array('status'=> 'success','totalQty'=>count($cart), 'totalPrice' => $cartTotalPrice);

                                echo json_encode($data);
                                exit();
                            }
    					}
    				}

    				$cartItem = array('cart_id' => (int)$request->inventory_id, 
                                  'product_id' => (int)$request->product_id, 
                                  'product_variation_id' => (int)$request->product_variation_id, 
                                  'vendor_id' => (int)$request->vendor_id, 
                                  'product_title' => addslashes($productTitle),
                                  'cart_orderedQty' =>(int)$request->orderedQty,
                                  'cart_subTotal' => (float)$request->tPrice,
                                );

    				session()->push("cart", $cartItem);
    				session()->put("total_price", $cartTotalPrice);

    				$data = array('status'=> 'success','totalQty'=>count(session()->get('cart')) , 'totalPrice' => $cartTotalPrice);
    				
    				echo json_encode($data);

    			}
            }else{

                $data = array('status'=> 'stockerror', 'stock'=> $availableStock, 'in_cart' => $in_cart);
                echo json_encode($data);
            }
		
		}	
    }

    public function update_cart(Request $request){
    	// dd($request);
    	$cart = (array)session()->get("cart");
    	$cartTotalPrice = (float)session()->get("total_price");

        $cart_id = $request->cart_id;
        $qty = $request->qty;

        $cart_ids =  array_column($cart, 'cart_id');
        $cart_key = (int)array_search($cart_id, $cart_ids);

        $productUnitPrice = $cart[$cart_key]['cart_subTotal']/$cart[$cart_key]['cart_orderedQty'];
        $cartTotalPrice = $cartTotalPrice - $cart[$cart_key]['cart_subTotal'];

        $cart[$cart_key]['cart_orderedQty'] = $qty;
        $cart[$cart_key]['cart_subTotal'] = $qty*$productUnitPrice;
        $cartTotalPrice = $cartTotalPrice + $cart[$cart_key]['cart_subTotal'];

        session()->put("cart", $cart);
        session()->put("total_price", $cartTotalPrice);
        session()->save();

        $data = array('status'=> 'success', 'totalQty'=>count(session()->get('cart')) , 'totalPrice' => $cartTotalPrice);
        
        echo json_encode($data);
        exit();
    }

    public function delete_cart_item(Request $request){

    	$cart = (array)session()->get("cart");
    	$cartTotalPrice = (float)session()->get("total_price");

    	if (@$request->action == 'delete') {
			$id = $request->id;

			$cartTotalPrice = $cartTotalPrice - $cart[$id]['cart_subTotal'];

			unset($cart[$id]);
			$cart = array_values($cart);

			$data = array('status'=> 'deleted','totalQty'=>count($cart), 'totalPrice' => $cartTotalPrice);

			session()->put("cart", $cart);
			session()->put("total_price", $cartTotalPrice);
			session()->save();
			
			echo json_encode($data);
		}
    }

    public function update_checked_cart_products(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'checked_cart_id' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error','Please Check at least one item.')->withInput();
        }

        session()->put('checked_cart_id', $request->checked_cart_id);
        return redirect()->route('checkout')->with('flag',1);
    }

    public function checkout()
    {
        if (session('checked_cart_id') == NULL || session('flag') != 1) {
            return redirect()->route('cart');
        }

        // $cartItems = session()->get('cart');
        // // dd($cartItems);
        // $cart = collect($cartItems)->whereIn('cart_id', session('checked_cart_id'));
        // // dd($cart);
        // $newCart = collect($cartItems)->diffKeys($cart)->all();

        // dd(array_values($newCart));

        if (Auth::check()) {
            $customer = User::where('id', Auth::user()->id)->first();
            $billing_address = $customer->customer_addresses()->where('address_type', 1)->first();
            $shipping_address = $customer->customer_addresses()->where('address_type', 2)->first();
        }else{
            $customer = $billing_address = $shipping_address = NULL;
        }

        $db_countries = DB::table('countries')->get();

        $cart = session()->get('cart');
        $cart = collect($cart)->whereIn('cart_id',session('checked_cart_id'));
        
        $cartByVendor = collect($cart)->groupBy('vendor_id');
        

        return view('checkout',compact('customer', 'billing_address', 'shipping_address','db_countries','cartByVendor'));
    }

    public function clear_shopping_cart()
    {
    	session()->forget("cart");
        session()->forget("total_price");

        return redirect()->to('/')->with('success_status','Shopping Cart Cleared Successfully!');
    }


}
