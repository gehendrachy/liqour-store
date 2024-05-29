<?php
/**
 * Created By PhpStorm.
 * Project Name: ktma2zdeals
 * Author Name: Subas Nyaupane
 * Author Email: mail.subasnyaupane@gmail.com
 * Author Url : https://subasnyaupane.github.io/
 * Date: 01/Jan/2020
 */
?>
@extends('admin/layouts.header-sidebar')
@push('styles')
@endpush
@section('content')
    <div class="container-fluid">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-md-6 col-sm-12">
                    <h2>Create Role</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
                            <li class="breadcrumb-item active">Create</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 col-sm-12 text-right ">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-sm btn-primary btn-round" title=""><i
                            class="fa fa-angle-double-left"></i> Go Back</a>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12">
                <div class="card planned_task">
                    <div class="header">
                        <h2>Manage Role & Permission</h2>
                        <ul class="header-dropdown dropdown">
                            <li><a href="javascript:void(0);" class="full-screen"><i class="icon-frame"></i></a></li>

                        </ul>
                    </div>
                    {!! Form::open(array('route' => 'admin.roles.store','method'=>'POST')) !!}
                    <div class="body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            Name
                                        </div>
                                    </div>
                                    {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="card ">
                                    <div class="card-header">Permission</div>
                                    <div class="body">

                                        <div class="row">
                                            @foreach($permission as $value)
                                                <div class="col-3 mb-3">
                                                    <div
                                                        class="fancy-checkbox list-group-item d-flex justify-content-between align-items-center">
                                                        <label>{{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name')) }}
                                                            <span>{{ $value->name }}</span></label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-danger">Cancel</a>
                        <button type="submit" style="float: right" class="btn btn-success">Submit</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>

        </div>
    </div>
@endsection
@push('script')
@endpush


