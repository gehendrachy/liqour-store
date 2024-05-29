@extends('layouts.app')
@section('title','Shopping Cart')
@section('content')
<div class="main-container container">
    <ul class="header-main ">
        <li class="home"><a href="{{ route('home') }}">Home   </a><i class="fa fa-angle-right" aria-hidden="true"></i></li>
        <li> Shopping Cart</li>
    </ul>

    <ul class="breadcrumb">
        <li>
            <a href="javascript:void(0)" class="active">Shopping Cart</a>
        </li>
        <li>
            <a href="javascript:void(0)">Billing / Shipping Address</a>
        </li>
        <li>
            <a href="javascript:void(0)">Order Status</a>
        </li>
    </ul>

    <div class="row" id="cartTable">
        @if(count($cartByVendor)>0)
        <!--Middle Part Start-->
        <form method="POST" action="{{ route('update-checked-cart-products') }}">
        @csrf
        <div id="content" class="col-sm-12">
            <h2 class="title">Shopping Cart</h2>
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

                @if (array_key_exists($vendor->id, $cartByVendor)) 
                    @php
                        $cartVendorSubTotal = $cartByVendor[$vendor->id]->sum('cart_subTotal');
                    @endphp


                    @if ($cartVendorSubTotal < $vendor->vendor_details->minimum_order) 
                        
                        @php
                            $qualify = 0;
                            $remainingTotal = $vendor->vendor_details->minimum_order - $cartVendorSubTotal;
                        @endphp
                        
                    @else
                        @php
                            $qualify = 1;
                        @endphp
                        
                    @endif
                @endif


                <table class="table table-bordered">
                    <tbody>
                        <tr class="store-clearance">
                            <td class="text-center">
                                <input type="checkbox" name="store_{{ $vendor->vendor_details->store_name }}" checked value="1" class="store_checkbox" id="store_checkbox_{{ $vendor_id }}" data-vendor-id="{{ $vendor_id }}" data-vendor-minimum-order="{{ $vendor->vendor_details->minimum_order }}" 
                                data-vendor-delivery-fee="{{ $vendor->vendor_details->delivery_fee }}"
                                data-class-name="store_{{ $vendor_id }}">
                            </td>
                            <td class="text-left" colspan="2">
                                <strong>
                                    <a href="{{ route('store_details',['vendor_slug' => $vendor->vendor_details->slug]) }}">
                                        {{ $vendor->vendor_details->store_name }}
                                    </a>
                                </strong>
                                @if($qualify == 0)
                                    <p id="qualifyMessage{{ $vendor_id }}" style="margin: 0px; color: red;">Please add ${{ $remainingTotal }}  more from this store to meet minimum order.</p>
                                @else
                                    <p id="qualifyMessage{{ $vendor_id }}" style="margin: 0px; color: green;">Your order qualifies for delivery.</p>
                                @endif
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
                            <td class="text-center" width="50">
                                <input type="checkbox" name="checked_cart_id[]" checked value='{{ $item["cart_id"] }}' class="store_{{ $vendor_id }} store_product_item vendor_product_item_{{ $vendor_id }}" data-vendor-id="{{ $vendor->id }}" data-class-name="store_{{ $vendor_id }}" data-sub-total-tax="{{ $taxTotalPerItem }}" data-cart-sub-total="{{ $item['cart_subTotal'] }}" data-sub-total-exc-price="{{ $beforeTaxSubTotalPerItem }}" data-sub-total-price="{{ (float)number_format($cart_subTotal , 2)  }}" data-qualify="{{ $qualify }}">
                            </td>
                            
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
                            <td class="text-left" width="150px">
                                <div class="input-group btn-block quantity">
                                    <input type="number" name="quantity" class="form-control ordered_qty" data-cart-id="{{ $item['cart_id'] }}" value="{{(int)$item['cart_orderedQty']}}" min="1" max="{{ $invProd->stock }}" />

                                    <!-- <span class="input-group-btn">
                                        <button type="submit" data-toggle="tooltip" title="Update" class="btn btn-primary"><i class="fa fa-clone"></i></button>

                                    </span> -->
                                </div>
                            </td>

                            <td class="text-right" width="100px">
                                <strong>
                                    ${{ $itemPrice }}
                                </strong>

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
                            <!-- <td class="text-center" style="vertical-align: middle;">
                                <i onclick="cartDelete('{{ $key }}','{{ addslashes($cProd->product_name) }}','{{ $cProd->slug }}', '{{ $cProd->image }}')" style="color: #e74c3c;" class="fa fa-lg fa-times-circle"></i>
                            </td> -->
                        </tr>
                        @endforeach

                        <!-- <tr>
                            <td colspan="4" class="text-right">
                                <strong>Sub Total</strong>
                            </td>
                            <td class="text-right">
                                <strong>$85.55</strong>
                            </td>
                            <td class="text-right">
                                <strong>$4.78</strong>
                            </td>
                            <td class="text-right">
                                <strong>$92.39</strong>
                            </td>
                        </tr> -->
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
                                    <td class="text-right"><strong>$<span class="total_exc_price_bottom">{{ $beforeTaxSubTotal }}</span></strong></td>
                                </tr>

                                <tr>
                                    <td class="text-right">
                                        <strong>Tax Total :</strong>
                                    </td>
                                    <td class="text-right"><strong>$<span class="total_tax_bottom">{{ number_format($taxTotal,2) }}</span></strong></td>
                                </tr>
                                <tr>
                                    <td class="text-right">
                                        <strong>Sub-Total <small>(Inc. Tax)</small>:</strong>
                                    </td>
                                    <td class="text-right"><strong>$<span class="total_price_bottom">{{ number_format($subTotalPrice,2) }}</span></strong></td>
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
                                <td class="text-right"><strong id="TotalPrice">$<span class="grand_total_price_bottom">{{ number_format($subTotalPrice + $deliveryCharge ,2) }}</span></strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="buttons">
                <div class="pull-left"><a href="{{ route('home') }}" class="btn btn-primary">Continue Shopping</a></div>

                <div class="pull-right">
                    <button id="checkOutBtn" type="submit" class="btn btn-primary">Checkout</button>
                    <!-- <a href="javascript:void(0)" class="btn btn-primary">Checkout*</a> -->
                </div>
            </div>
            <!-- <br><br>
            <div class="pull-right">
                <small style="color: #722f37;">*Checkout Coming Soon</small>
            </div> -->
        </div>
        </form>
        <!--Middle Part End -->
        @else
        <div class="col-sm-12 col-xs-12 text-center alert alert-danger">
            <h3>No items in the cart!!! </h3>
            <a href="{{ route('home') }}" class="btn btn-primary">Continue Shopping</a>
        </div>
        @endif

    </div>
</div>
@endsection

@push('post-scripts')

<script>
    function calculate_total_price(){
        var total_price = 0;
        var total_exc_price = 0;
        var total_delivery_charge = 0;
        var grand_total_price = 0;

        var tax_total = 0;
        var qualify = 1;

        $(".store_product_item").each(function(){
            
            var vendor_id = $(this).data('vendor-id');

            if ($(this).is(':checked')) {

                var sub_total_price = parseFloat($(this).data('sub-total-price'));
                total_price = total_price + sub_total_price;

                var sub_total_exc_price = parseFloat($(this).data('sub-total-exc-price'));
                total_exc_price = total_exc_price + sub_total_exc_price;

                var sub_total_tax = parseFloat($(this).data('sub-total-tax'));
                tax_total = tax_total + sub_total_tax;

                // ============================================================================

                

                if ($('.vendor_product_item_'+vendor_id+':checked').length > 0) {
                    // ===================================Tax and Minimum Order Part===========
                    var total_exc_price_temp = 0;

                    var minimum_order = $('#store_checkbox_'+vendor_id).data('vendor-minimum-order');

                    $('.vendor_product_item_'+vendor_id+':checked').each(function(){

                        var sub_total_exc_price_temp = parseFloat($(this).data('cart-sub-total'));
                        total_exc_price_temp = total_exc_price_temp + sub_total_exc_price_temp;

                    });

                    if (total_exc_price_temp < minimum_order) {

                        var remainingTotal = minimum_order - total_exc_price_temp;

                        $("#qualifyMessage"+vendor_id).css('color','red');
                        $("#qualifyMessage"+vendor_id).html('Please add $'+ remainingTotal.toFixed(2) + ' more from this store to meet minimum order.');

                        qualify = 0;

                    }else{

                        $("#qualifyMessage"+vendor_id).css('color','green');
                        $("#qualifyMessage"+vendor_id).html('Your order qualifies for delivery.');

                    }



                }
                
                // if ($(this).data('qualify') == 0) {
                //     qualify = 0;
                // }
            }

        });

        $(".store_checkbox").each(function(){
            var vendor_id = $(this).data('vendor-id');

            if ($('.vendor_product_item_'+vendor_id+':checked').length > 0) {

                var vendor_delivery_charge = parseFloat($(this).data('vendor-delivery-fee'));
                total_delivery_charge = total_delivery_charge + vendor_delivery_charge;
            }else{
                    $("#qualifyMessage"+vendor_id).html('');
                }
        });

        

        if (qualify != 1) {
            $("#checkOutBtn").attr('disabled',true);
        }else{
            $("#checkOutBtn").attr('disabled',false);
        }
        // alert(qualify);
        // console.log($(".store_product_item:checked").length);
        
        $(".total_tax_bottom").html(tax_total.toFixed(2));
        $(".total_exc_price_bottom").html(total_exc_price.toFixed(2));
        $(".total_price_bottom").html(total_price.toFixed(2));
        $(".total_delivery_charge_bottom").html(total_delivery_charge.toFixed(2));
        $(".grand_total_price_bottom").html((total_price+total_delivery_charge).toFixed(2));
    } 

    calculate_total_price();

    $(".store_product_item").click(function(){
        // var store_checkbox_id = $(this).data('store-checkbox-id');
        // var total_exc_price = 0;
        var vendor_id = $(this).data('vendor-id');

        // var minimum_order = $('#store_checkbox_'+vendor_id).data('vendor-minimum-order');

        // $('.vendor_product_item_'+vendor_id+':checked').each(function(){
        //     var sub_total_exc_price = parseFloat($(this).data('sub-total-exc-price'));
        //     total_exc_price = total_exc_price + sub_total_exc_price;
        // });

        // if (total_exc_price < minimum_order) {
        //     $('.vendor_product_item_'+vendor_id).attr('data-qualify', 0);
            
        // }else{
        //     $('.vendor_product_item_'+vendor_id).attr('data-qualify', 1);
        // }
        // alert(total_exc_price.toFixed(2));

        if($('.vendor_product_item_'+vendor_id+':checked').length == $('.vendor_product_item_'+vendor_id).length){
            
            $("#store_checkbox_"+vendor_id).prop('checked',true);
        }else{
            
            $("#store_checkbox_"+vendor_id).prop('checked',false);
        }

        calculate_total_price();
    });


    $(".store_checkbox").click(function(){
        var store_class_name = $(this).data('class-name');

        if (this.checked) {

            $('.'+store_class_name).each(function () {     
                $(this).prop('checked', true);   
            });

        } else {

            $('.'+store_class_name).each(function () { 
                $(this).prop('checked', false); 
            });
        }

        calculate_total_price();
    });

    $(".ordered_qty").change(function(){
        cart_id = $(this).data('cart-id');
        qty = $(this).val();

        $.ajax({
            url : "{{ URL::route('update-cart') }}",
            type : "POST",
            data : {
                '_token': '{{ csrf_token() }}',
                cart_id: cart_id,
                qty: qty
            },
            beforeSend : function (){

            },
            complete : function(response){
                // console.log(response.responseText);
                var obj = jQuery.parseJSON(response.responseText);

                if (obj.status =='success') {
                    location.reload();
                }
            },
            error : function ($responseObj){
                alert("Something went wrong while processing your request.\n\nError => "
                    + $responseObj.responseText);
            }
        }); 

    });
</script>
@endpush