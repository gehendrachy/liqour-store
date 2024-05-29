@extends('admin/layouts.header-sidebar')
@section('title', $vendor->vendor_details->store_name)
@section('content')
<div class="container-fluid">
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-md-12 col-sm-12">
                <h2>{{ $vendor->vendor_details->store_name }}'s Profile</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('vendor.dashboard',['username' => $username])  }}"><i class="icon-speedometer"></i> Dashboard</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('vendor.vendor-orders.list',['username' => $username]) }}"><i class="icon-basket-loaded"></i> Vendor Orders</a>
                        </li>
                        <li class="breadcrumb-item active">
                            #{{ $vendor_order->order->order_no }}
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-md-3">
            <div class="card social">
                <div class="profile-header d-flex justify-content-between justify-content-center">
                    <div class="d-flex">
                        <div class="details">
                            <h5 class="mb-0">{{ $vendor->vendor_details->store_name }}</h5>
                            <span class="text-light">
                                {{ $vendor->email }}
                            </span>
                        </div>                                
                    </div>
                </div>
                <div class="card-body">
                    <a href="{{ route('vendor.dashboard',['username' => $username])  }}" class="btn btn-block btn-round text-left btn-outline-info">
                        <i class="fa fa-user"></i>
                        Dashboard
                    </a>

                    <a href="{{ route('vendor.vendor-settings.list',['username' => $username]) }}" class="btn btn-block btn-round text-left btn-outline-info ">
                        <i class="fa fa-info-circle"></i>
                        Vendor Settings
                    </a>
                    
                    <a href="{{ route('vendor.inventory-products.list',['username' => $username]) }}" class="btn btn-block btn-round text-left btn-outline-info">
                        <i class="fa fa-database"></i>
                        Inventory Products
                    </a>
                    
                    <a href="{{ route('vendor.vendor-orders.list',['username' => $username]) }}" class="btn btn-block btn-round text-left btn-primary ">
                        <i class="icon-basket-loaded"></i>
                        Vendor Orders
                    </a>
                    
                </div>
            </div>                    
        </div>
        <div class="col-md-9">
            <div class="card border-secondary">
                <div class="card-header">
                    <div class="d-flex">
                        <div class="details">
                            <h5>Order Details</h5>
                            <!-- <small>Edit Your Store Details</small> -->
                        </div>                                
                    </div>
                </div>
                <div class="card p-3">
                    <div class="cart-body bg-transparent">
                       <!-- form here -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card bg-secondary">
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
                                    <div class="card-header">
                                        <h5 class="text-white text-center">
                                            Product Details
                                            <div class="btn-group pull-right" role="group">
                                                <button id="btnGroupDrop1" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Change Status for all product
                                                </button>
                                                <div style="width: 100%;" class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                    @for($i = 0; $i < count($orderStatus); $i++)

                                                        <a class="dropdown-item" href="{{ route('vendor.vendor-orders.change-vendor-orders-status', ['username' => $username, 'vendor_order_id' => $vendor_order->id, 'status' => $i ]) }}">{{ $orderStatus[$i] }}</a>
                                                    @endfor

                                                </div>
                                            </div>
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <style type="text/css">
                                            tr, th, td{
                                                border-bottom: 1px solid #d4d4d4 !important;
                                            }

                                            .table td, .table th {
                                                padding: 7px;
                                            }

                                            .order-product-status{
                                                font-size: 9px;
                                            }
                                        </style>
                                        <div class="table-responsive">
                                            <table class="table header-border table-hover table-custom spacing5">
                                                <thead>
                                                    <tr>
                                                        
                                                        <th></th>
                                                        <th>Product Name</th>
                                                        <th>Quantity</th>
                                                        <th class="text-right">Price</th>
                                                        <th class="text-right">Tax</th>
                                                        <th class="text-right">Sub Total</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @php
                                                    $subTotalPrice = 0;
                                                    $grand_total = 0;
                                                    $beforeTaxSubTotal = 0;
                                                    $taxTotal = 0;
                                                    
                                                    $orderStatus = array('0' => ['Pending', 'warning'],
                                                                         '1' => ['On Process', 'primary'],
                                                                         '2' => ['Delivering', 'info'],
                                                                         '3' => ['Delivered', 'success'],
                                                                         '4' => ['Cancelled', 'danger'],
                                                                         '5' => ['Return Requested','danger'],
                                                                         '6' => ['Returned','danger']
                                                                        );

                                                @endphp
                                                @foreach($vendor_order->ordered_products as $key => $item)
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

                                                    $subTotalPrice += $sub_total; 

                                                    @endphp
                                                    <tr>
                                                        
                                                    
                                                        <td class="text-center" style="min-width: 60px;">
                                                            @if(isset($product) && isset($invProd))
                                                            
                                                            <a target="_blank" href="{{ url('product/'.$product->slug) }}">

                                                                <img  src="{{ asset('storage/products/'.$product->slug.'/thumbs/thumb_'.$invProd->product_variation->image) }}" alt="{{ $product->product_name }}" title="{{ $product->product_name }}" class="img-thumbnail" width="50" />

                                                            </a>

                                                            @else

                                                                <small>Image Not Available</small>

                                                            @endif
                                                        </td>
                                                        
                                                        <td class="text-left" >
                                                            @if(isset($product))
                                                            <a target="_blank" href="{{ url('product/'.$product->slug) }}">
                                                                <b>{{ $product->product_name }}</b> 
                                                            </a>
                                                            @else
                                                                <b>{{ $item->product_title }}</b> 
                                                            @endif
                                                            
                                                            <p class="mb-0">{{ $item->variation_name }}</p>

                                                            @if($item->bottle_deposit_rate != 0)
                                                            <small>
                                                                Bottle Deposit :                                     

                                                                {{ $item->pack * (int)$item->quantity }} @ {{ $item->bottle_deposit_rate }}

                                                                = ${{ $bottleDepositPerItem }}
                                                            </small>
                                                            @endif
                                                        </td>

                                                        <td class="text-center" >
                                                            <b>{{(int)$item->quantity}}</b>
                                                        </td>

                                                        <td class="text-right" >
                                                            <strong>
                                                                ${{ $itemPrice }}
                                                            </strong>
                                                        </td>

                                                        <td class="text-right" >
                                                            <strong>
                                                                ${{ $taxTotalPerItem }}
                                                            </strong>
                                                        </td>
                                                        
                                                        <td class="text-right" >
                                                            <strong>

                                                                ${{  number_format($sub_total , 2)  }}
                                                            </strong>
                                                        </td>

                                                        <td class="order-product-status" id="orderedProductStatus{{ $item->id }}">
                                                            <small class="badge badge-{{ $orderStatus[$item->status][1] }}" >
                                                                {{ $orderStatus[$item->status][0] }}
                                                            </small>
                                                        </td>

                                                        <td width="10%">
                                                            <div class="btn-group mb-3" role="group">
                                                                <button id="btnGroupDrop1" type="button" class="btn btn-outline-secondary btn-sm text-right dropdown-toggle" data-toggle="dropdown" >
                                                                    Status
                                                                </button>
                                                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                                    @for($i = 0; $i < count($orderStatus); $i++)
                                                                    <button class="btn btn-info dropdown-item ordered-product-status-btn" data-ordered-product-id="{{ $item->id }}" data-status="{{ $i }}" href="">{{ $orderStatus[$i][0] }}</button>
                                                                    @endfor
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    
                                                @endforeach
                                                <tr>
                                                    <td colspan="5" class="text-right">Sub Total <small>(Exc. Tax)</small> </td>
                                                    <th class="text-right">${{ number_format($beforeTaxSubTotal,2) }}</th>
                                                    <td colspan="3"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" class="text-right">Tax Total </td>
                                                    <th class="text-right">${{ number_format($taxTotal,2) }}</th>
                                                    <td colspan="3"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" class="text-right">Sub Total <small>(Inc. Tax)</small> </td>
                                                    <th class="text-right">${{ number_format($subTotalPrice,2) }}</th>
                                                    <td colspan="3"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" class="text-right">Delivery Charge </td>
                                                    <th class="text-right">${{ number_format($vendor_order->delivery_fee,2) }}</th>
                                                    <td colspan="3"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" class="text-right"> Grand Total </td>
                                                    @php
                                                        $grand_total = $subTotalPrice + $vendor_order->delivery_fee;
                                                    @endphp
                                                    <th class="text-right">${{  number_format($grand_total ,2)  }}</th>
                                                    <td colspan="3"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- .row -->
                        <style>
                            .list-group li > .float-right {
                                font-size: 12px !important;
                            }
                        </style>
                        <div class="row" style="font-size: 12px !important;">

                            <div class="col-md-6">
                                <div class="card border-secondary bg-secondary">
                                    <div class="card-header">
                                        <h5 class="text-white text-center">Billing Details</h5>
                                    </div>
                                    <div class="card-body ">
                                        <strong>{{ $billing_details->billing_name }}</strong><br>
                                        
                                        @if($billing_details->billing_apt_ste_bldg != '')
                                        {{ $billing_details->billing_apt_ste_bldg }}<br>
                                        @endif
                                        
                                        {{ $billing_details->billing_street_address }}<br>
                                        
                                        {{ $billing_details->billing_city }},
                                        
                                        {{ DB::table('states')->where('id',$billing_details->billing_state)->first()->name }}
                                        
                                        {{ $billing_details->billing_zip_code }}<br>
                                        
                                        {{ DB::table('countries')->where('id',$billing_details->billing_country)->first()->name }}

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-secondary bg-secondary">
                                    <div class="card-header">
                                        <h5 class="text-white text-center">Shipping Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <strong>{{ $shipping_details->shipping_name }}</strong><br>
                                        
                                        @if($shipping_details->shipping_apt_ste_bldg != '')
                                        {{ $shipping_details->shipping_apt_ste_bldg }}<br>
                                        @endif

                                        {{ $shipping_details->shipping_street_address }}<br>
                                        
                                        {{ $shipping_details->shipping_city }},
                                        
                                        {{ DB::table('states')->where('id', $shipping_details->shipping_state)->first()->name }}

                                        {{ $shipping_details->shipping_zip_code }}<br>

                                        {{ DB::table('countries')->where('id', $shipping_details->shipping_country)->first()->name }}
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
    <script>
        $(".ordered-product-status-btn").click(function(){
            var status = $(this).data('status');
            var ordered_product_id = $(this).data('ordered-product-id');

            $.ajax({
                url : "{{ URL::route('vendor.vendor-orders.change-ordered-product-status',['username' => $username]) }}",
                type : "POST",
                data :{ '_token': '{{ csrf_token() }}',
                        id: ordered_product_id,
                        status: status
                    },
                beforeSend: function(){                

                },
                success : function(response)
                {
                    console.log("response "+ response);
                    var obj = jQuery.parseJSON( response);

                    if (obj.status == 'success') {
                        
                        
                        $('#orderedProductStatus'+ordered_product_id).load(document.URL + ' #orderedProductStatus'+ordered_product_id+'>*');

                        toastr['success']('Status Updated');
                        

                    }else {

                        toastr['error']('Something went wrong!');
                        

                    };
                }
            });
        });
    </script>
@endsection