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
@section('content')
    <div class="container-fluid">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-md-6 col-sm-12">
                    <h2>View Role</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('admin/roles') }}">Roles</a></li>
                            <li class="breadcrumb-item active">View</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 col-sm-12 text-right ">
                    @can('role-create')
                        <a href="{{ route('admin.roles.create') }}" class="btn btn-sm btn-primary btn-round" title=""><i
                                class="fa fa-angle-double-left"></i> Go Back</a>
                    @endcan
                </div>
            </div>
        </div>

<div class="card">
    <div class="card-body">

     <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    {{ $role->name }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Permissions:</strong>
                    @if($role->name == 'Super Admin')
                        All Permission
                    @endif
                    @if(!empty($rolePermissions))
                        @foreach($rolePermissions as $v)
                            <label class="label label-success">{{ $v->name }},</label>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

    </div>
@endsection
