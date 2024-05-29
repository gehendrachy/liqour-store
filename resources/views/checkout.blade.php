@extends('layouts.app')
@section('title','Check Out')
@section('content')
<div class="main-container container">
    <ul class="header-main ">
        <li class="home"><a href="{{ route('home') }}">Home   </a><i class="fa fa-angle-right" aria-hidden="true"></i></li>
        <li> Check Out</li>
    </ul>

    <ul class="breadcrumb">
        <li>
            <a href="{{ route('cart') }}" >Shopping Cart</a>
        </li>
        <li>
            <a href="javascript:void(0)" class="active">Billing / Shipping Address</a>
        </li>
        <li>
            <a href="javascript:void(0)">Order Status</a>
        </li>
    </ul>


    <div class="so-onepagecheckout ">
        <div class="row">
            <form action="{{ route('place-order') }}" method="POST">
                @csrf
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title"><i class="fa fa-user"></i> Your Personal Details</h4>
                        </div>
                        <div class="panel-body">
                            <fieldset id="account">
                                <div class="row">
                                    <div class="col-sm-4 form-group required">
                                        <label for="input-name" class="control-label">Full Name</label>
                                        <input type="text" class="form-control" id="input-name" placeholder="eg: John Doe" value="{{ old('name') ? old('name') : (Auth::check() ? Auth::user()->name : '') }}" name="name" required>
                                    </div>

                                    <div class="col-sm-4 form-group required">
                                        <label for="input-email" class="control-label">E-Mail</label>
                                        <input type="email" class="form-control" id="input-email" placeholder="E-Mail" value="{{ old('email') ? old('email') : (Auth::check() ? Auth::user()->email : '') }}" name="email" required>
                                        <small>*Confirmation mail will be sent to this email.</small>
                                    </div>
                                    <div class="col-sm-4 form-group required">
                                        <label for="input-phone" class="control-label">Contact Number</label>
                                        <input type="tel" class="form-control" id="input-phone" placeholder="Contact Number" value="{{ old('phone') ? old('phone') : (Auth::check() ? Auth::user()->phone : '') }}" name="phone" required>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title"><i class="fa fa-book"></i> Billing Address</h4>
                        </div>
                        <div class="panel-body">
                            
                            <fieldset id="address">
                                <div class="row">
                                    <div class="col-sm-6 form-group required">
                                        <label for="input-billing-name" class="control-label">Name</label>
                                        <input type="text" class="form-control" id="input-billing-name" placeholder="Billing Name" value="{{ old('billing_name') ? old('billing_name') : (isset($billing_address->name) ? $billing_address->name : '') }}" name="billing_name" required>

                                    </div>

                                    <div class="col-sm-6 form-group required">
                                        <label for="input-billing-email" class="control-label">Email</label>
                                        <input type="email" class="form-control" id="input-billing-email" placeholder="Billing Email" value="{{ old('billing_email') ? old('billing_email') : (isset($billing_address->email) ? $billing_address->email : '') }}" name="billing_email" required>

                                    </div>

                                    <div class="col-sm-6 form-group required">
                                        <label for="input-billing-phone" class="control-label">Phone</label>
                                        <input type="text" class="form-control" id="input-billing-phone" placeholder="Billing Phone" value="{{ old('billing_phone') ? old('billing_phone') : (isset($billing_address->phone) ? $billing_address->phone : '') }}" name="billing_phone" required>
                                    </div>

                                    <div class="col-sm-6 form-group">
                                        <label for="input-billing-apt-ste-bldg" class="control-label">Apartment #/ Suite / Building </label>
                                        <input type="text" class="form-control" id="input-billing-apt-ste-bldg" placeholder="Apartment #/ Suite / Building" value="{{ old('billing_apt_ste_bldg') ? old('billing_apt_ste_bldg') : (isset($billing_address->apt_ste_bldg) ? $billing_address->apt_ste_bldg : '') }}" name="billing_apt_ste_bldg">
                                    </div>

                                    <div class="col-sm-6 form-group required">
                                        <label for="input-billing-street-address" class="control-label">Street Address</label>
                                        <input type="text" class="form-control" id="input-billing-street-address" placeholder="Street Address" value="{{ old('billing_street_address') ? old('billing_street_address') : (isset($billing_address->street_address) ? $billing_address->street_address : '') }}" name="billing_street_address" required>
                                    </div>

                                    <div class="col-sm-6 form-group required">
                                        <label for="input-billing-city" class="control-label">City</label>
                                        <input type="text" class="form-control" id="input-billing-city" placeholder="Billing City" value="{{ old('billing_city') ? old('billing_city') : (isset($billing_address->city) ? $billing_address->city : '') }}" name="billing_city" required>
                                    </div>

                                    <div class="col-sm-6 form-group required">
                                        <label for="input-billing-zip-code" class="control-label">Zip Code</label>
                                        <input type="text" class="form-control" id="input-billing-zip-code" placeholder="Billing Zip Code" value="{{ old('billing_zip_code') ? old('billing_zip_code') : (isset($billing_address->zip_code) ? $billing_address->zip_code : '') }}" name="billing_zip_code" required>
                                    </div>


                                    <div class="col-sm-6 form-group required">
                                        <label for="input-billing-country" class="control-label">Country</label>

                                        <select class="form-control billing_shipping_country" data-state-input-id="input-billing-state" data-state-id="{{ old('billing_state') ? old('billing_state') : (isset($billing_address->state) ? $billing_address->state : 0) }}" id="input-billing-country" name="billing_country" required>

                                            <option value="" selected disabled> --- Please Select --- </option>
                                            @php
                                            $billingCountry = old('billing_country') ? old('billing_country') : (isset($billing_address->country) ? $billing_address->country : '');
                                            @endphp

                                            @foreach($db_countries as $country)
                                            <option <?=$billingCountry == $country->id ? 'selected' : '' ?> value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>

                                    </div>

                                    <div class="col-sm-6 form-group required">
                                        <label for="input-billing-state" class="control-label">Region / State</label>

                                        <select class="form-control" id="input-billing-state" name="billing_state" required>
                                            <option value="" selected disabled> --- Please Select --- </option>
                                        </select>

                                    </div>
                                </div>

                            </fieldset>
                                
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-book"></i> 
                                Shipping Address
                                <small class="pull-right">

                                <input type="checkbox" id="sameAsShippingAddress">
                                <label for="sameAsShippingAddress">Same As Billing Address</label>
                                </small>
                            </h4>
                        </div>
                        <div class="panel-body">
                            
                            <fieldset id="address">
                                <div class="row">
                                    <div class="col-sm-6 form-group required">
                                        <label for="input-shipping-name" class="control-label">Name</label>
                                        <input type="text" class="form-control" id="input-shipping-name" placeholder="Shipping Name" value="{{ old('shipping_name') ? old('shipping_name') : (isset($shipping_address->name) ? $shipping_address->name : '') }}" name="shipping_name" required>

                                    </div>

                                    <div class="col-sm-6 form-group required">
                                        <label for="input-shipping-email" class="control-label">Email</label>
                                        <input type="email" class="form-control" id="input-shipping-email" placeholder="Shipping Email" value="{{ old('shipping_email') ? old('shipping_email') : (isset($shipping_address->email) ? $shipping_address->email : '') }}" name="shipping_email" required>

                                    </div>

                                    <div class="col-sm-6 form-group required">
                                        <label for="input-shipping-phone" class="control-label">Phone</label>
                                        <input type="text" class="form-control" id="input-shipping-phone" placeholder="Shipping Phone" value="{{ old('shipping_phone') ? old('shipping_phone') : (isset($shipping_address->phone) ? $shipping_address->phone : '') }}" name="shipping_phone" required>
                                    </div>

                                    <div class="col-sm-6 form-group">
                                        <label for="input-shipping-apt-ste-bldg" class="control-label">Apartment #/ Suite / Building </label>
                                        <input type="text" class="form-control" id="input-shipping-apt-ste-bldg" placeholder="Apartment #/ Suite / Building" value="{{ old('shipping_apt_ste_bldg') ? old('shipping_apt_ste_bldg') : (isset($shipping_address->apt_ste_bldg) ? $shipping_address->apt_ste_bldg : '') }}" name="shipping_apt_ste_bldg">
                                    </div>

                                    <div class="col-sm-6 form-group required">
                                        <label for="input-shipping-street-address" class="control-label">Street Address</label>
                                        <input type="text" class="form-control" id="input-shipping-street-address" placeholder="Street Address" value="{{ old('shipping_street_address') ? old('shipping_street_address') : (isset($shipping_address->street_address) ? $shipping_address->street_address : '') }}" name="shipping_street_address" required>
                                    </div>

                                    <div class="col-sm-6 form-group required">
                                        <label for="input-shipping-city" class="control-label">City</label>
                                        <input type="text" class="form-control" id="input-shipping-city" placeholder="Shipping City" value="{{ old('shipping_city') ? old('shipping_city') : (isset($shipping_address->city) ? $shipping_address->city : '') }}" name="shipping_city" required>
                                    </div>

                                    <div class="col-sm-6 form-group required">
                                        <label for="input-shipping-zip-code" class="control-label">Zip Code</label>
                                        <input type="text" class="form-control" id="input-shipping-zip-code" placeholder="Shipping Zip Code" value="{{ old('shipping_zip_code') ? old('shipping_zip_code') : (isset($shipping_address->zip_code) ? $shipping_address->zip_code : '') }}" name="shipping_zip_code" required>
                                    </div>


                                    <div class="col-sm-6 form-group required">
                                        <label for="input-shipping-country" class="control-label">Country</label>

                                        <select class="form-control billing_shipping_country" data-state-input-id="input-shipping-state" data-state-id="{{ old('shipping_state') ? old('shipping_state') :  (isset($shipping_address->state) ? $shipping_address->state : 0) }}" id="input-shipping-country" name="shipping_country" required>

                                            <option value="" selected disabled> --- Please Select --- </option>
                                            @php
                                            $shippingCountry = old('shipping_country') ? old('shipping_country') : (isset($shipping_address->country) ? $shipping_address->country : '');
                                            @endphp

                                            @foreach($db_countries as $country)
                                            <option <?=$shippingCountry == $country->id ? 'selected' : '' ?> value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>

                                    </div>

                                    <div class="col-sm-6 form-group required">
                                        <label for="input-shipping-state" class="control-label">Region / State</label>

                                        <select class="form-control" id="input-shipping-state" name="shipping_state" required>
                                            <option value="" selected disabled> --- Please Select --- </option>
                                        </select>

                                    </div>
                                </div>
                            </fieldset>
                                
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-12">
                    <div class="panel panel-default no-padding">
                        <div class="col-sm-6  checkout-payment-methods">
                            <div class="panel-heading">
                                <h4 class="panel-title"><i class="fa fa-credit-card"></i> Payment Method</h4>
                            </div>
                            <div class="panel-body">
                                <p>Please select the preferred payment method to use on this order.</p>
                                <div class="radio">
                                    <label>
                                        <input type="radio" checked="checked" name="payment_method" value="1">Cash On Delivery
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" name="payment_method" value="2">Payment Gateway
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 checkout-shipping-methods">
                            <div class="panel-heading">
                                <h4 class="panel-title"><i class="fa fa-truck"></i> Delivery Method <small>(TBD)</small></h4>
                            </div>
                            <div class="panel-body ">
                                <p>Please select the preferred shipping method to use on this order.</p>
                                <div class="radio">
                                    <label>
                                        <input type="radio" checked="checked" name="delivery_method" value="1">
                                        Free Shipping - $0.00
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="delivery_method" value="2">
                                        Flat Shipping Rate - $7.50
                                    </label>
                                </div>

                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title"><i class="fa fa-shopping-cart"></i> Shopping cart</h4>
                        </div>
                        <div class="panel-body">
                            <div class="row" id="cartTable">
                                @if(count($cartByVendor)>0)
                                <!--Middle Part Start-->
                                
                                <div id="content" class="col-sm-12">
                                    <div class=" form-group">
                                        @php 
                                            $subTotalPrice = 0;
                                            $grand_total = 0;
                                            $beforeTaxSubTotal = 0;
                                            $taxTotal = 0;
                                            $deliveryCharge = 0;
                                        @endphp
                                        @foreach ($cartByVendor as $vendor_id => $vendorItems) 

                                        @php
                                            $vendor = \App\User::where('id', $vendor_id)->first();
                                            $deliveryCharge += $vendor->vendor_details->delivery_fee;
                                        @endphp

                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr class="store-clearance">
                                                    <td class="text-left" colspan="2">
                                                        <strong>
                                                            <a href="{{ route('store_details',['vendor_slug' => $vendor->vendor_details->slug]) }}">
                                                                {{ $vendor->vendor_details->store_name }}
                                                            </a>
                                                        </strong>
                                                    </td>
                                                    <td class="text-center">
                                                        <strong>Ordered Quantity</strong>
                                                    </td>
                                                    <td class="text-right">
                                                        <strong>Unit Price</strong>
                                                    </td>
                                                    <td class="text-right">
                                                        <strong>Tax</strong>
                                                    </td>
                                                    <td class="text-right">
                                                        <strong>Sub Total</strong>
                                                    </td>
                                                    <!-- <td class="text-right">
                                                        <strong>Remove</strong>
                                                    </td> -->
                                                </tr>
                                                @foreach ($vendorItems as $key => $item) 
                                                
                                                @php
                        
                                                $cProd = \App\Product::where("id", $item["product_id"])->first();

                                                $invProd = \App\InventoryProduct::where('id',$item["cart_id"])->first();

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

                                                $itemPrice = ($item['cart_subTotal']/$item['cart_orderedQty']);

                                                $bottleDepositPerItem = $invProd->product_variation->pack * (int)$item['cart_orderedQty'] * $bottle_deposit_rate;

                                                $beforeTaxSubTotalPerItem =  ($itemPrice * $item['cart_orderedQty']) + $bottleDepositPerItem;

                                                $beforeTaxSubTotal = $beforeTaxSubTotal + $beforeTaxSubTotalPerItem;


                                                $taxPrice = $itemPrice * ($tax_rate/100);
                                                $taxPrice = number_format($taxPrice, 2);

                                                $taxTotalPerItem = $taxPrice * $item['cart_orderedQty'];

                                                $taxTotal = $taxTotal + $taxTotalPerItem;

                                                // $unitPriceWithTax = number_format(($itemPrice + $taxTotalPerItem ), 2);


                                                $cart_subTotal = number_format(($beforeTaxSubTotalPerItem + $taxTotalPerItem), 2);

                                                $subTotalPrice += $cart_subTotal ; 
                                                @endphp

                                                <tr>
                                                    
                                                    <td class="text-center" width="150">
                                                        <a href="{{ url('product/'.$cProd->slug) }}">
                                                            <img width="70px" src="{{ asset('storage/products/'.$cProd->slug.'/thumbs/thumb_'.$invProd->product_variation->image) }}" alt="{{ $cProd->product_name }}" title="{{ $cProd->product_name }}" class="img-thumbnail" />
                                                        </a>
                                                    </td>
                                                    <td class="text-left" width="480">
                                                        <a href="{{ url('product/'.$cProd->slug) }}">
                                                            <b>{{ $cProd->product_name }}<br>
                                                            </b> 
                                                        </a>
                                                        <p>
                                                            {{ $invProd->product_variation->pack != 1 ? $invProd->product_variation->pack.'x' : $invProd->product_variation->size}}

                                                            {{$invProd->product_variation->pack != 1 ? $invProd->product_variation->size : '' }} 

                                                            {{ $invProd->product_variation->pack != 1 ? $invProd->product_variation->container.'s' : $invProd->product_variation->container }}
                                                        </p>
                                                        @if($bottle_deposit_rate != 0)
                                                        <p>
                                                            Bottle Deposit :                                     

                                                            {{ $invProd->product_variation->pack * (int)$item['cart_orderedQty'] }} @ {{ $bottle_deposit_rate }}

                                                            = ${{ $bottleDepositPerItem }}
                                                        </p>
                                                        @endif
                                                    </td>   
                                                    <td class="text-center" width="150px">
                                                        <b>{{(int)$item['cart_orderedQty']}}</b>
                                                    </td>

                                                    <td class="text-right">
                                                        <strong>
                                                            ${{ $itemPrice }}
                                                        </strong>
                                                        <!-- @if($tax_rate != 0)
                                                        <small>(Inc. {{ $tax_rate }}% tax)</small>
                                                        @endif -->
                                                    </td>
                                                    <td class="text-right">
                                                        <strong>${{ $taxTotalPerItem }}</strong>
                                                        <br>
                                                        <!-- <strong>{{ $tax_rate }}%</strong> -->

                                                        <!-- @if($tax_rate != 0)
                                                        <br>
                                                        <small>({{ $tax_rate }}% tax)</small>
                                                        @endif -->
                                                    </td>
                                                    <td class="text-right">
                                                        <strong>

                                                            ${{  number_format($cart_subTotal , 2)  }}
                                                        </strong>
                                                    </td>
                                                </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                        @endforeach
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-4 col-sm-offset-8">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <td class="text-right">
                                                            <strong>Sub-Total <small>(Exc. Tax )</small>:</strong>
                                                        </td>
                                                        <td class="text-right"><strong id="SubTotalPrice">$<span class="total_exc_price_bottom">{{ $beforeTaxSubTotal }}</span></strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-right">
                                                            <strong>Tax Total :</strong>
                                                        </td>
                                                        <td class="text-right"><strong id="SubTotalPrice">$<span class="total_tax_bottom">{{ number_format($taxTotal,2) }}</span></strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-right">
                                                            <strong>Sub-Total <small>(Inc. Tax)</small>:</strong>
                                                        </td>
                                                        <td class="text-right">
                                                            <strong id="SubTotalPrice">
                                                                $<span class="total_price_bottom">
                                                                    {{ $subTotalPrice }}
                                                                </span>
                                                            </strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-right">
                                                            <strong>Delivery Charge:</strong>
                                                        </td>
                                                        <td class="text-right" id="shippingRate"><strong>$<span class="total_delivery_charge_bottom">{{ number_format($deliveryCharge,2) }}</span></strong></td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-right">
                                                            <strong>Grand Total:</strong>
                                                        </td>
                                                        <td class="text-right">
                                                            <strong id="TotalPrice">
                                                                $<span class="total_price_bottom">
                                                                    @php
                                                                    $grand_total = $subTotalPrice + $deliveryCharge;
                                                                    session()->put('total_price', $grand_total);
                                                                    session()->save();
                                                                    @endphp
                                                                    {{ number_format($grand_total ,2) }}
                                                                </span>
                                                            </strong
                                                            ></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!--Middle Part End -->
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title"><i class="fa fa-pencil"></i> Add Comments About Your Order</h4>
                        </div>
                        <div class="panel-body">
                            <textarea rows="1" class="form-control" id="confirm_comment" name="message"></textarea>
                            <br>

                            <div class="buttons">
                                <div class="pull-right">
                                    <input type="submit" class="btn btn-primary" id="button-confirm" value="Confirm Order">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


</div>
@endsection

@push('post-scripts')
     <script>

        $('#sameAsShippingAddress').click(function () {

            if (this.checked) {
                // alert($('#cust_region').val());
                $('#input-shipping-name').val($('#input-billing-name').val());
                $('#input-shipping-email').val($('#input-billing-email').val());
                $('#input-shipping-phone').val($('#input-billing-phone').val());
                $('#input-shipping-street-address').val($('#input-billing-street-address').val());
                $('#input-shipping-apt-ste-bldg').val($('#input-billing-apt-ste-bldg').val());
                $('#input-shipping-city').val($('#input-billing-city').val());
                $('#input-shipping-zip-code').val($('#input-billing-zip-code').val());
                $('#input-shipping-country').val($('#input-billing-country').val());
                
                call_ajax_function($('#input-billing-country').val(), $('#input-billing-state').val(), 'input-shipping-state');
                // $('#input-shipping-state').val($('#input-billing-state').val());
                
            } else {
                @guest
                    $('#input-shipping-name').val('');
                    $('#input-shipping-email').val('');
                    $('#input-shipping-phone').val('');
                    $('#input-shipping-street-address').val('');
                    $('#input-shipping-apt-ste-bldg').val('');
                    $('#input-shipping-city').val('');
                    $('#input-shipping-zip-code').val('');
                    $('#input-shipping-country').val('');
                    $('#input-shipping-state').val('');
                @else
                    $('#input-shipping-name').val('{{ isset($shipping_address) ? $shipping_address->name : '' }}');
                    $('#input-shipping-email').val('{{ isset($shipping_address) ? $shipping_address->email : '' }}');
                    $('#input-shipping-phone').val('{{ isset($shipping_address) ? $shipping_address->phone : '' }}');
                    $('#input-shipping-street-address').val('{{ isset($shipping_address) ? $shipping_address->street_address : '' }}');
                    $('#input-shipping-apt-ste-bldg').val('{{ isset($shipping_address) ? $shipping_address->apt_ste_bldg : '' }}');
                    $('#input-shipping-city').val('{{ isset($shipping_address) ? $shipping_address->city : '' }}');
                    $('#input-shipping-zip-code').val('{{ isset($shipping_address) ? $shipping_address->zip_code : '' }}');
                    $('#input-shipping-country').val('{{ isset($shipping_address) ? $shipping_address->country : '' }}');

                    call_ajax_function('{{ isset($shipping_address) ? $shipping_address->country : 0}}', '{{ isset($shipping_address) ? $shipping_address->state : 0 }}', 'input-shipping-state');
                @endguest
                
            }
        });

        $('.billing_shipping_country').change(function(){

            state_input_id =  $(this).data('state-input-id');
            state_id = $(this).data('state-id');
            country_id = $(this).val();

            // alert($('#'+state_input_id).val());
            // return;

            call_ajax_function(country_id, state_id, state_input_id);
            
            // alert($(this).data('state-input-id'));
        });

        $('.billing_shipping_country').each(function(){

            state_input_id =  $(this).data('state-input-id');
            state_id = $(this).data('state-id');
            country_id = $(this).val();

            call_ajax_function(country_id, state_id, state_input_id);
        });

        function call_ajax_function(country_id, state_id, state_input_id) {
            $.ajax({
                url : "{{ URL::route('get-states') }}",
                type: "POST",
                data: {
                        '_token' : '{{ csrf_token() }}',
                        country_id : country_id,
                        state_id : state_id
                    },
                beforeSend: function () {

                },
                success: function (response) {
                    
                   $('#'+state_input_id).html(response); 
                }
            });
        }
    </script>

@endpush