@extends('layouts.app')
@section('title','Register')
@section('content')
<div class="main-container container">
    <div class="bestsellers">
        <div class="title-bestsellers">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#product1">Register As Seller</a></li>
                <li class=""><a data-toggle="tab" href="#product2">Register As Customer</a></li>
            </ul>
        </div>
        <div class="product-bestsellers">
            <div class="tab-content">
                <div id="product1" class="tab-pane in active">
                    <div class="product-tab">
                        <form action="{{ url('/register') }}" method="POST" autocomplete="off">
                            @csrf
                            <input type="hidden" name="role" value="Vendor">
                            <div class="customer-login">
                                <div class="well">
                                    <h2><i class="fa fa-file-text-o" aria-hidden="true"></i> Register As A Seller</h2>
                                    <p><strong>I want to register as a Seller.</strong></p>
                                    <div class="form-group">
                                        <label class="control-label">Company Name</label>
                                        <input type="text" name="name" class="form-control" placeholder="eg: The Liquor House" value="{{ old('name') }}" required>

                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">E-Mail Address</label>
                                        <input type="email" name="email" onchange="check_email_availability(this.value)" value="{{ old('email') }}" class="form-control" placeholder="eg: hello@example.com" required>
                                        <small class="pull-right" style="font-size: 10px;" id="emailErrorMessage"></small>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">Unique Username</label>
                                        <input type="text" name="username" class="form-control" placeholder="eg: johndoe" onchange="check_username_availability(this.value)" value="{{ old('username') }}" id="userName" required>

                                        <small class="pull-right" style="color:green;" id="success">Username Available!</small>

                                        <small style="color:red;" class="pull-right" id="fail"> Sorry, username is already taken. Please enter different one! </small>
                                        
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">Password</label>
                                        <input type="password" name="password" class="form-control" minlength="8" required>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label ">Re-enter Password</label>
                                        <input type="password" name="password_confirmation" value="" minlength="8" class="form-control" required>
                                    </div>
                                </div>
                                <div class="bottom-form">
                                    <button type="submit" disabled id="saveUser" value="Register" class="btn btn-primary pull-right">Register</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="product2" class="tab-pane">
                    <div class="product-tab">
                        <form action="{{ url('/register') }}" method="POST" autocomplete="off">
                            @csrf
                            <input type="hidden" name="role" value="Customer">
                            <div class="customer-login">
                                <div class="well">
                                    <h2><i class="fa fa-file-text-o" aria-hidden="true"></i> Register As A Customer</h2>
                                    <p><strong>I want to register as a Customer.</strong></p>
                                    <div class="form-group">
                                        <label class="control-label">Full Name</label>
                                        <input type="text" name="name" class="form-control" placeholder="eg: The Liquor House" value="{{ old('name') }}" required>

                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">E-Mail Address</label>
                                        <input type="email" name="email" onchange="check_email_availability_customer(this.value)" value="{{ old('email') }}" class="form-control" placeholder="eg: hello@example.com" required>
                                        <small class="pull-right" style="font-size: 10px;" id="emailErrorMessage1"></small>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">Unique Username</label>
                                        <input type="text" name="username" class="form-control" placeholder="eg: johndoe" onchange="check_username_availability_customer(this.value)" value="{{ old('username') }}" id="userName" required>

                                        <small class="pull-right" style="color:green;" id="success1">Username Available!</small>

                                        <small style="color:red;" class="pull-right" id="fail1"> Sorry, username is already taken. Please enter different one! </small>
                                        
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">Password</label>
                                        <input type="password" name="password" class="form-control" minlength="8" required>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label ">Re-enter Password</label>
                                        <input type="password" name="password_confirmation" value="" minlength="8" class="form-control" required>
                                    </div>
                                </div>
                                <div class="bottom-form">
                                    <button type="submit" disabled id="saveUser1" value="Register" class="btn btn-primary pull-right">Register</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('post-scripts')
    <script type="text/javascript">
        $("#success").hide();
        $("#fail").hide();

        $("#success1").hide();
        $("#fail1").hide();

        function check_email_availability(email, user_id = 0) {
        

            var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            if (!emailReg.test(email)) {
                $("#emailErrorMessage").css('color','red');
                $("#emailErrorMessage").html('Please Enter Valid Email');
                return;
            }

            
            
            if (email != '') {
                
                $.ajax({
                    url : "{{ URL::route('users.email.availability') }}",
                    type : "POST",
                    data :{ '_token': '{{ csrf_token() }}',
                    email: email,
                    id: user_id,
                    },
                    beforeSend: function(){                
                        $("#userEmail").val(email);
                    },
                    success : function(response)
                    {
                        console.log("success");
                        console.log("response "+ response);
                        if(response == 1){
                            $("#emailErrorMessage").css('color','green');
                            $("#emailErrorMessage").html('Email Available!')
                            $("#saveUser").prop("disabled", false);
                        }else{
                            $("#emailErrorMessage").css('color','red');
                            $("#emailErrorMessage").html('Email is already taken. Please enter Different one!');
                            $("#saveUser").prop("disabled", true);
                        }
                    }
                });
            }else{
                $("#saveUser").prop("disabled", true);
                $("#emailErrorMessage").html('');
            }
        }

        function check_email_availability_customer(email, user_id = 0) {
        

            var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            if (!emailReg.test(email)) {
                $("#emailErrorMessage1").css('color','red');
                $("#emailErrorMessage1").html('Please Enter Valid Email');
                return;
            }

            
            
            if (email != '') {
                
                $.ajax({
                    url : "{{ URL::route('users.email.availability') }}",
                    type : "POST",
                    data :{ '_token': '{{ csrf_token() }}',
                    email: email,
                    id: user_id,
                    },
                    beforeSend: function(){                
                        $("#userEmail").val(email);
                    },
                    success : function(response)
                    {
                        console.log("success");
                        console.log("response "+ response);
                        if(response == 1){
                            $("#emailErrorMessage1").css('color','green');
                            $("#emailErrorMessage1").html('Email Available!')
                            $("#saveUser1").prop("disabled", false);
                        }else{
                            $("#emailErrorMessage1").css('color','red');
                            $("#emailErrorMessage1").html('Email is already taken. Please enter Different one!');
                            $("#saveUser1").prop("disabled", true);
                        }
                    }
                });
            }else{
                $("#saveUser1").prop("disabled", true);
                $("#emailErrorMessage1").html('');
            }
        }

        function check_username_availability(username) {
            if (username != '') {
                username = username.toLowerCase();
                $.ajax({
                    url : "{{ URL::route('users.availability') }}",
                    type : "POST",
                    data :{ '_token': '{{ csrf_token() }}',
                            username: username
                    },
                    beforeSend: function(){
                        $("#userName").val(username);
                    },
                    success : function(response)
                    {
                        // console.log("success");
                        // console.log("response "+ response);
                        if(response == 1){
                            $("#fail").hide();
                            $("#success").show();
                            $("#saveUser").prop("disabled", false);
                        }else{
                            $("#fail").show();
                            $("#success").hide();
                            $("#saveUser").prop("disabled", true);
                        }
                    }
                });
            }else{
                $("#saveUser").prop("disabled", true);
                $("#success").hide();
                $("#fail").hide();
            }
        }

        function check_username_availability_customer(username) {
            if (username != '') {
                username = username.toLowerCase();
                $.ajax({
                    url : "{{ URL::route('users.availability') }}",
                    type : "POST",
                    data :{ '_token': '{{ csrf_token() }}',
                            username: username
                    },
                    beforeSend: function(){
                        $("#userName").val(username);
                    },
                    success : function(response)
                    {
                        // console.log("success");
                        // console.log("response "+ response);
                        if(response == 1){
                            $("#fail1").hide();
                            $("#success1").show();
                            $("#saveUser1").prop("disabled", false);
                        }else{
                            $("#fail1").show();
                            $("#success1").hide();
                            $("#saveUser1").prop("disabled", true);
                        }
                    }
                });
            }else{
                $("#saveUser1").prop("disabled", true);
                $("#success1").hide();
                $("#fail1").hide();
            }
        }
    </script>
@endpush