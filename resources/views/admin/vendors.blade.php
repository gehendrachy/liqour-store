@extends('admin/layouts.header-sidebar')
@section('title','Vendors')
@section('content')
<div class="container-fluid">
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-md-6 col-sm-12">
                <h2>Vendors</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item" aria-current="page">
                            <a href="{{ route('admin.dashboard') }}"><i class="icon-speedometer"></i> Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page"><i class="fa fa-th"></i> Vendors</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-6 col-sm-12 text-right hidden-xs">
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card border-secondary p-3">
                <div class="cart-body bg-transparent">
                    <div class="table-responsive">
                        <table class="table table-hover js-basic-example dataTable table-custom spacing5">
                            <thead>
                                <tr>
                                    <th><strong>SN.</strong></th>
                                    <th><strong>Vendor Name</strong></th>
                                    <th><strong>No. of Products</strong></th>
                                    <th><strong>Address</strong></th>
                                    <th><strong>Joined Date</strong></th>
                                    <th><strong>Action</strong></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>SN.</th>
                                    <th>Vendor Name</th>
                                    <th>No. of Products</th>
                                    <th>Address</th>
                                    <th>Joined Date</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach($vendors as $key => $vendor)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ isset($vendor->vendor_details) ? $vendor->vendor_details->store_name : $vendor->name }}</td>
                                    <td>{{ $vendor->inventory_products()->count() }}</td>
                                    <td>{{ isset($vendor->vendor_details) ? $vendor->vendor_details->address_1 : '' }}</td>
                                    <td>{{ date('F jS,Y',strtotime($vendor->created_at)) }}</td>
                                    <td>
                                        <a href="{{ url('vendor/'.$vendor->username.'/dashboard/') }}" class="btn btn-sm btn-info" title="Go to Vendor Dashboard">
                                            <i class="fa fa-eye"></i> Goto Vendor Dashboard
                                        </a>
                                    </td>
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

<!-- <div class="col-lg-12">
    <div class="row clearfix">
        @foreach($vendors as $key => $vendor)
        <div class="col-lg-3 col-md-6">
            <a class="card border-secondary" href="{{ url('vendor/'.$vendor->username.'/dashboard/') }}">
                <div class="body text-center">
                    <img class="img-thumbnail rounded-circle" src="{{ asset('images/admin.jpg') }}" width="50%" alt="">
                    <h6 class="mt-3">{{ $vendor->name }}</h6>
                    <div class="text-center text-muted">Intranet Developer</div>
                </div>
                <div class="card-footer text-center">
                    <div class="row clearfix">
                        <div class="col-6">
                            <i class="fa fa-shopping-cart font-22"></i>
                            <div><span class="text-muted">{{ $key+1 }} Products</span></div>
                        </div>
                        <div class="col-6">
                            <i class="fa fa-dollar font-22"></i>
                            <div><span class="text-muted">$ 3.100</span></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div> -->
@endsection

