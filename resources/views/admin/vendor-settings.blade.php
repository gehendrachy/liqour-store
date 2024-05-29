@extends('admin/layouts.header-sidebar')
@section('title',  isset($vendor->vendor_details) ? $vendor->vendor_details->store_name : $vendor->name. ' - Settings')
@section('content')
<div class="container-fluid">
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-md-12 col-sm-12">
                <h2>{{ isset($vendor->vendor_details) ? $vendor->vendor_details->store_name : $vendor->name }}'s Profile</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('vendor.dashboard',['username' => $username])  }}"><i class="icon-speedometer"></i> Dashboard</a>
                        </li>

                        <li class="breadcrumb-item active">
                            <i class="fa fa-info-circle"></i> Vendor Settings
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
                            <h5 class="mb-0">{{ isset($vendor->vendor_details) ? $vendor->vendor_details->store_name : $vendor->name }}</h5>
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

                    <a href="{{ route('vendor.vendor-settings.list',['username' => $username]) }}" class="btn btn-block btn-round text-left btn-primary disabled ">
                        <i class="fa fa-info-circle"></i>
                        Vendor Settings
                    </a>
                    @if(isset($vendor_details))
                    <a href="{{ route('vendor.inventory-products.list',['username' => $username]) }}" class="btn btn-block btn-round text-left btn-outline-info">
                        <i class="fa fa-database"></i>
                        Inventory Products
                    </a>

                    <a href="{{ route('vendor.vendor-orders.list',['username' => $username]) }}" class="btn btn-block btn-round text-left btn-outline-info">
                        <i class="icon-basket-loaded"></i>
                        Vendor Orders
                    </a>
                    @endif
                    
                </div>
            </div>                    
        </div>
        <div class="col-md-9">
            <div class="card border-secondary">
                <div class="card-header">
                    <div class="d-flex">
                        <div class="details">
                            <h5>Vendor Settings</h5>
                            <small>Edit Your Store Details</small>
                        </div>                                
                    </div>
                </div>
                <form id="parsley-form" method="post" action="{{ route('vendor.vendor-settings.update',['username' => $username]) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body mb-0">    
                        <div class="card-body pb-3 mb-0">
                        
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted"><i class="fa fa-building"></i> Vendor Name*: </small>
                                    <input type="text" name="store_name" class="form-control" required value="{{ old('store_name') ? old('store_name') : (isset($vendor_details->store_name) ? $vendor_details->store_name : $vendor->name ) }}" placeholder="eg: The Liquor House">
                                    <hr>    
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted"><i class="fa fa-envelope"></i> Email: </small>

                                    <input type="email" name="email" class="form-control" required value="{{ old('email') ? old('email') : (isset($vendor_details->email) ? $vendor_details->email : $vendor->email ) }}" disabled readonly placeholder="eg: hello@example.com">
                                    <hr>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted"><i class="fa fa-map-marker"></i> Address 1(Primary)*: </small>
                                    <input type="text" name="address_1" class="form-control" required value="{{ old('address_1') ? old('address_1') : (isset($vendor_details->address_1) ? $vendor_details->address_1 : $vendor->address ) }}" placeholder="eg: Kathmandu, Nepal">
                                    <hr>    
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted"><i class="fa fa-map-marker"></i> Address 2(Secondary): </small>
                                    <input type="text" name="address_2" class="form-control" value="{{ old('address_2') ? old('address_2') : (isset($vendor_details->address_2) ? $vendor_details->address_2 : $vendor->address ) }}" placeholder="eg: Kathmandu, Nepal">
                                    <hr>    
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted"><i class="fa fa-phone"></i> Contact Number<span style="font-size: 10px;">(Phone)</span>*: </small>
                                    <input type="text" name="phone" class="form-control" required value="{{ old('phone') ? old('phone') : (isset($vendor_details->phone) ? $vendor_details->phone : $vendor->phone ) }}" placeholder="eg: +1 (351) 527-6003">
                                    <hr>    
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="fa fa-building"></i> &nbsp; Contact Number<span style="font-size: 10px;">(Mobile)</span>
                                    </small>
                                    <input type="text" name="mobile" class="form-control" value="{{ old('mobile') ? old('mobile') : @$vendor_details->mobile }}" placeholder="eg: +1 (351) 527-6003">
                                    <hr>    
                                </div>
                                
                                <div class="col-md-4">
                                    <small class="text-muted">
                                        <i class="fa fa-building"></i> &nbsp; City*
                                    </small>
                                    <input type="text" name="city" class="form-control" required value="{{ old('city') ? old('city') : @$vendor_details->city }}" placeholder="eg: Los Angeles">
                                    <hr>    
                                </div>

                                <div class="col-md-4">
                                    <small class="text-muted">
                                        <i class="fa fa-building"></i> &nbsp; State*
                                    </small>
                                    <input type="text" name="state" class="form-control" required value="{{ old('state') ? old('state') : @$vendor_details->state }}" placeholder="eg: California">
                                    <hr>    
                                </div>

                                <div class="col-md-4">
                                    <small class="text-muted">
                                        <i class="fa fa-building"></i> &nbsp; Zip Code
                                    </small>
                                    <input type="text" name="zip_code" class="form-control" value="{{ old('zip_code') ? old('zip_code') : @$vendor_details->zip_code }}" placeholder="eg: 50238">
                                    <hr>    
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="fa fa-clock-o"></i> &nbsp; Opening Time
                                    </small>
                                    <input type="time" name="opening_time" class="form-control" value="{{ old('opening_time') ? old('opening_time') : @$vendor_details->opening_time }}" placeholder="eg: 09:20">
                                    <hr>    
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="fa fa-clock-o"></i> &nbsp; Closing Time
                                    </small>
                                    <input type="time" name="closing_time" class="form-control" value="{{ old('closing_time') ? old('closing_time') : @$vendor_details->closing_time }}" placeholder="eg: 22:00">
                                    <hr>    
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="fa fa-dollar"></i> &nbsp; Delivery Fee(In USD)
                                    </small>
                                    <input type="text" name="delivery_fee" class="form-control decimal-input" value="{{ old('delivery_fee') ? old('delivery_fee') : @$vendor_details->delivery_fee }}" placeholder="eg: 20">
                                    <hr>    
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="fa fa-shopping-cart"></i> &nbsp; Minimum Order (In USD)
                                    </small>
                                    <input type="text" name="minimum_order" class="form-control decimal-input" value="{{ old('minimum_order') ? old('minimum_order') : @$vendor_details->minimum_order }}" placeholder="eg: 20">
                                    <hr>    
                                </div>

                                <div class="col-md-4">
                                    
                                    <small class="text-muted">
                                        <i class="fa fa-building"></i> &nbsp; Sales Tax Rate 1(%)
                                    </small>

                                    <input type="text" name="tax_rate_1" class="form-control tax_rate decimal-input" value="{{ old('tax_rate_1') ? old('tax_rate_1') : @$vendor_details->tax_rate_1 }}" placeholder="eg: 10">
                                    <hr>    
                                </div>

                                <div class="col-md-4">
                                    <small class="text-muted">
                                        <i class="fa fa-building"></i> &nbsp; Sales Tax Rate 2(%)
                                    </small>
                                    <input type="text" name="tax_rate_2" class="form-control tax_rate decimal-input" value="{{ old('tax_rate_2') ? old('tax_rate_2') : @$vendor_details->tax_rate_2 }}" placeholder="eg: 10">
                                    <hr>    
                                </div>

                                <div class="col-md-4">
                                    <small class="text-muted">
                                        <i class="fa fa-building"></i> &nbsp; Sales Tax Rate 3(%)
                                    </small>
                                    <input type="text" name="tax_rate_3" class="form-control tax_rate decimal-input" value="{{ old('tax_rate_3') ? old('tax_rate_3') : @$vendor_details->tax_rate_3 }}" placeholder="eg: 10">
                                    <hr>
                                </div>

                                <div class="col-md-4">
                                    <small class="text-muted">
                                        <i class="fa fa-money"></i> &nbsp; Bottle Deposit 1 Rate(In USD)
                                    </small>
                                    <input type="text" name="bottle_deposit_1_rate" class="form-control bottle_deposit_rate decimal-input" value="{{ old('bottle_deposit_1_rate') ? old('bottle_deposit_1_rate') : @$vendor_details->bottle_deposit_1_rate }}" placeholder="eg: 14">
                                    <hr>    
                                </div>

                                <div class="col-md-4">
                                    <small class="text-muted">
                                        <i class="fa fa-money"></i> &nbsp; Bottle Deposit 2 Rate(In USD)
                                    </small>
                                    <input type="text" name="bottle_deposit_2_rate" class="form-control bottle_deposit_rate decimal-input" value="{{ old('bottle_deposit_2_rate') ? old('bottle_deposit_2_rate') : @$vendor_details->bottle_deposit_2_rate }}" placeholder="eg: 10">
                                    <hr>    
                                </div>
                                @role('Super Admin')
                                <div class="col-md-4">
                                    <small class="text-muted">
                                        <i class="fa fa-money"></i> &nbsp; Commission(%)
                                    </small>
                                    <input type="text" name="commission_percentage" class="form-control decimal-input" value="{{ old('commission_percentage') ? old('commission_percentage') : @$vendor_details->commission_percentage }}" placeholder="eg: 14">
                                    <hr>    
                                </div>
                                @endrole

                                <div class="col-md-6">  
                                    <small class="text-muted">
                                        <i class="fa fa-building"></i> &nbsp; Profile Image*
                                    </small>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-image"></i> &nbsp;Profile Image</span>
                                        </div>
                                        <input type="file" name="image" class="bg-primary text-white form-control" {{ (@$vendor_details->image == '' ? 'required' : '' ) }}>
                                    </div>
                                    <hr>
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="fa fa-building"></i> &nbsp; Current Image*
                                    </small>
                                    <div class="input-group mb-3">
                                        @if(@$vendor_details->image != '')
                                        <img width="100" src="{{ asset('storage/vendors/thumbs/small_'.$vendor_details->image) }}" class="img-thumbnail" alt="no-image">
                                        @else
                                            <img class="img-thumbnail" src="https://via.placeholder.com/200X50/?text=Image+Not+Updated">
                                        @endif
                                    </div>
                                    <hr>
                                </div>

                                <div class="col-md-12">
                                    <small class="text-muted">
                                        <i class="fa fa-building"></i> &nbsp; Description*
                                    </small>
                                    <textarea name="description" id="editor1" class="form-control ckeditor" required>{{ old('description') ? old('description') : @$vendor_details->description }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="route('vendor.vendor-settings.list',['username' => $username])"
                                class="btn btn-outline-danger">CANCEL</a>

                                <button type="submit" style="float: right;" class="btn btn-outline-success"> UPDATE</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
    <script>



        $(".decimal-input").keypress(function(event){
            return isDecimalNumber(event, this);
        });

        $(".number-input").keypress(function(event){
            return isNumberKey(event, this);
        });

        function isNumberKey(evt, element) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
        }

        function isDecimalNumber(evt, element){ 

            var charCode = (evt.which) ? evt.which : event.keyCode 

            if  ((charCode != 46 || ($(element).val().match(/\./g) || []).length > 0) && (charCode < 48 || charCode > 57))
                return false; 
            return true; 
        }
    </script>
@endsection