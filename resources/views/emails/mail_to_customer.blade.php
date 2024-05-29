<html>
<head>
    <style>
        body {
            width: 100%;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            font-family: Georgia, Times, serif;
        }

        table {
            border-collapse: collapse;
        }

        td#logo {
            margin: 0 auto;
            padding: 14px 0;
        }

        img {
            border: none;
            display: block;
        }

        a.blue-btn {
            display: inline-block;
            margin-bottom: 34px;
            border: 3px solid #3baaff;
            padding: 11px 38px;
            font-size: 12px;
            font-family: arial;
            font-weight: bold;
            color: #3baaff;
            text-decoration: none;
            text-align: center;
        }

        a.blue-btn:hover {
            background-color: #3baaff;
            color: #fff;
        }

        a.white-btn {
            display: inline-block;
            margin-bottom: 30px;
            border: 3px solid #fff;
            background: transparent;
            padding: 11px 38px;
            font-size: 12px;
            font-family: arial;
            font-weight: bold;
            color: #fff;
            text-decoration: none;
            text-align: center;
        }

        a.white-btn:hover {
            background-color: #fff;
            color: #3baaff;
        }

        .border-complete {
            border-top: 1px solid #dadada;
            border-left: 1px solid #dadada;
            border-right: 1px solid #dadada;
        }

        .border-lr {
            border-left: 1px solid #dadada;
            border-right: 1px solid #dadada;
        }

        #banner-txt {
            color: #fff;
            padding: 15px 32px 0px 32px;
            font-family: arial;
            font-size: 13px;
            text-align: center;
        }

        h2#our-products {
            font-family: \'Pacifico\';
            margin: 23px auto 5px auto;
            font-size: 27px;
            color: #3baaff;
        }

        h3.our-products {
            font-family: arial;
            font-size: 15px;
            color: #7c7b7b;
        }

        p.our-products {
            text-align: center;
            font-family: arial;
            color: #7c7b7b;
            font-size: 12px;
            padding: 10px 10px 24px 10px;
        }

        h2.special {
            margin: 0;
            color: #fff;
            color: #fff;
            font-family: \'Pacifico\';
            padding: 15px 32px 0px 32px;
        }

        p.special {
            color: #fff;
            font-size: 12px;
            color: #fff;
            text-align: center;
            font-family: arial;
            padding: 0px 32px 10px 32px;
        }

        h2#coupons {
            color: #3baaff;
            text-align: center;
            font-family: \'Pacifico\';
            margin-top: 30px;
        }

        p#coupons {
            color: #7c7b7b;
            text-align: center;
            font-size: 12px;
            text-align: center;
            font-family: arial;
            padding: 0 32px;
        }

        #socials {
            padding-top: 12px;
        }

        p#footer-txt {
            text-align: center;
            color: #303032;
            font-family: arial;
            font-size: 12px;
            padding: 0 32px;
        }

        #social-icons {
            width: 28%;
        }

        @media only screen and (max-width: 640px) {
            body[yahoo] .deviceWidth {
                width: 440px!important;
                padding: 0;
            }
            body[yahoo] .center {
                text-align: center!important;
            }
            #social-icons {
                width: 40%;
            }
        }

        @media only screen and (max-width: 479px) {
            body[yahoo] .deviceWidth {
                width: 280px!important;
                padding: 0;
            }
            body[yahoo] .center {
                text-align: center!important;
            }
            #social-icons {
                width: 60%;
            }
        }

        .product-table th,.product-table td{
            border: 1px solid black;
            padding: 4px 8px;
            text-align: center;
        }

        a{
            text-decoration: none;
            color: #722f37;
        }

        tr{
            border-bottom: 1px solid #bdbdbd
        }
    </style>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" yahoo="fix" style="font-family: Helvetica, sans-serif">

    


        <!-- Start Header-->
        <table width="900" style="background-color: #722f37;" border="0" cellpadding="0" cellspacing="0" align="center" class="border-complete deviceWidth" bgcolor="#e9e9e9">
            <tr>
                <td width="100%">
                    <!-- Logo -->
                    <table border="0"  cellpadding="0" cellspacing="0" align="center" class="deviceWidth">
                        <tr>
                            <td id="logo" style="text-align: center;" >
                                <h2 style="color: #fff; padding-top: 15px; font-family: arial">{{ $orderMessage['subject'] }}</h2>
                            </td>
                        </tr>
                    </table>
                    <!-- End Logo -->
                </td>
            </tr>
        </table>
        <!-- End Header -->

        <!-- Banner Text -->
        <table width="900" height="108" border="0" cellpadding="0" cellspacing="0" align="center" class="border-lr deviceWidth" bgcolor="#ecf0fb">
            <tr>
                <td colspan="2" style="padding: 40px; color: #095583; text-align: center;">
                    <strong>Your order is placed!</strong>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-left: 40px;padding-right: 40px; padding-top: 40px; color: #095583; font-size: 12px;">
                    <p>Hi <strong>{{ $orderMessage['order']->customer_name }}</strong> ,</p>

                    <p>Thank you for ordering from Liquor Store!</p>

                    <p>We're excited for you to receive your order <strong>#{{ $orderMessage['order']->order_no }}</strong> and will notify you once it's on its way. If you have ordered from multiple sellers, your items will be delivered in separate packages. We hope you had a great shopping experience! You can check your order status here. </p>

                    <div style="text-align: center; margin-top: 10px; padding: 20px;">
                        <a target="_blank" style="background-color: #722f37; color: white; padding: 8px;" href="{{ route('customer.view-order',['order_no' => base64_encode($orderMessage['order']->order_no)]) }}">ORDER STATUS</a>
                    </div>

                    <p>Please note, we are unable to change your delivery address once your order is placed.​</p>

                    <p>Here's a confirmation of what you bought in your order.</p>
                    <br>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-left: 40px;padding-right: 40px; padding-top: 40px; color: #095583;">
                    @php 
                        $subTotalPrice = 0;
                        $grand_total = 0;
                        $beforeTaxSubTotal = 0;
                        $taxTotal = 0;
                        $deliveryCharge = 0;
                    @endphp

                    @foreach ($orderMessage['vendor_orders'] as $vendor_id => $vendor_order) 
                    
                    @php
                        $deliveryCharge += $vendor_order->delivery_fee;
                    @endphp

                    <table>
                        <tbody>
                            <tr style="background:#999; color:#fff; padding:10px;text-transform:uppercase; font-size:14px">
                                <td style="padding: 10px">
                                    <strong>
                                        @if($vendor_order->vendor->vendor_details)
                                        <a href="{{ route('store_details',['vendor_slug' => $vendor_order->vendor->vendor_details->slug]) }}">
                                            {{ $vendor_order->vendor->vendor_details->store_name }}
                                        </a>
                                        @endif
                                    </strong>
                                </td>
                                <td style="padding: 10px;">
                                    <strong>Ordered Quantity</strong>
                                </td>
                                <td style="padding: 10px;">
                                    <strong>Unit Price</strong>
                                </td>
                                <td style="padding: 10px;">
                                    <strong>Tax</strong>
                                </td>
                                <td style="padding: 10px;">
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

                                    $sub_total = number_format(($beforeTaxSubTotalPerItem + $taxTotalPerItem), 2);

                                    $subTotalPrice += $sub_total ; 

                                    $orderStatus = array('0' => 'Pending',
                                                         '1' => 'On Process',
                                                         '2' => 'Delivering',
                                                         '3' => 'Delivered',
                                                         '4' => 'Cancelled'
                                                        );

                                @endphp

                                <tr style="padding:10px;font-size:12px;">
                                
                                    <td style="padding: 10px;" width="480">
                                        @if(isset($product))
                                            <a href="{{ url('product/'.$product->slug) }}">
                                                <b>{{ $product->product_name }}</b> 
                                            </a>
                                        @else
                                            <b>{{ $item->product_title }}</b> 
                                        @endif

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

                                    <td style="padding: 10px;" width="150px">
                                        <b>{{(int)$item->quantity}}</b>
                                    </td>

                                    <td style="padding: 10px;" width="100px">
                                        <strong>${{ $itemPrice }}</strong>
                                    </td>
                                    <td style="padding: 10px;" width="100px">
                                        <strong>${{ $taxTotalPerItem }}</strong>
                                    </td>
                                    <td style="padding: 10px;" width="100px">
                                        <strong>${{  number_format($sub_total , 2)  }}</strong>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endforeach
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-left: 40px;padding-right: 40px; color: #095583;">
                    <table>
                        <tbody style="padding-left: 40px;padding-right: 40px; padding-bottom: 40px; font-size:12px;">
                            <tr style="padding:10px;font-size:12px;">
                                <td style="padding: 10px; color: #722f37;" width="730">
                                    <strong style="float: right;">Sub-Total <small>(Exc. Tax)</small>:</strong>
                                </td>

                                <td style="padding: 10px; text-align: center;" width="100px">
                                    <strong>${{ $beforeTaxSubTotal }}</strong>
                                </td>
                            </tr>

                            <tr style="padding:10px;font-size:12px;">
                                <td style="padding: 10px; color: #722f37;" width="730">
                                    <strong style="float: right;">Tax Total :</strong>
                                </td>
                                <td style="padding: 10px; text-align: center;" width="100px">
                                    <strong>${{ number_format($taxTotal,2) }}</strong>
                                </td>
                            </tr>

                            <tr style="padding:10px;font-size:12px;">
                                <td style="padding: 10px; color: #722f37;" width="730">
                                    <strong style="float: right;">Sub-Total <small>(Inc. Tax)</small>:</strong>
                                </td>
                                <td style="padding: 10px; text-align: center;" width="100px">
                                    <strong>${{ number_format($subTotalPrice,2) }}</strong>
                                </td>
                            </tr>


                            <tr style="padding:10px;font-size:12px;">
                                <td style="padding: 10px; color: #722f37;" width="730">
                                    <strong style="float: right;">Delivery Charge </strong>
                                </td>
                                <td style="padding: 10px; text-align: center;" width="100px">
                                    <strong>${{ number_format($deliveryCharge,2) }}</strong>
                                </td>
                            </tr>

                            <tr style="padding:10px;font-size:12px;">
                                <td style="padding: 10px; color: #722f37;" width="730">
                                    <strong style="float: right;">Grand Total </strong>
                                </td>
                                <td style="padding: 10px; text-align: center;" width="100px">
                                    <strong>
                                        @php
                                            $grand_total = $subTotalPrice + $deliveryCharge;
                                        @endphp
                                        ${{ number_format($grand_total ,2) }}
                                    </strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>

            <tr style="border-bottom: 2px solid #555;">
                <td width="50%" style="padding-left: 40px;padding-right: 40px; padding-bottom: 40px;">
                    <br>
                    <table>
                        <tbody style="padding-left: 40px;padding-right: 40px; padding-bottom: 40px; font-size:12px;">
                            <tr>
                               <th style="padding: 10px; text-transform: uppercase; color: #722f37;"><h3>Billing Information </h3></th> 
                            </tr>

                            <tr>
                                <td style="padding: 10px; ">
                                    <b>{{ $orderMessage['billing_details']->billing_name }}</b><br>
                                    @if($orderMessage['billing_details']->billing_apt_ste_bldg != '')
                                    {{ $orderMessage['billing_details']->billing_apt_ste_bldg }}<br>
                                    @endif
                                    {{ $orderMessage['billing_details']->billing_street_address }}<br>
                                    {{ $orderMessage['billing_details']->billing_city }},
                                    {{ DB::table('states')->where('id', $orderMessage['billing_details']->billing_state)->first()->name }}
                                    {{ $orderMessage['billing_details']->billing_zip_code }}<br>
                                    {{ DB::table('countries')->where('id', $orderMessage['billing_details']->billing_country)->first()->name }}
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
                </td>        
            
                <td width="50%" style="padding-left: 40px;padding-right: 40px; padding-bottom: 40px;">
                    <br>
                    <table>
                        <tbody style="padding-left: 40px;padding-right: 40px; padding-top: 40px; padding-bottom: 40px; font-size:12px;">
                            <tr>
                               <th style="padding: 10px;text-align: left; text-transform: uppercase; color: #722f37;"><h3>Shipping Information </h3></th> 
                            </tr>

                            <tr>
                                <td style="padding: 10px; ">
                                    <b>{{ $orderMessage['shipping_details']->shipping_name }}</b><br>
                                    @if($orderMessage['shipping_details']->shipping_apt_ste_bldg != '')
                                    {{ $orderMessage['shipping_details']->shipping_apt_ste_bldg }}<br>
                                    @endif
                                    {{ $orderMessage['shipping_details']->shipping_street_address }}<br>
                                    {{ $orderMessage['shipping_details']->shipping_city }},
                                    {{ DB::table('states')->where('id', $orderMessage['shipping_details']->shipping_state)->first()->name }}
                                    {{ $orderMessage['shipping_details']->shipping_zip_code }}<br>
                                    {{ DB::table('countries')->where('id', $orderMessage['shipping_details']->shipping_country)->first()->name }}
                                </td>
                                
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding-left: 40px;padding-right: 40px; padding-top:0 px;">
                    <small>
                        <p>Additional Message : {{ $orderMessage['order']->message }}</p>
                        <p>Ordered Date : {{ $orderMessage['ordered_date'] }}</p>
                        <p>Payment Method : {{ $orderMessage['order']->payment_method == 1 ? 'Cash on Delivery' : 'Payment Gateway' }}</p>
                        <p>Delivery Method : {{ $orderMessage['order']->delivery_method == 1 ? 'Free Shipping' : 'Standard' }}</p>
                    </small>
                </td>
            </tr>
            <tr>
                <td style="padding-left: 40px;padding-right: 40px; padding-top:0 px;">
                    <small>
                        <p style="opacity: 0.5;">PLEASE DO NOT REPLY TO THIS EMAIL</p>

                        <p style="opacity: 0.5;">This is an auto-generated email, replies to this email are not responded.</p>

                        <p style="opacity: 0.5;">Please contact at <a href="mailto:{{ \App\Setting::where('id', 1)->first()->siteemail }}">{{ \App\Setting::where('id', 1)->first()->siteemail }}</a> for queries.</p>
                    </small>
                </td>
            </tr>
        </table>
        <!-- End of Banner Text -->

        <!-- Footer -->
        <table width="900" border="0" cellpadding="0" cellspacing="0" align="center" class="border-complete deviceWidth" bgcolor="#b6b6b6">
            <tr>
                <td style="text-align: center;">
                    <p id="footer-txt" style="padding-top: 20px; padding-bottom: 20px; color:#eeeeed ">
                        <b>© Copyright  {{ date("Y") }} - Liquor Store - All Rights Reserved</b>
                    </p>
                </td>
            </tr>
        </table>
        <!-- End of Footer-->
    
</body>     
</html>;