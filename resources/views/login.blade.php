@extends('layouts.app')
@section('title','LogIn')
@section('content')
<div class="main-container container">
    <ul class="header-main ">
        <li class="home"><a href="#">Home   </a><i class="fa fa-angle-right" aria-hidden="true"></i></li>
        <li class="home"><a href="#">Account   </a><i class="fa fa-angle-right" aria-hidden="true"></i></li>
        <li>  Login</li>
    </ul>

    <div class="row">
        <div id="content" class="col-sm-12">
            <div class="page-login">

                <div class="account-border">
                    <div class="row">
                        <div class="col-sm-6 new-customer">
                            <div class="well">
                                <h2><i class="fa fa-file-o" aria-hidden="true"></i> New Customer</h2>
                                <p>By creating an account you will be able to shop faster, be up to date on an order's status, and keep track of the orders you have previously made.</p>
                            </div>
                            <div class="bottom-form">
                                <a href="{{ route('user-register') }}" class="btn btn-default pull-right">Continue</a>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="col-sm-6 customer-login">
                                <div class="well">
                                    <h2><i class="fa fa-file-text-o" aria-hidden="true"></i> Returning Customer</h2>
                                    <p><strong>I am a returning customer</strong></p>
                                    <div class="form-group">
                                        <label class="control-label " for="input-email">E-Mail Address</label>
                                        <input type="text" name="login" value="{{ old('username') ?: old('email') }}" required autofocus id="input-email" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label " for="input-password">Password</label>
                                        <input type="password" name="password" value="" id="input-password" class="form-control">
                                    </div>
                                </div>
                                <div class="bottom-form">
                                    <a href="#" class="forgot">Forgotten Password</a>
                                    <input type="submit" value="Login" class="btn btn-default pull-right">
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