<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Validator;
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
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderMail;

class OrderController extends Controller
{
    public function place_order(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'billing_name' => 'required|max:255',
            'billing_email' => 'required|email|max:225',
            'billing_phone' => 'required|max:225',
            'billing_street_address' => 'required|max:225',
            'billing_city' => 'required|max:225',
            'billing_zip_code' => 'required|max:225',
            'billing_country' => 'required|max:225',
            'billing_state' => 'required|max:225',
            'shipping_name' => 'required|max:255',
            'shipping_email' => 'required|email|max:225',
            'shipping_phone' => 'required|max:225',
            'shipping_street_address' => 'required|max:225',
            'shipping_city' => 'required|max:225',
            'shipping_zip_code' => 'required|max:225',
            'shipping_country' => 'required|max:225',
            'shipping_state' => 'required|max:225',
            'payment_method' => 'required',
            'delivery_method' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
            ->back()
            ->withErrors($validator)
            ->withInput()
            ->with('flag',1);
        }


        

        $billing_details = array(
            'billing_name' => $request->billing_name,
            'billing_email' => $request->billing_email,
            'billing_phone' => $request->billing_phone,
            'billing_street_address' => $request->billing_street_address,
            'billing_apt_ste_bldg' => $request->billing_apt_ste_bldg,
            'billing_city' => $request->billing_city,
            'billing_zip_code' => $request->billing_zip_code,
            'billing_country' => $request->billing_country,
            'billing_state' => $request->billing_state
        );

        $shipping_details = array(
            'shipping_name' => $request->shipping_name,
            'shipping_email' => $request->shipping_email,
            'shipping_phone' => $request->shipping_phone,
            'shipping_street_address' => $request->shipping_street_address,
            'shipping_apt_ste_bldg' => $request->shipping_apt_ste_bldg,
            'shipping_city' => $request->shipping_city,
            'shipping_zip_code' => $request->shipping_zip_code,
            'shipping_country' => $request->shipping_country,
            'shipping_state' => $request->shipping_state
        );


        $cartItems = session()->get('cart');
        $cart = collect($cartItems)->whereIn('cart_id', session('checked_cart_id'));        

        $cartByVendor = collect($cart)->groupBy('vendor_id');
        // dd($cartByVendor);

        $total_price = session()->get('total_price');
        // dd($total_price);

        $order_status = 0;
        $payment_status = 0;

        $max_id = Order::max('id');
        $order_no = (date('Y')*10000)+$max_id+1;

        $orderArray = array('order_no' => $order_no,
                            'customer_id' => Auth::check() ? Auth::user()->id : 0,
                            'customer_name' => $request->name,
                            'customer_email' => $request->email,
                            'customer_phone' => $request->phone,
                            'billing_details' => json_encode($billing_details),
                            'shipping_details' => json_encode($shipping_details),
                            'status' => $order_status,
                            'total_price' => $total_price,
                            'payment_status' => $payment_status,
                            'payment_method' => $request->payment_method,
                            'delivery_method' => $request->delivery_method,
                            'order_json' => json_encode($cart),
                            'message' => $request->message
                            );

        $order = Order::create($orderArray);
        // ========================================= Vendor Orders  ==================================================

        foreach($cartByVendor as $vendor_id => $vendor_items){
            
            $subTotalPrice = 0;
            $vendor_grand_total = 0;
            $beforeTaxSubTotal = 0;
            $taxTotal = 0;

        	// dd($vendor_items);
        	$vendor = User::where('id', $vendor_id)->first();

        	$vendorOrderArray = array(	'order_id' => $order->id,
        								'vendor_id' => $vendor_id,
        								'status' => $order_status,
        								'payment_status' => $payment_status,
        								'payment_method' => $request->payment_method,
                                        'sub_total_exc_tax' => 0,
                                        'tax_total' => 0,
                                        'sub_total_inc_tax' => 0,
        								'delivery_fee' => isset($vendor->vendor_details->delivery_fee) ? $vendor->vendor_details->delivery_fee : 0,
                                        'grand_total' => 0,
        								'order_json' => json_encode($vendor_items)
    								);

        	$vendor_order = VendorOrder::create($vendorOrderArray);

            // ========================================= Vendor Ordered Products  ==================================================

        	$orderedProductArray = array();

        	foreach ($vendor_items as $key => $item){

        		$invProd = InventoryProduct::where('id', $item["cart_id"])->first();

        		if($invProd->tax_type == 1){
        		    
        		    $tax_rate = $vendor->vendor_details->tax_rate_1;
        		    
        		}elseif($invProd->tax_type == 2){
        		    
        		    $tax_rate = $vendor->vendor_details->tax_rate_2;
        		    
        		}elseif($invProd->tax_type == 3){
        		    
        		    $tax_rate = $vendor->vendor_details->tax_rate_3;
        		    
        		}else{
        		    
        		    $tax_rate = 0;
        		}

        		if($invProd->bottle_deposit_type == 1){
        		    
        		    $bottle_deposit_rate = $vendor->vendor_details->bottle_deposit_1_rate;
        		    
        		}elseif($invProd->bottle_deposit_type == 2){
        		    
        		    $bottle_deposit_rate = $vendor->vendor_details->bottle_deposit_2_rate;
        		    
        		}else{
        		    
        		    $bottle_deposit_rate = 0;
        		}

                // ============================================= calculation for Database Sales Report & Payment Starts ===============================

                $itemPrice = ($item['cart_subTotal']/$item['cart_orderedQty']);

                $bottleDepositPerItem = $invProd->product_variation->pack * (int)$item['cart_orderedQty'] * $bottle_deposit_rate;

                $beforeTaxSubTotalPerItem =  ($itemPrice * $item['cart_orderedQty']) + $bottleDepositPerItem;

                $beforeTaxSubTotal = $beforeTaxSubTotal + $beforeTaxSubTotalPerItem;

                $taxPrice = $itemPrice * ($tax_rate/100);
                $taxPrice = number_format($taxPrice, 2);

                $taxTotalPerItem = $taxPrice * $item['cart_orderedQty'];

                $taxTotal = $taxTotal + $taxTotalPerItem;


                $cart_subTotal = number_format(($beforeTaxSubTotalPerItem + $taxTotalPerItem), 2);

                $subTotalPrice += $cart_subTotal ; 

                // ============================================= calculation for Database Sales Report & Payment Ends ===============================


        		$pack = $invProd->product_variation->pack != 1 ? $invProd->product_variation->pack.'x' : $invProd->product_variation->size;

        		$size = $invProd->product_variation->pack != 1 ? $invProd->product_variation->size : '' ;

        		$container = $invProd->product_variation->pack != 1 ? $invProd->product_variation->container.'s' : $invProd->product_variation->container;

        		$variation_name = $pack.' '.$size.' '.$container;

        		$productArray = array(	'vendor_id' => $vendor_id,
        								'product_id' => $item['product_id'],
        								'product_title' => $item['product_title'],
        								'inventory_product_id' => $item['cart_id'],
        								'product_variation_id' => $item['product_variation_id'],
        								'variation_name' => $variation_name,
                                        'pack' => $invProd->product_variation->pack,
        								'quantity' => $item['cart_orderedQty'],
        								'sub_total' => $item['cart_subTotal'],
        								'tax_rate' => $tax_rate,
        								'bottle_deposit_rate' => $bottle_deposit_rate,
                                        'grand_total' => $cart_subTotal,
        								'status' => $order_status,
        							);

        		array_push($orderedProductArray, $productArray);
        	} 
            $deliveryCharge = isset($vendor->vendor_details->delivery_fee) ? $vendor->vendor_details->delivery_fee : 0;
            $vendor_grand_total = $subTotalPrice + $deliveryCharge;
            
            // echo "Sub Total(Exc. Tax)  -> ".$beforeTaxSubTotal."<br>";
            // echo "Tax Total  -> ".$taxTotal."<br>";
            // echo "Sub Total(Inc. Tax)  -> ".$subTotalPrice."<br>";
            // echo "Delivery Charge  -> ".$deliveryCharge."<br>";
            // echo "Grand Total  -> ".$vendor_grand_total."<br>";
            // echo "====================================================================<br>";

            $vendor_order->sub_total_exc_tax = $beforeTaxSubTotal;
            $vendor_order->tax_total = $taxTotal;
            $vendor_order->sub_total_inc_tax = $subTotalPrice;
            $vendor_order->grand_total = $vendor_grand_total;
            $vendor_order->save();

        	$vendor_order->ordered_products()->createMany($orderedProductArray);
        }


        if (Auth::check()) {
            $billing_address = Auth::user()->customer_addresses()->where('address_type', 1)->first();
            $shipping_address = Auth::user()->customer_addresses()->where('address_type', 2)->first();

            $user = User::findOrFail(Auth::user()->id);

            if (!$billing_address) {
                
                $billing_details = array(
                    'name' => $request->billing_name,
                    'email' => $request->billing_email,
                    'phone' => $request->billing_phone,
                    'street_address' => $request->billing_street_address,
                    'apt_ste_bldg' => $request->billing_apt_ste_bldg,
                    'city' => $request->billing_city,
                    'zip_code' => $request->billing_zip_code,
                    'country' => $request->billing_country,
                    'state' => $request->billing_state
                );

                // $billingDetailsSaved = $user->customer_addresses()->updateOrCreate(['address_type' => 1], $billing_details);

            }

            if (!$shipping_address) {

                $shipping_details = array(
                    'name' => $request->shipping_name,
                    'email' => $request->shipping_email,
                    'phone' => $request->shipping_phone,
                    'street_address' => $request->shipping_street_address,
                    'apt_ste_bldg' => $request->shipping_apt_ste_bldg,
                    'city' => $request->shipping_city,
                    'zip_code' => $request->shipping_zip_code,
                    'country' => $request->shipping_country,
                    'state' => $request->shipping_state
                );

                $shippingDetailsSaved = $user->customer_addresses()->updateOrCreate(['address_type' => 1], $shipping_details);
                                
            }

        }

        dd('test');
        $subject = "Order Mail - #".$order->order_no." | Liquor Store ";
        
        $billing_details = json_decode($order->billing_details);
   		$shipping_details = json_decode($order->shipping_details);
   		$vendor_orders = $order->vendor_orders->all();

        $orderMessage = array(
            'order' => $order,
            'subject' => $subject,
            'vendor_orders' => $vendor_orders,
            'billing_details' => $billing_details,
            'shipping_details' => $shipping_details,
            'total_price' => $total_price,
            'ordered_date' => date('jS F, Y',strtotime($order->created_at)),
            'site_email' => 'customer@liquorstore.ktmrush.com',
        );


        Mail::to($orderMessage['order']->customer_email)->send(new OrderMail($orderMessage));
        
        // dd('success');
        // return view('order-mail', compact('orderMessage'));

        // exit();

        // dd($newCart->count());
        
        foreach ($cart as $item) {

        	$invProd = InventoryProduct::where('id', $item["cart_id"])->first();
        	$invProd->stock = $invProd->stock - $item["cart_orderedQty"];
        	$invProd->save();

        }

        $newCart = collect($cartItems)->diffKeys($cart)->all();

        if (count($newCart) > 0) {

        	session()->put("cart", $newCart);
			session()->forget('checked_cart_id');
			session()->save();
			return redirect()->route('cart')->with('success_status','Your order has been placed Successfully!');
        }else{
        	session()->forget('cart');
        	session()->forget('total_price');
        	session()->forget('checked_cart_id');
        	session()->save();
        	return redirect()->route('home')->with('success_status','Your order has been placed Successfully!');
        }

        // dd($cart);


        // $order->vendor_orders()->createMany($vendorOrderArray);
        
        // dd($orderArray);



        // dd($_POST);
    }
}
