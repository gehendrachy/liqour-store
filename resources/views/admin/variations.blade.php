@extends('admin/layouts.header-sidebar')
@section('title', isset($variation) ? $variation->title : 'Variations')
@section('content')
    <script>
        $(document).ready(function () {
            var updateOutput = function (e) {
                var list = e.length ? e : $(e.target), output = list.data('output');

                $.ajax({
                    method: "POST",
                    url: "{{ URL::route('admin.order_variations')}}",
                    data: {
                        '_token': $('input[name=_token]').val(),
                        list_order: list.nestable('serialize'),
                        parent_id: $('#parentId').val(),
                        table: "variations"
                    },
                    success: function (response) {
                        console.log("success");
                        console.log("response " + response);
                        var obj = jQuery.parseJSON(response);
                        if (obj.status == 'success') {
                            swal({
                                title: 'Success!',
                                buttonsStyling: false,
                                confirmButtonClass: "btn btn-success",
                                html: '<b>Content</b> Sorted Successfully',
                                timer: 1000,
                                type: "success"
                            }).catch(swal.noop);
                        }
                        ;

                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    sweetAlert('Failure', 'Something Went Wrong!', 'error');
                });
            };

            $('#nestable').nestable({
                group: 1,
                maxDepth: 1,
            }).on('change', updateOutput);
        });
    </script>

    <?php
    function displayList($list)
    {
    ?>
    <ol class="dd-list">
        <?php

        foreach ($list as $item):
        ?>
        <li class="dd-item dd3-item" data-id="{{ $item->id }} ">
            <div class="dd-handle dd3-handle"></div>
            <div class="dd3-content">
                <b>{{ $item->title }}</b>&nbsp;|&nbsp; 
                <small>
                <?php
                foreach($item->sub_variation as $key => $sub){
                    echo '<i class="badge badge-primary">'.$sub->title.'</i>';
                    if ($key > 1) {
                        $remaining = $item->sub_variation->count()-$key-1;
                        echo '<i class="badge badge-primary"> And '. $remaining.' more</i>';
                        break;
                    }
                }
                ?>
                
                </small>


                <small>
                    <i>
                        @if($item->display == 1)
                        <span class="badge badge-success mr-0 ml-0" style="font-size: 7px;">Displayed</span>
                        @else
                        <span class="badge badge-danger mr-0 ml-0" style="font-size: 7px;">Not Displayed</span>
                        @endif

                    </i>
                </small>
                <span class="content-right">

                    <a href="{{ url('admin/variations/edit/'.base64_encode($item->id)) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fa fa-edit"></i></a>

                    <a href="#delete"
                       data-toggle="modal"
                       data-id="{{ $item->id }}"
                       id="delete{{ $item->id }}"
                       title="Delete" 
                       class="btn btn-sm btn-outline-danger center-block"
                       onClick="delete_menu('{{ base64_encode($item->id) }}')"><i class="fa fa-trash  "></i>
                   </a>
                </span>
            </div>

        </li>
        <?php
        endforeach; ?>
    </ol>
    <?php
    }
    ?>

    <div class="container-fluid">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-md-6 col-sm-12">

                    <h2>Variations</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard')  }}"><i class="fa fa-tachometer"></i> Dashboard</a>
                            </li>
                            
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.variations') }}"><i class="fa fa-anchor"></i> Variations</a>
                            </li>
                            
                            @if($id != 0)
                            <li class="breadcrumb-item active" aria-current="page">{{ isset($variation) ? $variation->title : 'Variations' }}</li>
                            @endif
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 col-sm-12 text-right hidden-xs">
                    @if(base64_decode(request()->route()->parameter('parent_id')) == '')
                        <a href="{{ $id != 0 ? route('admin.variations') : route('admin.dashboard') }}" class="btn btn-sm btn-outline-primary" title="Go Back">
                            <i class="fa fa-angle-double-left"></i> Go Back
                        </a>
                    @else
                        <a href="{{ route('admin.variations') }}" class="btn btn-sm btn-outline-primary" title="Go Back">
                            <i class="fa fa-angle-double-left"></i> Go Back
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12">
                <ul class="nav nav-tabs">
                    @if($id == 0)
                    <li class="nav-item"><a class="nav-link show  active" data-toggle="tab" href="#Variations">{{ isset($variation) ? $variation->title : 'Variations' }}</a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link {{ $id != 0 ? 'show active' : '' }}" data-toggle="tab" href="#addVariation">
                            {{ $id == 0 ? 'Add Variation' : 'Update Variation '}}
                        </a>
                    </li>
                </ul>
                <div class="tab-content mt-0">
                    @if($id == 0)
                    <div class="tab-pane show active" id="Variations">
                        <div class="card">
                            <div class="header card-header">
                                <h6 class="title mb-0">All {{ isset($variation) ? $variation->title : 'Variations' }}</h6>
                            </div>
                            <div class="body mt-0">
                                <div class="dd nestable-with-handle" id="nestable">
                                    <?php isset($variations) ? displayList($variations) : '' ?>
                                </div>
                            </div>
                        </div>

                    </div>
                    @endif
                    <div class="tab-pane {{ $id != 0 ? 'show active' : '' }}" id="addVariation">
                        <div class="card">
                            <div class="header card-header">
                                <h6 class="title mb-0">{{ isset($variation) ? $variation->title : 'Add Variations' }}</h6>
                            </div>
                            <div class="body mt-2">
                                <form method="post" action="{{ $id == 0 ? route('admin.variations.create') : route('admin.variations.update') }}" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" id="variationId" name="id" value="{{ $id != 0 ? $id : '' }}"/>
                                    <input type="hidden" id="parentId" name="parent_id" value="{{ isset($variation) ? $variation->id : 0 }}">
                                    @if($id == 0)
                                    <input type="hidden" name="url" value="{{ url()->current() }}">
                                    @else
                                    <input type="hidden" name="url" value="{{ url()->previous() }}">
                                    @endif
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputGroup-sizing-default"><i class="fa fa-text-width"></i> &nbsp;Title</span>
                                                </div>
                                                <input type="text" name="title" class="form-control"  required value="{{ $id != 0 ? $variation->title : '' }}" placeholder="eg: Enter Variation Title Here..">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <?php $display =  $id != 0 ? $variation->display : 0  ?>
                                                        <input type="checkbox" name="display" value="1" <?=$display == 1 ? 'checked' : ($id == 0 ? 'checked' : '') ?>>
                                                    </div>
                                                </div>
                                                <input type="button " class="form-control bg-indigo text-muted" value="Display" disabled>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-header" style="background: #d2d6de;">
                                                <b class="title mb-0">Related Sub Variations with this Variation</b>
                                            </div>
                                            <div class="card-body">
                                                <div class="row" id="sub_variations_field">
                                                    <?php
                                                        if ($id != 0) {
                                                            $sub_variations = $variation->sub_variation;
                                                            ?>
                                                            <input type="hidden" id="subVariationCount" value="<?=count($sub_variations)+1;?>">
                                                            <?php 
                                                                foreach ($sub_variations as $i => $sub_variation) {
                                                                    ?>
                                                                    <input type="hidden" name="sub_variation_id[]" value="{{ $sub_variation->id }}">
                                                                    <div class="col-md-3" id="row{{$i+1}}">
                                                                        <div class="input-group mb-3">
                                                                            <input type="text" name="sub_variation_db[]" placeholder="Sub Variations Related to Variation" class="form-control name_list" value="{{ $sub_variation->title }}" required/>
                                                                            @if($sub_variations->count() > 1)
                                                                            <div class="input-group-prepend">
                                                                                <a href="#deleteSubVariation"
                                                                                   data-toggle="modal"
                                                                                   data-id="{{ $sub_variation->id }}"
                                                                                   id="delete_team{{ $sub_variation->id }}"
                                                                                   class="btn btn-sm btn-danger delete"
                                                                                   onclick="delete_sub_variation('{{ base64_encode($sub_variation->id) }}')">
                                                                                   <i class=" fa fa-trash"></i>
                                                                                </a>
                                                                            </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                }
                                                            ?>
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <div class="col-md-4" >
                                                                <div class="input-group mb-3">
                                                                    <input type="text" name="sub_variations[]" placeholder="eg: Cans, 12Oz Bottles" class="form-control name_list" required/>
                                                                </div>
                                                            </div>

                                                            <?php
                                                        }
                                                    ?>
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
                                            @if ($id != 0)
                                            <a href="{{ route('admin.variations') }}"
                                            class="btn btn-outline-danger">CANCEL</a>

                                            <button type="submit" style="float: right;" class="btn btn-outline-success"> UPDATE</button>
                                            @else
                                            <button type="submit" style="float: right;" class="btn btn-outline-success"> SAVE</button>
                                            @endif
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

        <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog " role="document">
                <div class="modal-content bg-danger">
                    <div class="modal-header">
                        <h5 class="modal-title text-white" id="exampleModalLabel">Delete Variation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-white">
                        <p>Are you Sure...!!</p>
                    </div>
                    <div class="modal-footer ">
                        <button type="button" class="btn btn-round btn-default" data-dismiss="modal">Close</button>
                        <a href="" class="btn btn-round btn-primary">Delete</a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade modal-primary" id="deleteSubVariation">
        <div class="modal-dialog " role="document">
            <div class="modal-content bg-primary">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="exampleModalLabel">Delete Sub Variation of this Variation?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-white">
                    <p>Are you Sure...!!</p>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-round btn-default" data-dismiss="modal">Close</button>
                    <a href="" class="btn btn-round btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('script')
    <script>
        $(document).ready(function(){
            var i=1;
            if ($("#variationId").val() != '') {
                i = $('#subVariationCount').val();
            }

            $('#addSubVariations').click(function(){  
                i++;

                $.ajax({
                    url : "{{url('admin/variations/addSubVariations/')}}/"+i,
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


        function delete_menu(id) {
            var conn = '{{ url("admin/variations/delete/")}}/' + id;
            $('#delete a').attr("href", conn);
        }

        function delete_sub_variation(id) {
            var conn = '{{ url("admin/variations/sub_variations/delete/")}}/' + id;
            $('#deleteSubVariation a').attr("href", conn);
        }

    </script>

@endsection
