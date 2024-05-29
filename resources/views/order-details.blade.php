@extends('layouts.app')
@section('title','Order - '. $order->order_no)
@section('content')
<div class="main-container container">
    <ul class="header-main ">
        <li class="home"><a href="{{ route('home') }}">Home </a><i class="fa fa-angle-right" aria-hidden="true"></i></li>

        <li class="home"><a href="{{ route('customer.my-account') }}">Account </a><i class="fa fa-angle-right" aria-hidden="true"></i></li>

        <li class="home"><a href="{{ route('customer.orders') }}">Orders </a><i class="fa fa-angle-right" aria-hidden="true"></i></li>

        <li> #{{ $order->order_no }}</li>
    </ul>

    <div class="row">
        <!--Middle Part Start-->
        <div id="content" class="col-sm-9">
            <h2 class="title">Order History - #{{ $order->order_no }}</h2>
            <hr>
            <br>


            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <td colspan="2" class="text-left">Order Details</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width: 50%;" class="text-left"> 
                            <b>Order No : </b> #{{ $order->order_no }}
                            <br>
                            <b>Ordered Date : </b> {{ date('jS F, Y H:i:s',strtotime($order->created_at)) }}
                        </td>
                        <td style="width: 50%;" class="text-left"> 
                            <b>Payment Method : </b> {{ $order->payment_method == 1 ? 'Cash On Delivery' : 'Payment Gateway' }}
                            <br>
                            <b>Shipping Method : </b> {{ $order->delivery_method == 1 ? 'Free Shipping' : 'Flat Shipping Rate' }} 
                        </td>
                    </tr>
                    @if($order->message != '')
                    <tr>
                        <td colspan="2">
                            <b>Additional Message : </b> {{ $order->message }}
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
            <div class="table-responsive">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title"><i class="fa fa-shopping-cart"></i> Ordered Products</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row" id="cartTable">
                            
                            <div id="content" class="col-sm-12">
                                <div class=" form-group">
                                    @php 
                                        $subTotalPrice = 0;
                                        $grand_total = 0;
                                        $beforeTaxSubTotal = 0;
                                        $taxTotal = 0;
                                        $deliveryCharge = 0;
                                    @endphp

                                    @foreach ($vendor_orders as $vendor_id => $vendor_order) 

                                    @php
                                        $deliveryCharge += $vendor_order->delivery_fee;
                                    @endphp

                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr class="store-clearance">
                                                <td class="text-left" colspan="2">
                                                    <strong>
                                                        @if($vendor_order->vendor->vendor_details)
                                                        <a href="{{ route('store_details',['vendor_slug' => $vendor_order->vendor->vendor_details->slug]) }}">
                                                            {{ $vendor_order->vendor->vendor_details->store_name }}
                                                        </a>
                                                        @endif
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
                                            @foreach ($vendor_order->ordered_products as $key => $item) 
                                            
                                            @php
                                            
                                            $product = \App\Product::where("id", $item->product_id)->first();
                                            
                                            $invProd = \App\InventoryProduct::where('id', $item->inventory_product_id)->first();

                                            $itemPrice = ($item->sub_total/$item->quantity);

                                            $bottleDepositPerItem = $item->pack * (int)$item->quantity * $item->bottle_deposit_rate;

                                            $beforeTaxSubTotalPerItem =  ($itemPrice * $item->quantity) + $bottleDepositPerItem;
                                            
                                            $beforeTaxSubTotal = $beforeTaxSubTotal + $beforeTaxSubTotalPerItem;
                                            
                                            $taxPrice = $itemPrice * ($item->tax_rate/100);
                                            $taxPrice = number_format($taxPrice, 2);

                                            $taxTotalPerItem = $taxPrice*$item->quantity;

                                            $taxTotal = $taxTotal + $taxTotalPerItem;

                                            // $unitPrice = number_format($itemPrice + $taxPrice, 2);

                                            $sub_total = number_format(($beforeTaxSubTotalPerItem + $taxTotalPerItem), 2);

                                            $subTotalPrice += $sub_total ; 

                                            $orderStatus = array('0' => 'Pending',
                                                             '1' => 'On Process',
                                                             '2' => 'Delivering',
                                                             '3' => 'Delivered',
                                                             '4' => 'Cancelled',
                                                             '5' => 'Return Requested',
                                                             '6' => 'Returned'
                                                            );

                                            @endphp

                                            <tr>
                                                
                                                <td class="text-center" width="100">
                                                    @if(isset($product) && isset($invProd))
                                                    
                                                    <a href="{{ url('product/'.$product->slug) }}">

                                                        <img width="70px" src="{{ asset('storage/products/'.$product->slug.'/thumbs/thumb_'.$invProd->product_variation->image) }}" alt="{{ $product->product_name }}" title="{{ $product->product_name }}" class="img-thumbnail" />

                                                    </a>

                                                    @else

                                                        <small>Image Not Available</small>

                                                    @endif
                                                </td>
                                                <td class="text-left" width="480">
                                                    @if(isset($product))
                                                        <a href="{{ url('product/'.$product->slug) }}">
                                                            <b>{{ $product->product_name }}</b> 
                                                        </a>
                                                    @else
                                                        <b>{{ $item->product_title }}</b> 
                                                    @endif
                                                    <small>
                                                        <i class="pull-right">{{ $orderStatus[$item->status] }}</i>
                                                    </small>

                                                    <p>
                                                        {{ $item->variation_name }}
                                                    </p>

                                                    @if($item->bottle_deposit_rate != 0)
                                                        <p>
                                                            Bottle Deposit :                                     

                                                            {{ $item->pack * (int)$item->quantity }} @ {{ $item->bottle_deposit_rate }}

                                                            = ${{ $bottleDepositPerItem }}
                                                        </p>
                                                    @endif
                                                </td>   
                                                <td class="text-center" width="150px">
                                                    <b>{{(int)$item->quantity}}</b>
                                                </td>

                                                <td class="text-right" width="100px">
                                                    <strong>${{ $itemPrice }}</strong>
                                                    <!-- @if($item->tax_rate != 0)
                                                    <br>
                                                    <small>(Inc. {{ $item->tax_rate }}% tax)</small>
                                                    @endif -->
                                                </td>
                                                <td class="text-right" width="100px">
                                                    <strong>${{ number_format($taxTotalPerItem, 2) }}</strong>
                                                    <br>
                                                </td>
                                                <td class="text-right" width="100px">
                                                    <strong>${{  number_format($sub_total , 2)  }}</strong>
                                                </td>
                                            </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="5" class="text-right">
                                                    <b>Order Status</b>
                                                </td>
                                                <td>
                                                    @php 
                                                        $orderStatus = array('0' => 'Pending',
                                                                             '1' => 'On Process',
                                                                             '2' => 'Delivering',
                                                                             '3' => 'Delivered',
                                                                             '4' => 'Cancelled',
                                                                             '5' => 'Return Requested',
                                                                             '6' => 'Returned'
                                                                            );
                                                    @endphp
                                                    <b style="color: #722f37;">{{ $orderStatus[$vendor_order->status] }}</b>
                                                </td>
                                            </tr>
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
                                                        <strong>Sub-Total <small>(Exc. Tax)</small>:</strong>
                                                    </td>
                                                    <td class="text-right">
                                                        <strong id="SubTotalPrice">
                                                            $<span class="total_price_bottom">

                                                                {{ $beforeTaxSubTotal }}
                                                            </span>
                                                        </strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">
                                                        <strong>Tax-Total :</strong>
                                                    </td>
                                                    <td class="text-right">
                                                        <strong id="SubTotalPrice">
                                                            $<span class="total_price_bottom">

                                                                {{ number_format($taxTotal,2) }}
                                                            </span>
                                                        </strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">
                                                        <strong>Sub-Total <small>(Inc. Tax)</small>:</strong>
                                                    </td>
                                                    <td class="text-right">
                                                        <strong id="SubTotalPrice">
                                                            $<span class="total_price_bottom">

                                                                {{ number_format($subTotalPrice,2) }}
                                                            </span>
                                                        </strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">
                                                        <strong>Delivery Charge:</strong>
                                                    </td>
                                                    <td class="text-right" id="shippingRate">
                                                        <strong>
                                                            $<span class="total_delivery_charge_bottom">
                                                                {{ number_format($deliveryCharge,2) }}
                                                            </span>
                                                        </strong>
                                                    </td>
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
                                                                @endphp
                                                                {{ number_format($grand_total ,2) }}
                                                            </span>
                                                        </strong>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            

                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 50%; vertical-align: top;" class="text-left">Billing Details</th>
                        <th style="width: 50%; vertical-align: top;" class="text-left">Shipping Details</th>
                    </tr>
                </thead>
                <tbody>
                    @if($billing_details)
                    <tr>
                        <td class="text-left">
                            <b>{{ $billing_details->billing_name }}</b><br>
                            @if($billing_details->billing_apt_ste_bldg != '')
                            {{ $billing_details->billing_apt_ste_bldg }}<br>
                            @endif
                            {{ $billing_details->billing_street_address }}<br>
                            {{ $billing_details->billing_city }},
                            {{ DB::table('states')->where('id', $billing_details->billing_state)->first()->name }}
                            {{ $billing_details->billing_zip_code }}<br>
                            {{ DB::table('countries')->where('id', $billing_details->billing_country)->first()->name }}
                            
                            
                            
                        </td>
                        <td class="text-left">
                            <b>{{ $shipping_details->shipping_name }}</b><br>
                            @if($shipping_details->shipping_apt_ste_bldg != '')
                            {{ $shipping_details->shipping_apt_ste_bldg }}<br>
                            @endif
                            {{ $shipping_details->shipping_street_address }}<br>
                            {{ $shipping_details->shipping_city }},
                            {{ DB::table('states')->where('id', $shipping_details->shipping_state)->first()->name }}
                            {{ $shipping_details->shipping_zip_code }}<br>
                            {{ DB::table('countries')->where('id', $shipping_details->shipping_country)->first()->name }}
                            
                            
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>

        </div>
        <!--Middle Part End-->
        <!--Right Part Start -->
        <aside class="col-sm-3 hidden-xs" id="column-right">
            <h2 class="subtitle">Account</h2>
            <div class="list-group">
                <ul class="list-item">
                    <li><a href="{{ route('customer.my-account') }}">My Account</a>
                    </li>
                    <li><a href="{{ route('customer.account-settings') }}">Account Settings</a></li>
                    <li><a href="{{ route('customer.wishlist') }}">Wish List</a></li>
                    <li><b><a href="{{ route('customer.orders') }}">Order History</a></b>
                    </li>
                </ul>
            </div>
        </aside>
        <!--Right Part End -->
    </div>
</div>

@endsection

@push('post-scripts')


@endpush