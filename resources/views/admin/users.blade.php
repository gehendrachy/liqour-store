@extends('admin/layouts.header-sidebar')
@section('title', $id != 0 ? $user->name : 'Users')
@section('content')
<div class="container-fluid">
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-md-6 col-sm-12">
                <h2>User List</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard')  }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.list') }}">Users</a></li>
                        <li class="breadcrumb-item active" aria-current="page">User List</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-6 col-sm-12 text-right hidden-xs">
                <a href="{{ $id != 0 ? route('admin.users.list') : route('admin.dashboard') }}" class="btn btn-sm btn-outline-primary" title="Go Back">
                    <i class="fa fa-angle-double-left"></i> Go Back
                </a>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs2">
                        @if($id == 0)
                        <li class="nav-item"><a class="nav-link show active" data-toggle="tab" href="#Users">Users</a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link {{ $id != 0 ? 'show active' : '' }}" data-toggle="tab" href="#addUser">
                                {{ $id == 0 ? 'Add User' : 'Update User | '.$user->name }}
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body bg-transparent">
                    <div class="tab-content mt-0">
                        @if($id == 0)
                        <div class="tab-pane show active" id="Users">
                            <div class="table-responsive">
                                <table id="only-bodytable" class="table table-hover table-custom spacing8">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th class="w60"></th>
                                            <th>Full Name</th>
                                            <th>Role</th>
                                            <th>Created Date</th>
                                            <th>Status</th>
                                            <th class="w100">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $user)
                                        <tr>
                                            <td><i class="table-dragger-handle sindu_handle"></i></td>
                                            <td class="width45">
                                                @php
                                                $parts = explode(' ', $user->name);
                                                $userRole = $user->roles->pluck('name')->first();
                                                @endphp
                                                <div class="avtar-pic w35 @if($userRole == 'Vendor') bg-pink @else bg-blue @endif "
                                                data-toggle="tooltip"
                                                data-placement="top" title=""
                                                data-original-title="{{ $user->name }}">
                                                <span>
                                                    @for($i=0; $i < count($parts); $i++)
                                                    {{ strtoupper(substr($parts[$i], 0, 1)) }} 
                                                    @endfor
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <h6 class="mb-0">{{ $user->name }}</h6>
                                            <span>{{ $user->email }}</span>
                                        </td>
                                        <td>
                                            @if(!empty($user->getRoleNames()))
                                            @foreach($user->getRoleNames() as $v)
                                            <label class="badge badge-success">{{ $v }}</label>
                                            @endforeach
                                            @endif
                                                <!-- @if($user->role == 1)
                                                <span class="badge badge-success">Super Admin</span>
                                                @else
                                                <span class="badge badge-primary">Editor</span>
                                                @endif -->
                                            </td>

                                            <td>{{ date('jS F, Y',strtotime($user->created_at)) }}</td>
                                            <td>
                                                @if($user->status == 1)
                                                <span class="badge badge-success">Active</span>
                                                @else
                                                <span class="badge badge-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="#viewModal" class="btn btn-sm btn-outline-success" data-toggle="modal"
                                                data-id="{{ $user->id }} "
                                                id="view{{ $user->id }}"
                                                data-email="{{ $user->email }}"
                                                data-gender="{{ $user->gender }}"
                                                data-phone="{{ $user->phone }}"
                                                data-address="{{ $user->address }}"
                                                data-city="{{ $user->city }}"
                                                data-region="{{ $user->region }}"
                                                data-country="{{ $user->country }}"
                                                onclick="view_user('{{ $user->id }}','{{ addslashes($user->name) }}','{{ $user->status }}')"
                                                title="View"><i class="fa fa-eye"></i></a>

                                                <a href="{{ url('admin/users/edit/'.base64_encode($user->id)) }}" class="btn btn-sm btn-outline-primary" title="View">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                @if($user->id != \Illuminate\Support\Facades\Auth::user()->id)
                                                <a href="#delete" data-toggle="modal" data-id="{{ $user->id }}" id="delete{{ $user->id }}" class="btn btn-sm btn-outline-danger" title="Delete" onclick="delete_user('{{ $user->id }}')">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                        <div class="tab-pane {{ $id != 0 ? 'show active' : '' }}" id="addUser">
                            <div class="body mt-2">
                                <form id="parsley-form" method="post" action="{{ $id == 0 ? route('admin.users.create') : route('admin.users.update') }}">
                                    @csrf
                                    <input type="hidden" id="userId" name="id" value="{{ $id != 0 ? $id : '' }}"/>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" style="min-width: 100px; background-color: #e1e8ed">
                                                        <i class="fa fa-text-width"></i> &nbsp; Name
                                                    </span>
                                                </div>
                                                <input type="text" name="name" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" required value="{{ $id != 0 ? $user->name : '' }}" placeholder="eg: John Doe">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" style="min-width: 100px; background-color: #e1e8ed">
                                                        <i class="fa fa-envelope"></i> &nbsp;Email
                                                    </span>
                                                </div>
                                                <input type="email" name="email" class="form-control"
                                                aria-label="Default" aria-describedby="inputGroup-sizing-default" required value="{{ $id != 0 ? $user->email : '' }}" <?= $id != 0 ? "disabled readonly" : "" ?> placeholder="eg: hello@example.com" onchange="check_email_availability(this.value)" id="userEmail">

                                            </div>
                                            <small style="font-size: 8px;" id="emailErrorMessage"></small>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group ">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" style="min-width: 100px; background-color: #e1e8ed">
                                                        <i class="fa fa-text-width"></i> &nbsp; Unique Username
                                                    </span>
                                                </div>
                                                <input type="text" name="username" class="form-control" required value="{{ $id != 0 ? $user->username : '' }}" placeholder="eg: johndoe" onchange="check_username_availability(this.value)" id="userName" <?=$id != 0 ? 'readonly' : '' ?>>
                                            </div>
                                            @if($id == 0)
                                            <small class="pull-right" style="color:green;" id="success">Username Available!</small>
                                            <small style="color:red;" class="pull-right" id="fail"> Sorry, username is already taken. Please enter different one! </small>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" style="min-width: 100px; background-color: #e1e8ed" for="inputGroupSelect01"><i class="fa fa-user"></i>&nbsp;  Role</label>
                                                </div>
                                                <select class="custom-select" name="role" required>
                                                    <option disabled selected="" value="">Choose User's Role...</option>
                                                    @foreach($roles as $key => $role)
                                                    <option value="{{ $key }}" <?= $id != 0 && $userRole == $key ? 'selected' : '' ?>>{{$key}}</option>
                                                    @endforeach
                                                    <!-- <option value="2" {{ $id != 0 && $user->role == 2 ? 'selected' : '' }}>Editor</option> -->
                                                </select>
                                            </div>
                                        </div>
                                        @if($id == 0)
                                        <div class="col-md-6">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" style="min-width: 100px; background-color: #e1e8ed">
                                                        <i class="fa fa-lock"></i> &nbsp;Password
                                                    </span>
                                                </div>
                                                <input type="password" name="password" class="form-control"
                                                aria-label="Default"
                                                aria-describedby="inputGroup-sizing-default" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" style="min-width: 100px; background-color: #e1e8ed">
                                                        <i class="fa fa-lock"></i> &nbsp;Re-Type Password
                                                    </span>
                                                </div>
                                                <input type="password" name="password_confirmation" class="form-control"
                                                aria-label="Default"
                                                aria-describedby="inputGroup-sizing-default">
                                            </div>
                                        </div>
                                        @endif
                                        <div class="col-md-6">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text" style="background-color: #e1e8ed">
                                                        <?php $status =  $id != 0 ? $user->status : 0  ?>
                                                        <input type="checkbox" name="status" value="1" aria-label="Checkbox for following text input" <?=$status == 1 ? 'checked' : '' ?>>
                                                    </div>
                                                </div>
                                                <input type="button " class="form-control bg-indigo text-muted" value="Active" disabled>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" style="min-width: 100px; background-color: #e1e8ed">
                                                        <i class="fa fa-text-width"></i> &nbsp;Phone
                                                    </span>
                                                </div>
                                                <input type="text" name="phone" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" required value="{{ $id != 0 ? $user->phone : '' }}" placeholder="eg: 9876543210">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" style="min-width: 100px; background-color: #e1e8ed">
                                                        <i class="fa fa-text-width"></i> &nbsp;Address
                                                    </span>
                                                </div>
                                                <input type="text" name="address" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" required value="{{ $id != 0 ? $user->address : '' }}" placeholder="eg: Kathmandu, Nepal">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" style="min-width: 100px; background-color: #e1e8ed">
                                                        <i class="fa fa-text-width"></i> &nbsp;City
                                                    </span>
                                                </div>
                                                <input type="text" name="city" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" required value="{{ $id != 0 ? $user->city : '' }}" placeholder="eg: Kathmandu">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" style="min-width: 100px; background-color: #e1e8ed" for="inputGroupSelect01"><i class="fa fa-user"></i>&nbsp; Country</label>
                                                </div>
                                                <select id="inputCountry" class="custom-select" name="country" onchange='getStates(this.value,"{{ $user->region }}")' required>
                                                    <option disabled selected="" value="">Choose...</option>
                                                    @php
                                                    $countries = DB::table('countries')->get();
                                                    @endphp
                                                    @foreach($countries as $con)
                                                    <option value="{{ $con->name }}" {{ $id != 0 && $user->country == $con->name ? 'selected' : '' }}>{{ $con->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" style="min-width: 100px; background-color: #e1e8ed">
                                                        <i class="fa fa-text-width"></i> &nbsp;Region
                                                    </span>
                                                </div>
                                                <select id="inputState" class="custom-select" name="region" required>
                                                    <option disabled selected="" value="">Choose...</option>
                                                    <option value='{{ $user->region }}' selected>{{ $user->region }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" style="min-width: 100px; background-color: #e1e8ed" for="inputGroupSelect01"><i class="fa fa-user"></i>&nbsp; Gender</label>
                                                </div>
                                                <select class="custom-select" name="gender" required>
                                                    <option disabled selected="" value="">Choose...</option>
                                                    <option value="1" {{ $id != 0 && $user->gender == 1 ? 'selected' : '' }}>Male</option>
                                                    <option value="2" {{ $id != 0 && $user->gender == 2 ? 'selected' : '' }}>Female</option>
                                                    <option value="3" {{ $id != 0 && $user->gender == 3 ? 'selected' : '' }}>Others</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            @if ($id != 0)
                                            <a href="{{ route('admin.users.list') }}"
                                            class="btn btn-outline-danger">CANCEL</a>

                                            <button type="submit" style="float: right;" class="btn btn-outline-success"> UPDATE</button>
                                            @else
                                            <button id="saveUser" type="submit" style="float: right;" class="btn btn-outline-success"> SAVE</button>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade " id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6><span class="viewName"></span><span class="mb-0" id="viewStatus"></span></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pricing_page">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-0">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <small class="text-muted"><i class="fa fa-user"></i> Name: </small>
                                        <p class="mb-0 viewName"></p>
                                    </li>
                                    <li class="list-group-item">
                                        <small class="text-muted"><i class="fa fa-envelope"></i> Email: </small>
                                        <p class="mb-0" id="viewEmail"></p>
                                    </li>
                                    <li class="list-group-item">
                                        <small class="text-muted"><i class="fa fa-male"></i><i class="fa fa-female"></i> Gender: </small>
                                        <p class="mb-0" id="viewGender"></p>
                                    </li>
                                    <li class="list-group-item">
                                        <small class="text-muted"><i class="fa fa-phone"></i> Contact Number: </small>
                                        <p class="mb-0" id="viewPhone"></p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-0">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <small class="text-muted"><i class="fa fa-map-marker"></i> Address: </small>
                                        <p class="mb-0" id="viewAddress"></p>
                                    </li>
                                    <li class="list-group-item">
                                        <small class="text-muted"><i class="fa fa-building"></i> City: </small>
                                        <p class="mb-0" id="viewCity"></p>
                                    </li>
                                    <li class="list-group-item">
                                        <small class="text-muted"><i class="fa fa-location-arrow"></i> Region: </small>
                                        <p class="mb-0" id="viewRegion"></p>
                                    </li>
                                    <li class="list-group-item">
                                        <small class="text-muted"><i class="fa fa-globe"></i> Country: </small>
                                        <p class="mb-0" id="viewCountry"></p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button style="text-align: right;" type="button" data-dismiss="modal"
                    class="btn btn-outline-danger">Close
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade " id="delete" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6>Delete User</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body pricing_page">
                <p>Are your Sure?</p>
            </div>
            <div class="modal-footer">
                <a style="text-align: right;" type="button" class="btn btn-outline-success" href="">Yes, Delete It!</a>
                <button style="text-align: left;" type="button" data-dismiss="modal" class="btn btn-outline-danger">No</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    

    if ($("#inputCountry").val() != null) {
        getStates($("#inputCountry").val(), $("#inputState").val());
    }

    function getStates($cName, $regionState){
        $.ajax({
            url : "{{url('admin/users/get_states/')}}/"+$cName+"?region="+$regionState,
            cache : false,
            beforeSend : function (){
                $('#inputState').empty();
            },
            complete : function($response, $status){
                if ($status != "error" && $status != "timeout") {
                    var obj = jQuery.parseJSON($response.responseText);
                    var countries = jQuery.parseJSON(obj['country_list']);

                    $("#inputZip").val(obj['postal_code']);

                    for (var i = 0; i < countries.length; i++) {
                        $('#inputState').append(countries[i]);
                    }
                }
            },
            error : function ($responseObj){
                alert("Something went wrong while processing your request.\n\nError => "
                    + $responseObj.responseText);
            }
        }); 
    }


    $("#saveUser").prop("disabled", true);
    $("#success").hide();
    $("#fail").hide();

    function check_username_availability(username, user_id = 0) {

        // check_username_email_status();

        if (username != '') {
            username = username.toLowerCase();
            $.ajax({
                url : "{{ URL::route('admin.users.availability') }}",
                type : "POST",
                data :{ '_token': '{{ csrf_token() }}',
                username: username,
                id: user_id,
            },
            beforeSend: function(){                
                $("#userName").val(username);
            },
            success : function(response)
            {
                console.log("success");
                console.log("response "+ response);
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

    function check_email_availability(email, user_id = 0) {
        

        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if (!emailReg.test(email)) {
            $("#emailErrorMessage").css('color','red');
            $("#emailErrorMessage").html('Please Enter Valid Email');
            return;
        }

        
        
        if (email != '') {
            // email = email.toLowerCase();
            $.ajax({
                url : "{{ URL::route('admin.users.email.availability') }}",
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
            $("#success").hide();
            $("#fail").hide();
        }
    }

    // function check_username_email_status() {
    //     var email = $("#userEmail").val();
    //     var username = $("#userName").val();

    //     if (email != null || username != null) {
    //         $("#saveUser").prop("disabled", true);    
    //     }else{
        
    //         $("#saveUser").prop("disabled", false);
    //     }
    // }

    function view_user(id, name, status) {
        var email = $('#view' + id).attr('data-email');
        var gender = $('#view' + id).attr('data-gender');
        var phone = $('#view' + id).attr('data-phone');
        var address = $('#view' + id).attr('data-address');
        var city = $('#view' + id).attr('data-city');
        var region = $('#view' + id).attr('data-region');
        var country = $('#view' + id).attr('data-country');
        $('.viewName').html(name);
        $('#viewEmail').html(email);
        $('#viewPhone').html(phone);
        $('#viewAddress').html(address);
        $('#viewCity').html(city);
        $('#viewRegion').html(region);
        $('#viewCountry').html(country);

        if (gender == 1) {
            $('#viewGender').html('Male');
        }else if (gender == 2) {
            $('#viewGender').html('Female');
        }else if (gender == 3) {
            $('#viewGender').html('Others');
        }else{
            $('#viewGender').html('To be Updated');
        }
        if (status == 0) {
            $('#viewStatus').html('<span class="badge badge-danger">Inactive</span>');
        }else{
            $('#viewStatus').html('<span class="badge badge-success">Active</span>');
        }
    }

    function delete_user(id) {
        var conn = './users/delete/' + id;
        $('#delete a').attr("href", conn);
    }
</script>
@endsection
