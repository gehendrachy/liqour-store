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

                        <li class="breadcrumb-item active">
                            <i class="icon-basket-loaded"></i> Vendor Orders
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
                    
                    <a href="{{ route('vendor.vendor-orders.list',['username' => $username]) }}" class="btn btn-block btn-round text-left btn-primary disabled">
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
                            <h5>Vendor Orders</h5>
                            <small>View Your Store Orders</small>
                        </div>                                
                    </div>
                </div>
                <div class="card p-3">
                    <div class="cart-body bg-transparent">
                        <div class="table-responsive">
                            <table class="table table-hover js-basic-example table-custom spacing5" id="order-table">
                                <thead>
                                    <tr>
                                        <th><strong>Order #</strong></th>
                                        <th><strong>Date / Time</strong></th>
                                        <th><strong>Customer Name</strong></th>
                                        <th><strong>Items Count</strong></th>
                                        <th><strong>Status</strong></th>
                                        <th><strong>Total Price</strong></th>
                                        
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date / Time</th>
                                        <th><strong>Customer Name</strong></th>
                                        <th>Items Count</th>
                                        <th><strong>Total</strong></th>
                                        <th>Total Price</th>
                                        
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @php
                                        $orderStatus = array('0' => ['Pending', 'warning'],
                                                             '1' => ['On Process', 'primary'],
                                                             '2' => ['Delivering', 'info'],
                                                             '3' => ['Delivered', 'success'],
                                                             '4' => ['Cancelled', 'danger'],
                                                             '5' => ['Return Requested','danger'],
                                                             '6' => ['Returned','danger']
                                                            );
                                    @endphp
                                    @foreach($vendor_orders as $key => $vendor_order)
                                    <tr>
                                        
                                        <td>
                                            <strong><a href="{{ url('vendor/'.$username.'/vendor-orders/view/'.$vendor_order->id) }}" title="View Order Details"> #{{ $vendor_order->order->order_no }}</a></strong>
                                        </td>

                                        <td><small>{{ date('jS M, Y H:i:s', strtotime($vendor_order->created_at)) }}</small></td>
                                        <td>{{ $vendor_order->order->customer_name }}</td>
                                        <td class="text-center">{{ $vendor_order->ordered_products->count() }}</td>

                                        <td>
                                            <small class="badge badge-{{ $orderStatus[$vendor_order->status][1] }}" >
                                                {{ $orderStatus[$vendor_order->status][0] }}
                                            </small>
                                        </td>
                                        
                                        
                                        <?php 
                                            $calculated_subtotal = $vendor_order->ordered_products()
                                                                    ->select(
                                                                        DB::raw('sum(
                                                                                    replace(
                                                                                        format(
                                                                                            (sub_total/quantity * (1+tax_rate/100) + pack * bottle_deposit_rate), 2
                                                                                        ), ",", ""
                                                                                    ) * quantity 
                                                                                ) as total'
                                                                            )
                                                                    )->first()->total;

                                            $calculated_subtotal = number_format($calculated_subtotal + $vendor_order->delivery_fee, 2);
                                        ?>
                                        
                                        
                                        <td><strong>${{ $calculated_subtotal }}</strong></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
        
        $('#order-table').DataTable({

            "order": [[ 0, "desc" ]],
            "lengthMenu": [[ 25, 50,  100, -1], [ 25, 50,  100, "All"]]

        });

    </script>
@endsection
