@extends('layouts.app')
@section('title','My Account')
@section('content')
<div class="main-container container">
    <ul class="header-main ">
        <li class="home"><a href="{{ route('home') }}">Home </a><i class="fa fa-angle-right" aria-hidden="true"></i></li>
        <li class="home"><a href="{{ route('customer.my-account') }}">Account </a><i class="fa fa-angle-right" aria-hidden="true"></i></li>
        <li>  My Account</li>
    </ul>

    <div class="row">
        <!--Middle Part Start-->
        <div class="col-sm-9 type-2" id="content">
            <h2 class="title">My Account</h2>
            <hr>
            <br>
            <p class="lead">Hello, <strong>{{ $customer->name }}!</strong> - <small>This page contains the overview of your profile.</small></p>
            <br>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th colspan="" class="text-left"><h4>Personal Details</h4></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width: 50%;" class="text-left"> 
                            <b>Full Name:</b> {{ $customer->name }}
                            <br>
                            <b>Email Address:</b> {{ $customer->email }}
                            <br>
                            <b>Contact No:</b> {{ $customer->phone }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <br>
            <hr>
            <br>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 50%; vertical-align: top;" class="text-left">Billing Details</th>
                        <th style="width: 50%; vertical-align: top;" class="text-left">Shipping Details</th>
                    </tr>
                </thead>
                <tbody>
                    @if($billing_address)
                    <tr>
                        <td class="text-left">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                    <tr>
                                        <td width="30%">Name</td>
                                        <td width="70%"><b>{{ $billing_address->name }}</b></td>
                                    </tr>
                                    <tr>
                                        <td width="30%">Email</td>
                                        <td width="70%"><b>{{ $billing_address->email }}</b></td>
                                    </tr>
                                    <tr>
                                        <td width="30%">Phone</td>
                                        <td width="70%"><b>{{ $billing_address->phone }}</b></td>
                                    </tr>
                                    <tr>
                                        <td width="30%">Street Address</td>
                                        <td width="70%"><b>{{ $billing_address->street_address }}</b></td>
                                    </tr>
                                    <tr>
                                        <td width="30%">Apartment #/ Suite / Building</td>
                                        <td width="70%"><b>{{ $billing_address->apt_ste_bldg }}</b></td>
                                    </tr>
                                    <tr>
                                        <td width="30%">City</td>
                                        <td width="70%"><b>{{ $billing_address->city }}</b></td>
                                    </tr>
                                    <tr>
                                        <td width="30%">Zip Code</td>
                                        <td width="70%"><b>{{ $billing_address->zip_code }}</b></td>
                                    </tr>
                                    <tr>
                                        <td width="30%">Country</td>
                                        <td width="70%"><b>{{ DB::table('countries')->where('id', $billing_address->country)->first()->name }}</b></td>
                                    </tr>
                                    <tr>
                                        <td width="30%">State</td>
                                        <td width="70%"><b>{{ DB::table('states')->where('id', $billing_address->state)->first()->name }}</b></td>
                                    </tr>
                                </tbody>
                            </table>
                            
                        </td>
                        <td class="text-left">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                    <tr>
                                        <td width="30%">Name</td>
                                        <td width="70%"><b>{{ $shipping_address->name }}</b></td>
                                    </tr>
                                    <tr>
                                        <td width="30%">Email</td>
                                        <td width="70%"><b>{{ $shipping_address->email }}</b></td>
                                    </tr>
                                    <tr>
                                        <td width="30%">Phone</td>
                                        <td width="70%"><b>{{ $shipping_address->phone }}</b></td>
                                    </tr>
                                    <tr>
                                        <td width="30%">Street Address</td>
                                        <td width="70%"><b>{{ $shipping_address->street_address }}</b></td>
                                    </tr>
                                    <tr>
                                        <td width="30%">Apartment #/ Suite / Building</td>
                                        <td width="70%"><b>{{ $shipping_address->apt_ste_bldg }}</b></td>
                                    </tr>
                                    <tr>
                                        <td width="30%">City</td>
                                        <td width="70%"><b>{{ $shipping_address->city }}</b></td>
                                    </tr>
                                    <tr>
                                        <td width="30%">Zip Code</td>
                                        <td width="70%"><b>{{ $shipping_address->zip_code }}</b></td>
                                    </tr>
                                    <tr>
                                        <td width="30%">Country</td>
                                        <td width="70%"><b>{{ DB::table('countries')->where('id', $shipping_address->country)->first()->name }}</b></td>
                                    </tr>
                                    <tr>
                                        <td width="30%">State</td>
                                        <td width="70%"><b>{{ DB::table('states')->where('id', $shipping_address->state)->first()->name }}</b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    @else
                    <tr>
                        <td colspan="2" class="text-center">
                            <p>You Haven't Updated your Address</p>
                            <a href="{{ route('customer.account-settings') }}" class="btn btn-md btn-primary">Go to Account Settings</a>
                        </td> 
                    </tr>
                    @endif
                </tbody>
            </table>
            <br>
            <h5>If you want to edit or update these infomation, you can head over to account settings page or <a href="{{ route('customer.account-settings') }}"><i>click here</i></a> to go there directly</h5>
            <br>
            <h2 class="title">Order History</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <td class="text-center">Order ID</td>
                            <td class="text-center">Items Count</td>
                            <td class="text-center">Date Added</td>
                            <td class="text-right">Total</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $key => $order)
                        <tr>
                            <td class="text-center">#{{ $order->order_no }}</td>
                            <td class="text-center">
                                {{ $order->vendor_ordered_products()->count() }}
                            </td>
                            <td class="text-center">{{ date('jS F, Y',strtotime($order->created_at)) }}</td>
                            <td class="text-right">${{ $order->total_price }}</td>
                            <td class="text-center">
                                <a class="btn btn-info" title="View Order Details" data-toggle="tooltip" href="{{ route('customer.view-order', ['order_no' => base64_encode($order->order_no)]) }}" data-original-title="View Order Details"><i class="fa fa-eye"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pull-left">
                <a href="{{ route('customer.orders') }}" class="btn btn-md btn-primary">View Full History</a>
            </div>
            <div class="clearfix"></div>
            <br>
            <hr>
            <br>
            <h2 class="title">My Wish List</h2>
            <div class="digital">
                <div class="row">
                    <div class=" col-sm-12">
                        <div class="product-category">
                            <div class="products-list grid">
                                @foreach($wishlists as $wishlist)
                                @php
                                    $productImage = asset('storage/products/'.$wishlist->product->slug.'/thumbs/small_'.$wishlist->product->image);
                                @endphp
                                <div class="product-layout">
                                    <div class="product-item-container">
                                        <div class="left-block">
                                            <div class="product-image-container  second_img ">
                                                <a href="{{ url('product/'.$wishlist->product->slug) }}" class="product-img">
                                                    <img src="{{ asset('storage/products/'.$wishlist->product->slug.'/thumbs/small_'.$wishlist->product->image) }}" alt="{{ $wishlist->product->slug }}">
                                                </a>
                                                <!--Sale Label-->
                                                <!-- <span class="new">New</span> -->
                                                @if(Auth::check())
                                                <div class="hover">
                                                    <ul>
                                                        <li class="icon-heart">
                                                            <a class="wishlist btn-add-to-wishlist" data-toggle="tooltip"  data-product-id="{{ $wishlist->product->id }}" data-product-image="{{ $productImage }}" data-product-name="{{ $wishlist->product->product_name }}" data-product-slug="{{ $wishlist->product->slug }}" data-original-title="Add to Wish List"><i class="fa fa-heart"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="right-block">
                                            <div class="caption">
                                                <h4><a href="{{ url('product/'.$wishlist->product->slug) }}">{{ $wishlist->product->product_name }}</a></h4>

                                                @php
                                                    $invProd = $wishlist->product->inventory_products->sortBy('retail_price')->first();

                                                @endphp

                                                <div class="price">
                                                    <span class="price-new">
                                                        ${{ $invProd->retail_price }}

                                                        <?php 
                                                            if ($invProd->product_variation->pack != 1) {
                                                                $variationName = $invProd->product_variation->pack.'x - '.$invProd->product_variation->size.' '.$invProd->product_variation->container.'s';
                                                            }else{
                                                                $variationName = $invProd->product_variation->size.' '.$invProd->product_variation->container;
                                                            }
                                                        ?>
                                                        <small>{{ $variationName }}</small>
                                                    </span>
                                                </div>
                                                
                                                <div class="description item-desc hidden">
                                                    {!! $wishlist->product->summary !!}
                                                </div>
                                            </div>

                                        </div><!-- right block -->
                                    </div>
                                </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pull-left">
                <a href="{{ route('customer.wishlist') }}" class="btn btn-md btn-primary">View All</a>
            </div>
        </div>
        <!--Middle Part End-->
        <!--Right Part Start -->
        <aside class="col-sm-3 hidden-xs" id="column-right">
            <h2 class="subtitle">Account</h2>
            <div class="list-group">
                <ul class="list-item">
                    <li><b>My Account</b></li>
                    <li><a href="{{ route('customer.account-settings') }}">Account Settings</a></li>
                    <li><a href="{{ route('customer.wishlist') }}">Wish List</a>
                    <li><a href="{{ route('customer.orders') }}">Order History</a></li>
                </ul>
            </div>
        </aside>
        <!--Right Part End -->
    </div>
</div>

@endsection

@push('post-scripts')


@endpush