@extends('admin/layouts.header-sidebar')
@section('title',  isset($vendor->vendor_details) ? $vendor->vendor_details->store_name : $vendor->name. ' : Dashboard')
@section('content')
    <div class="container-fluid">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-md-6 col-sm-12">
                    <h2>{{ isset($vendor->vendor_details) ? $vendor->vendor_details->store_name : $vendor->name }}</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 col-sm-12 text-right hidden-xs">
                </div>
            </div>
        </div>


    </div>

    <div class="col-lg-12">
        <div class="row clearfix">

            <div class="col-6 col-md-6 col-xl-4 {{ !isset($vendor_details) ? 'ribbon' : '' }}">
                <a href="{{ !isset($vendor_details) ? url('vendor/'.$vendor->username.'/vendor-settings') : url('vendor/'.$vendor->username.'/inventory-products') }}">
                    <div class="card mb-3">
                        <div class="body top_counter">
                            <div class="icon text-white" style="background-color: #0099ae;">
                                <i class="fa fa-shopping-cart"></i>
                            </div>
                            <div class="content">
                                <h5 class="number mb-0">Inventory Products</h5>
                                <small class="subtitle">Add | Update | Delete Inventory Products</small>
                            </div>
                            @if(!isset($vendor_details))
                            <div class="ribbon-box orange" style="z-index: 9999; font-size:8px;top:4px; color: black;">Please Update Store Details First</div>
                            @endif
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-6 col-xl-4 ribbon">

                <a href="{{ url('vendor/'.$vendor->username.'/vendor-settings') }}">
                    <div class="card mb-3">
                        <div class="body top_counter">
                            <div class="icon text-white" style="background-color: #0099ae;">
                                <i class="fa fa-info-circle"></i>
                            </div>
                            <div class="content">
                                <h5 class="number mb-0">Vendor Settings </h5>
                                <small class="subtitle">Update Vendor Settings</small>
                            </div>
                            @if(!isset($vendor_details))
                            <div class="ribbon-box orange" style="z-index: 9999; font-size:8px;top:4px; color: black;">To Be Updated</div>
                            @endif
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-6 col-xl-4 ribbon">

                <a href="{{ url('vendor/'.$vendor->username.'/vendor-orders') }}">
                    <div class="card mb-3">
                        <div class="body top_counter">
                            <div class="icon text-white" style="background-color: #0099ae;">
                                <i class="icon-basket-loaded"></i>
                            </div>
                            <div class="content">
                                <h5 class="number mb-0">Vendor Orders </h5>
                                <small class="subtitle">View Orders</small>
                            </div>
                            @if(!isset($vendor_details))
                            <div class="ribbon-box orange" style="z-index: 9999; font-size:8px;top:4px; color: black;">To Be Updated</div>
                            @endif
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>
@endsection

