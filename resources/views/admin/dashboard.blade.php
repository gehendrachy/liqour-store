@extends('admin/layouts.header-sidebar')
@section('title','Dashboard')
@section('content')
    <div class="container-fluid">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-md-6 col-sm-12">
                    <h2>Dashboard</h2>
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

            <div class="col-6 col-md-6 col-xl-4">
                <a href="{{ url('admin/users') }}">
                    <div class="card mb-3">
                        <div class="body top_counter">
                            <div class="icon text-white" style="background-color: #0099ae;">
                                <i class="icon-users"></i>
                            </div>
                            <div class="content">
                                <h5 class="number mb-0">Users</h5>
                                <small class="subtitle">Add | Update | Delete Users</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-6 col-xl-4">
                <a href="{{ url('admin/setting') }}">
                    <div class="card mb-3">
                        <div class="body top_counter">
                            <div class="icon text-white" style="background-color: #0099ae;">
                                <i class="icon-settings"></i>
                            </div>
                            <div class="content">
                                <h5 class="number mb-0">Site Setting</h5>
                                <small class="subtitle">Update Site Details</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-6 col-xl-4">
                <a href="{{ url('admin/categories') }}">
                    <div class="card mb-3">
                        <div class="body top_counter">
                            <div class="icon text-white" style="background-color: #0099ae;">
                                <i class="fa fa-anchor"></i>
                            </div>
                            <div class="content">
                                <h5 class="number mb-0">Categories</h5>
                                <small class="subtitle">Add | Update | Delete Product Categories</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- <div class="col-6 col-md-6 col-xl-4">
                <a href="{{ url('admin/brands') }}">
                    <div class="card mb-3">
                        <div class="body top_counter">
                            <div class="icon text-white" style="background-color: #0099ae;">
                                <i class="fa fa-spoon"></i>
                            </div>
                            <div class="content">
                                <h5 class="number mb-0">Brands</h5>
                                <small class="subtitle">Add | Update | Delete Product Brands</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div> -->

            <!-- <div class="col-6 col-md-6 col-xl-4">
                <a href="{{ url('admin/variations') }}">
                    <div class="card mb-3">
                        <div class="body top_counter">
                            <div class="icon text-white" style="background-color: #0099ae;">
                                <i class="icon-size-fullscreen"></i>
                            </div>
                            <div class="content">
                                <h5 class="number mb-0">Product Variations <i class="fa fa-times" style="color: red;"></i></h5>
                                <small class="subtitle">Add | Update | Delete Product Variations</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div> -->

            <div class="col-6 col-md-6 col-xl-4">
                <a href="{{ url('admin/products') }}">
                    <div class="card mb-3">
                        <div class="body top_counter">
                            <div class="icon text-white" style="background-color: #0099ae;">
                                <i class="fa fa-database"></i>
                            </div>
                            <div class="content">
                                <h5 class="number mb-0">Products</h5>
                                <small class="subtitle">Add | Update | Delete Products</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-6 col-xl-4">
                <a href="{{ url('admin/vendors') }}">
                    <div class="card mb-3">
                        <div class="body top_counter">
                            <div class="icon text-white" style="background-color: #0099ae;">
                                <i class="fa fa-th"></i>
                            </div>
                            <div class="content">
                                <h5 class="number mb-0">Vendors</h5>
                                <small class="subtitle">View All Vendors</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-6 col-xl-4">
                <a href="{{ url('admin/pages') }}">
                    <div class="card mb-3">
                        <div class="body top_counter">
                            <div class="icon text-white" style="background-color: #0099ae;">
                                <i class="fa fa-file-text-o"></i>
                            </div>
                            <div class="content">
                                <h5 class="number mb-0">Pages</h5>
                                <small class="subtitle">Add | Update | Delete Pages</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-6 col-xl-4">
                <a href="{{ url('admin/sliders') }}">
                    <div class="card mb-3">
                        <div class="body top_counter">
                            <div class="icon text-white" style="background-color: #0099ae;">
                                <i class="icon-layers"></i>
                            </div>
                            <div class="content">
                                <h5 class="number mb-0">Sliders</h5>
                                <small class="subtitle">Add | Update | Delete Sliders</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-6 col-xl-4">
                <a href="{{ url('admin/sales-reports') }}">
                    <div class="card mb-3">
                        <div class="body top_counter">
                            <div class="icon text-white" style="background-color: #0099ae;">
                                <i class="fa fa-calculator"></i>
                            </div>
                            <div class="content">
                                <h5 class="number mb-0">Sales Report</h5>
                                <small class="subtitle">Add | Update | Delete Sales Report</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-6 col-xl-4">
                <a href="{{ url('admin/payment-reports') }}">
                    <div class="card mb-3">
                        <div class="body top_counter">
                            <div class="icon text-white" style="background-color: #0099ae;">
                                <i class="icon-calculator"></i>
                            </div>
                            <div class="content">
                                <h5 class="number mb-0">Payment Reports</h5>
                                <small class="subtitle">Add | Update | Delete Payment Reports</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>
@endsection

