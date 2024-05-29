@extends('layouts.app')
@section('title','Order History')
@section('content')
<div class="main-container container">
    <ul class="header-main ">
        <li class="home"><a href="{{ route('home') }}">Home </a><i class="fa fa-angle-right" aria-hidden="true"></i></li>
        <li class="home"><a href="{{ route('customer.my-account') }}">Account </a><i class="fa fa-angle-right" aria-hidden="true"></i></li>
        <li>  Order History</li>
    </ul>

    <div class="row">
        <!--Middle Part Start-->
        <div class="col-sm-9" id="content">
            <h2 class="title">Order History</h2>
            <hr>
            <br>
            <p class="lead">Hello, <strong>{{ $customer->name }}!</strong> - <small>This page contains your Order History.</small></p>
            
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <td class="text-center">Order No</td>
                            <td class="text-center">Status</td>
                            <td class="text-center">Items Count</td>
                            <td class="text-right">Total</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $key => $order)
                        <tr>
                            <td class="text-center">#{{ $order->order_no }}</td>
                            <td class="text-center">
                                {{-- @php 
                                    $orderStatus = array('0' => 'Pending',
                                                         '1' => 'On Process',
                                                         '2' => 'Delivering',
                                                         '3' => 'Delivered',
                                                         '4' => 'Cancelled'
                                                        );
                                @endphp
                                {{ $orderStatus[$order->status] }} --}}
                                {{ $order->vendor_ordered_products()->count() }}
                            </td>
                            <td class="text-center">{{ date('jS F, Y',strtotime($order->created_at)) }}</td>
                            <td class="text-right">
                                @php
                                // $calculated_subtotal =  collect($order->vendor_ordered_products()->get())
                                //                 ->sum(function($ordered_product){
                                //                     return number_format(($ordered_product['sub_total']/$ordered_product['quantity'])*(1+$ordered_product['tax_rate']/100),2)*$ordered_product['quantity'];
                                //                 });

                                $calculated_subtotal = $order->vendor_orders()->sum('grand_total');

                                @endphp
                                ${{ $calculated_subtotal }}
                            </td>
                            <td class="text-center">
                                <a class="btn btn-info" title="View Order Details" data-toggle="tooltip" href="{{ route('customer.view-order', ['order_no' => base64_encode($order->order_no)]) }}" data-original-title="View Order Details"><i class="fa fa-eye"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $orders->links() }}
        </div>
        <!--Middle Part End-->
        <!--Right Part Start -->
        <aside class="col-sm-3 hidden-xs" id="column-right">
            <h2 class="subtitle">Account</h2>
            <div class="list-group">
                <ul class="list-item">
                    <li><a href="{{ route('customer.my-account') }}">My Account</a></li>
                    <li><a href="{{ route('customer.account-settings') }}">Account Settings</a></li>
                    <li><a href="{{ route('customer.wishlist') }}">Wish List</a>
                    <li><b>Order History</b></li>
                </ul>
            </div>
        </aside>
        <!--Right Part End -->
    </div>
</div>

@endsection

@push('post-scripts')


@endpush