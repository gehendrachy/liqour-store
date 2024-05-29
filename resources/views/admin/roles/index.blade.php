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
                    <h2>Role Management</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Roles</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 col-sm-12 text-right ">
                    @can('role-create')
                        <a href="{{ route('admin.roles.create') }}" class="btn btn-sm btn-primary btn-round" title=""><i
                                class="fa fa-user-plus"></i> Create New Role</a>
                    @endcan
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
                    <div class="table-responsive">
                        <table class="table table-hover table-custom spacing8">
                            <thead>
                            <tr>
                                <th>SN</th>
                                <th>Name</th>
                                <th width="280px">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($roles as $key => $role)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>
                                        <a class="btn btn-info"
                                           href="{{ route('admin.roles.show',$role->id) }}">Show</a>
                                        @if($role->id !=1)
                                            @can('role-edit')
                                                <a class="btn btn-primary"
                                                   href="{{ route('admin.roles.edit',$role->id) }}">Edit</a>
                                            @endcan

                                            @can('role-delete')
                                                {!! Form::open(['method' => 'DELETE','route' => ['admin.roles.destroy', $role->id],'style'=>'display:inline']) !!}
                                                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                                {!! Form::close() !!}
                                            @endcan
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>


                        {!! $roles->render() !!}
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

