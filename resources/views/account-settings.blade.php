@extends('layouts.app')
@section('title','Account Settings')
@section('content')
<div class="main-container container">
    <ul class="header-main ">
        <li class="home"><a href="{{ route('home') }}">Home </a><i class="fa fa-angle-right" aria-hidden="true"></i></li>

        <li class="home"><a href="{{ route('customer.my-account') }}">Account </a><i class="fa fa-angle-right" aria-hidden="true"></i></li>

        <li>  Account Settings</li>
    </ul>

    <div class="row">
        <!--Middle Part Start-->
        <div class="col-sm-9" id="content">
            <h2 class="title">My Account</h2>
            <p class="lead">Hello, <strong>{{ $customer->name }}!</strong> - To update your account information.</p>
            
            <form method="POST" action="{{ route('customer.create-update-information') }}">
                @csrf
                <div class="row">
                    <div class="col-sm-12">
                        <fieldset id="personal-details">
                            <legend>Personal Details</legend>
                            <div class="row">
                                <div class="col-sm-4 form-group required">
                                    <label for="input-name" class="control-label">Full Name</label>
                                    <input type="text" class="form-control" id="input-name" placeholder="eg: John Doe" value="{{ old('name') ? old('name') : $customer->name }}" name="name" required>
                                </div>
                                <!-- <div class="col-sm-4 form-group required">
                                    <label for="input-lastname" class="control-label">Last Name</label>
                                    <input type="text" class="form-control" id="input-lastname" placeholder="Last Name" value="" name="lastname">
                                </div> -->
                                <div class="col-sm-4 form-group required">
                                    <label for="input-email" class="control-label">E-Mail</label>
                                    <input type="email" class="form-control" id="input-email" placeholder="E-Mail" value="{{ $customer->email }}" readonly>
                                </div>
                                <div class="col-sm-4 form-group required">
                                    <label for="input-phone" class="control-label">Contact Number</label>
                                    <input type="tel" class="form-control" id="input-phone" placeholder="Contact Number" value="{{ old('phone') ? old('phone') : $customer->phone }}" name="phone" required>
                                </div>
                            </div>
                        </fieldset>
                        <br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <fieldset id="address">
                            <legend>Billing Details</legend>
                            <div class="form-group required">
                                <label for="input-billing-name" class="control-label">Name</label>
                                <input type="text" class="form-control" id="input-billing-name" placeholder="Billing Name" value="{{ old('billing_name') ? old('billing_name') : (isset($billing_address->name) ? $billing_address->name : '') }}" name="billing_name" required>

                            </div>

                            <div class="form-group required">
                                <label for="input-billing-email" class="control-label">Email</label>
                                <input type="email" class="form-control" id="input-billing-email" placeholder="Billing Email" value="{{ old('billing_email') ? old('billing_email') : (isset($billing_address->email) ? $billing_address->email : '') }}" name="billing_email" required>

                            </div>

                            <div class="form-group required">
                                <label for="input-billing-phone" class="control-label">Phone</label>
                                <input type="text" class="form-control" id="input-billing-phone" placeholder="Billing Phone" value="{{ old('billing_phone') ? old('billing_phone') : (isset($billing_address->phone) ? $billing_address->phone : '') }}" name="billing_phone" required>
                            </div>

                            <div class="form-group required">
                                <label for="input-billing-street-address" class="control-label">Street Address</label>
                                <input type="text" class="form-control" id="input-billing-street-address" placeholder="Street Address" value="{{ old('billing_street_address') ? old('billing_street_address') : (isset($billing_address->street_address) ? $billing_address->street_address : '') }}" name="billing_street_address" required>
                            </div>

                            <div class="form-group">
                                <label for="input-billing-apt-ste-bldg" class="control-label">Apartment #/ Suite / Building </label>
                                <input type="text" class="form-control" id="input-billing-apt-ste-bldg" placeholder="Apartment #/ Suite / Building" value="{{ old('billing_apt_ste_bldg') ? old('billing_apt_ste_bldg') : (isset($billing_address->apt_ste_bldg) ? $billing_address->apt_ste_bldg : '') }}" name="billing_apt_ste_bldg">
                            </div>

                            <div class="form-group required">
                                <label for="input-billing-city" class="control-label">City</label>
                                <input type="text" class="form-control" id="input-billing-city" placeholder="Billing City" value="{{ old('billing_city') ? old('billing_city') : (isset($billing_address->city) ? $billing_address->city : '') }}" name="billing_city" required>
                            </div>

                            <div class="form-group required">
                                <label for="input-billing-zip-code" class="control-label">Zip Code</label>
                                <input type="text" class="form-control" id="input-billing-zip-code" placeholder="Billing Zip Code" value="{{ old('billing_zip_code') ? old('billing_zip_code') : (isset($billing_address->zip_code) ? $billing_address->zip_code : '') }}" name="billing_zip_code" required>
                            </div>


                            <div class="form-group required">
                                <label for="input-billing-country" class="control-label">Country</label>
                                
                                <select class="form-control billing_shipping_country" data-state-input-id="input-billing-state" data-state-id="{{ old('billing_state') ? old('billing_state') : (isset($billing_address->state) ? $billing_address->state : 0) }}" id="input-billing-country" name="billing_country" required>

                                    <option value="" selected disabled> --- Please Select --- </option>
                                    @php
                                        $billingCountry = old('billing_country') ? old('billing_country') : (isset($billing_address->country) ? $billing_address->country : '');
                                    @endphp

                                    @foreach($db_countries as $country)
                                        <option <?=$billingCountry == $country->id ? 'selected' : '' ?> value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="form-group required">
                                <label for="input-billing-state" class="control-label">Region / State</label>
                                
                                <select class="form-control" id="input-billing-state" name="billing_state" required>
                                    <option value="" selected disabled> --- Please Select --- </option>
                                </select>

                            </div>

                        </fieldset>
                    </div>
                    <div class="col-sm-6">
                        <fieldset id="address">
                            <legend>Shipping Details</legend>
                            <div class="form-group required">
                                <label for="input-shipping-name" class="control-label">Name</label>
                                <input type="text" class="form-control" id="input-shipping-name" placeholder="Shipping Name" value="{{ old('shipping_name') ? old('shipping_name') : (isset($shipping_address->name) ? $shipping_address->name : '') }}" name="shipping_name" required>

                            </div>

                            <div class="form-group required">
                                <label for="input-shipping-email" class="control-label">Email</label>
                                <input type="email" class="form-control" id="input-shipping-email" placeholder="Shipping Email" value="{{ old('shipping_email') ? old('shipping_email') : (isset($shipping_address->email) ? $shipping_address->email : '') }}" name="shipping_email" required>

                            </div>

                            <div class="form-group required">
                                <label for="input-shipping-phone" class="control-label">Phone</label>
                                <input type="text" class="form-control" id="input-shipping-phone" placeholder="Shipping Phone" value="{{ old('shipping_phone') ? old('shipping_phone') : (isset($shipping_address->phone) ? $shipping_address->phone : '') }}" name="shipping_phone" required>
                            </div>

                            <div class="form-group required">
                                <label for="input-shipping-street-address" class="control-label">Street Address</label>
                                <input type="text" class="form-control" id="input-shipping-street-address" placeholder="Street Address" value="{{ old('shipping_street_address') ? old('shipping_street_address') : (isset($shipping_address->street_address) ? $shipping_address->street_address : '') }}" name="shipping_street_address" required>
                            </div>

                            <div class="form-group">
                                <label for="input-shipping-apt-ste-bldg" class="control-label">Apartment #/ Suite / Building </label>
                                <input type="text" class="form-control" id="input-shipping-apt-ste-bldg" placeholder="Apartment #/ Suite / Building" value="{{ old('shipping_apt_ste_bldg') ? old('shipping_apt_ste_bldg') : (isset($shipping_address->apt_ste_bldg) ? $shipping_address->apt_ste_bldg : '') }}" name="shipping_apt_ste_bldg">
                            </div>

                            <div class="form-group required">
                                <label for="input-shipping-city" class="control-label">City</label>
                                <input type="text" class="form-control" id="input-shipping-city" placeholder="Shipping City" value="{{ old('shipping_city') ? old('shipping_city') : (isset($shipping_address->city) ? $shipping_address->city : '') }}" name="shipping_city" required>
                            </div>

                            <div class="form-group required">
                                <label for="input-shipping-zip-code" class="control-label">Zip Code</label>
                                <input type="text" class="form-control" id="input-shipping-zip-code" placeholder="Shipping Zip Code" value="{{ old('shipping_zip_code') ? old('shipping_zip_code') : (isset($shipping_address->zip_code) ? $shipping_address->zip_code : '') }}" name="shipping_zip_code" required>
                            </div>


                            <div class="form-group required">
                                <label for="input-shipping-country" class="control-label">Country</label>
                                
                                <select class="form-control billing_shipping_country" data-state-input-id="input-shipping-state" data-state-id="{{ old('shipping_state') ? old('shipping_state') :  (isset($shipping_address->state) ? $shipping_address->state : 0) }}" id="input-shipping-country" name="shipping_country" required>

                                    <option value="" selected disabled> --- Please Select --- </option>
                                    @php
                                        $shippingCountry = old('shipping_country') ? old('shipping_country') : (isset($shipping_address->country) ? $shipping_address->country : '');
                                    @endphp

                                    @foreach($db_countries as $country)
                                        <option <?=$shippingCountry == $country->id ? 'selected' : '' ?> value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="form-group required">
                                <label for="input-shipping-state" class="control-label">Region / State</label>
                                
                                <select class="form-control" id="input-shipping-state" name="shipping_state" required>
                                    <option value="" selected disabled> --- Please Select --- </option>
                                </select>

                            </div>

                        </fieldset>
                    </div>
                </div>

                <div class="buttons clearfix">
                    <div class="pull-right">
                        <input type="submit" class="btn btn-md btn-primary" value="Save Changes">
                    </div>
                </div>
            </form>

            <form>
                <div class="row">
                    <div class="col-sm-12">
                        <fieldset>
                            <legend>Change Password</legend>
                            <div class="row">
                                <div class="col-sm-4 form-group required">
                                    <label for="input-password" class="control-label">Old Password</label>
                                    <input type="password" class="form-control" id="input-password" placeholder="Old Password" value="" name="old-password">
                                </div>
                                <div class="col-sm-4 form-group required">
                                    <label for="input-password" class="control-label">New Password</label>
                                    <input type="password" class="form-control" id="input-password" placeholder="New Password" value="" name="new-password">
                                </div>
                                <div class="col-sm-4 form-group required">
                                    <label for="input-confirm" class="control-label">New Password Confirm</label>
                                    <input type="password" class="form-control" id="input-confirm" placeholder="New Password Confirm" value="" name="new-confirm">
                                </div>
                            </div>
                        </fieldset>
                        <!-- <fieldset>
                            <legend>Newsletter</legend>
                            <div class="form-group">
                                <label class="col-md-4 col-sm-4 col-xs-3 control-label">Subscribe</label>
                                <div class="col-md-8 col-sm-8 col-xs-9">
                                    <label class="radio-inline">
                                        <input type="radio" value="1" name="newsletter"> Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" checked="checked" value="0" name="newsletter"> No
                                    </label>
                                </div>
                            </div>
                        </fieldset> -->
                    </div>
                </div>
            </form>
        </div>
        <!--Middle Part End-->
        <!--Right Part Start -->
        <aside class="col-sm-3 hidden-xs" id="column-right">
            <h2 class="subtitle">Account</h2>
            <div class="list-group">
                <ul class="list-item">
                    <li><a href="{{ route('customer.my-account') }}">My Account</a></li>
                    <li><b>Account Settings</b></li>
                    <li><a href="{{ route('customer.wishlist') }}">Wish List</a></li>
                    <li><a href="{{ route('customer.orders') }}">Order History</a></li>
                </ul>
            </div>
        </aside>
        <!--Right Part End -->
    </div>
</div>

@endsection

@push('post-scripts')
    <script>
        $('.billing_shipping_country').change(function(){

            state_input_id =  $(this).data('state-input-id');
            state_id = $(this).data('state-id');
            country_id = $(this).val();

            // alert($('#'+state_input_id).val());
            // return;

            call_ajax_function(country_id, state_id, state_input_id);
            
            // alert($(this).data('state-input-id'));
        });

        $('.billing_shipping_country').each(function(){

            state_input_id =  $(this).data('state-input-id');
            state_id = $(this).data('state-id');
            country_id = $(this).val();

            call_ajax_function(country_id, state_id, state_input_id);
        });

        function call_ajax_function(country_id, state_id, state_input_id) {
            $.ajax({
                url : "{{ URL::route('get-states') }}",
                type: "POST",
                data: {
                        '_token' : '{{ csrf_token() }}',
                        country_id : country_id,
                        state_id : state_id
                    },
                beforeSend: function () {

                },
                success: function (response) {
                    
                   $('#'+state_input_id).html(response); 
                }
            });
        }
    </script>
@endpush