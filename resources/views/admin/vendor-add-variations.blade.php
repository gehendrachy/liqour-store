@extends('admin/layouts.header-sidebar')
@section('title', 'Add New Variations')
@section('content')
    <div class="container-fluid">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-md-6 col-sm-12">

                    <h2>Variations</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('vendor.dashboard', ['username' => $username])  }}"><i class="fa fa-tachometer"></i> Dashboard</a>
                            </li>
                            
                            <li class="breadcrumb-item">
                                <a href="{{ route('vendor.add-variations', ['username' => $username]) }}"><i class="fa fa-anchor"></i> Variations</a>
                            </li>

                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 col-sm-12 text-right hidden-xs">
                    <a href="{{ route('vendor.add-products', ['username' => $username]) }}" class="btn btn-sm btn-outline-primary" title="Go Back">
                        <i class="fa fa-angle-double-left"></i> Go Back
                    </a>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link show active" data-toggle="tab" href="#addVariation">
                            {{ $id == 0 ? 'Add Variation' : 'Update Variation '}}
                        </a>
                    </li>
                </ul>
                <div class="tab-content mt-0">
                    <div class="tab-pane show active" id="addVariation">
                        <div class="card">
                            <div class="header card-header">
                                <h6 class="title mb-0">Add New Variations</h6>
                            </div>
                            <div class="body mt-2">
                                <form method="post" action="{{ route('vendor.add-variations.store', ['username' => $username]) }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputGroup-sizing-default"><i class="fa fa-text-width"></i> &nbsp;Title</span>
                                                </div>
                                                <input type="text" name="title" class="form-control"  required value="{{ old('title') }}" placeholder="eg: Enter Variation Title Here..">
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-header" style="background: #d2d6de;">
                                                <b class="title mb-0">Related Sub Variations with this Variation</b>
                                            </div>
                                            <div class="card-body">
                                                <div class="row" id="sub_variations_field">
                                                    <div class="col-md-4" >
                                                        <div class="input-group mb-3">
                                                            <input type="text" name="sub_variations[]" placeholder="eg: Cans, 12Oz Bottles" class="form-control name_list" required/>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <button type="button" name="addSubVariations" id="addSubVariations" class="btn btn-success">Add More</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="clearfix"></div>
                                        <div class="col-md-12">
                                            
                                            <a href="{{ route('vendor.add-products', ['username' => $username]) }}"
                                            class="btn btn-outline-danger">CANCEL</a>

                                            <button type="submit" style="float: right;" class="btn btn-outline-success"> UPDATE</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="clearfix"></div>
            

        </div>

    </div>


@endsection
@section('script')
    <script>
        $(document).ready(function(){
            var i=1;

            $('#addSubVariations').click(function(){  
                i++;

                $.ajax({
                    url : "{{ url('vendor/'.$username.'/add-variations/addSubVariations') }}/"+i,
                    cache : false,
                    beforeSend : function (){

                    },
                    complete : function($response, $status){
                        if ($status != "error" && $status != "timeout") {
                            $('#sub_variations_field').append($response.responseText);
                        }
                    },
                    error : function ($responseObj){
                        alert("Something went wrong while processing your request.\n\nError => "
                            + $responseObj.responseText);
                    }
                }); 
            });

            $(document).on('click', '.btn_remove', function(){  
                var button_id = $(this).attr("id");   
                $('#row'+button_id).remove();  
            });  

        });

    </script>

@endsection
