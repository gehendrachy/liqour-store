@extends('admin/layouts.header-sidebar')
@section('title', isset($slider) ? $slider->title : 'Sliders')
@section('content')
<script>
    $(document).ready(function () {
        var updateOutput = function (e) {
            var list = e.length ? e : $(e.target), output = list.data('output');

            $.ajax({
                method: "POST",
                url: "{{ URL::route('admin.sliders.order_sliders')}}",
                data: {
                    '_token': $('input[name=_token]').val(),
                    list_order: list.nestable('serialize'),
                    parentid: "{{ isset($page)?$page->id:0 }}",
                    table: "pages"
                },
                success: function (response) {
                    console.log("success");
                    console.log("response " + response);
                    var obj = jQuery.parseJSON(response);

                    if (obj.status == 'success') {
                        toastr['success']('<b>Content</b> Sorted Successfully', 'Success!');
                        // swal({
                        //     title: 'Success!',
                        //     buttonsStyling: false,
                        //     confirmButtonClass: "btn btn-success",
                        //     html: '<b>Content</b> Sorted Successfully',
                        //     timer: 1000,
                        //     type: "success"
                        // }).catch(swal.noop);
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
                    <small>
                        <b>{{ $item->title }}</b>&nbsp;
                        @if($item->status == 1)
                        <small class="badge badge-success">Displayed</small>
                        @else
                        <small class="badge badge-danger">Not Displayed</small>
                        @endif
                    </small>
                    <span class="content-right">
                        <a href="#viewModal"
                        class="btn btn-sm btn-outline-success" data-toggle="modal"
                        data-id="{{ $item->id }} "
                        id="view{{ $item->id }}"
                        data-subtitles = '{{ addslashes($item->subtitles) }}'
                        onclick="view('{{ $item->id }}','{{ $item->title }}','{{ $item->status }}','{{ $item->image }}','{{ $item->buttonName }}','{{ $item->link }}')"
                        title="View"><i class="fa fa-eye"></i></a>
                        <a href="{{ url('admin/sliders/edit/'.$item->id) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fa fa-edit"></i></a>
                        <a href="#delete"
                        data-toggle="modal"
                        data-id="{{ $item->id }}"
                        id="delete{{ $item->id }}"
                        class="btn btn-sm btn-outline-danger center-block"
                        onClick="delete_menu({{ $item->id }} )"><i class="fa fa-trash  "></i></a>
                    </span>
                </div>

                <?php if (array_key_exists("children", $item)): ?>
                    <?php displayList($item->children); ?>
                <?php endif; ?>
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
            <div class="col-md-8 col-sm-12">

                <h2>Sliders</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard')  }}"><i class="icon-speedometer"></i> Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.sliders.list') }}"><i class="icon-layers"></i> Sliders</a></li>
                        @if($id != 0)
                        <li class="breadcrumb-item active" aria-current="page">{{ isset($slider) ? $slider->title : 'Sliders' }}</li>
                        @endif
                    </ol>
                </nav>
            </div>
            <div class="col-md-4 col-sm-12 text-right hidden-xs">
                <a href="{{ url('admin') }}" class="btn btn-outline-primary btn-round"><i class="fa fa-angle-double-left"></i> Go Back</a>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12">

            <ul class="nav nav-tabs">
                @if($id == 0)
                <li class="nav-item">
                    <a class="nav-link show  active" data-toggle="tab" href="#Pages">{{ isset($slider) ? $slider->title : 'Sliders' }}</a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link {{ $id != 0 ? 'show active' : '' }}" data-toggle="tab" href="#addPage">{{ $id == 0 ? 'Add Slider' : 'Update Slider' }}</a>
                </li>
            </ul>
            <div class="tab-content mt-0">
                @if($id == 0)
                <div class="tab-pane show active" id="Pages">
                    <div class="card">
                        <div class="header card-header">
                            <h6 class="title mb-0">All {{ isset($slider) ? $slider->title : 'Sliders' }}</h6>
                        </div>
                        <div class="body mt-0">
                            <div class="dd nestable-with-handle" id="nestable">
                                <?php isset($sliders) ? displayList($sliders) : '' ?>
                            </div>
                        </div>
                    </div>

                </div>
                @endif
                <div class="tab-pane {{ $id != 0 ? 'show active' : '' }}" id="addPage">
                    <div class="card">
                        <div class="header card-header">
                            <h6 class="title mb-0">{{ isset($slider) ? $slider->title : 'Add Sliders' }}</h6>
                        </div>
                        <div class="body mt-2">
                            <form method="post" action="{{ $id == 0 ? route('admin.sliders.create') : route('admin.sliders.update') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="sliderId" name="id" value="{{ $id != 0 ? $id : '' }}"/>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-text-width"></i> &nbsp;Title</span>
                                            </div>
                                            <input type="text" name="title" class="form-control" placeholder="Enter Slider Title Here" required value="{{ $id != 0 ? $slider->title : '' }}">
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-text-width"></i> &nbsp;Sub Title</span>
                                            </div>
                                            <input type="text" name="subtitles" class="form-control" placeholder="Enter Slider Subtitle Here" required value="{{ $id != 0 ? $slider->subtitles : '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-text-width"></i> &nbsp;Button Name</span>
                                            </div>
                                            <input type="text" name="buttonName" class="form-control" placeholder="Enter Slider Button Name Here" required value="{{ $id != 0 ? $slider->buttonName : '' }}">
                                        </div>
                                    </div> -->
                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-text-width"></i> &nbsp;Url</span>
                                            </div>
                                            <input type="text" name="link" class="form-control" placeholder="eg: www.ktmrush.com/about/" required value="{{ $id != 0 ? $slider->link : '' }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <?php $status =  $id != 0 ? $slider->status : 0  ?>
                                                    <input type="checkbox" name="status" value="1"
                                                    aria-label="Checkbox for following text input" <?=$status == 1 ? 'checked' : '' ?>>
                                                </div>
                                            </div>
                                            <input type="button " class="form-control bg-indigo text-muted"
                                            value="Display" disabled>
                                        </div>
                                    </div>

                                    <div class="col-md-6">

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-image"></i> &nbsp;Images</span>
                                            </div>
                                            <input type="file" name="image" class="bg-primary text-white form-control" <?=$id != 0 ? '' : 'required' ?>>

                                        </div>
                                        <div class="alert alert-warning"> Best Image Size 1920px X 600px </div>
                                    </div>

                                    <div class="clearfix"></div>

                                    <div class="col-md-12">
                                        @if ($id != 0)
                                        <a href="{{ route('admin.sliders.list') }}"
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
        <div class="col-md-12">

        </div>

    </div>

    <div class="modal fade " id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6>View Slider
                        <span id="viewDisplay">
                        </span>
                        <span id="viewFeatured">
                        </span>
                    </h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body pricing_page text-center pt-4">
                    <div class="card ">
                        <div class="card-header">
                            <h5 id="PageTitle"></h5>
                            <small class="text-muted" id="PageSubTitle"></small>
                        </div>
                        <div class="card-body">
                            <img id="ViewImage" class="img-fluid"
                            src="https://via.placeholder.com/1584x1058?text=Sample + Image + For + Slider">
                            <hr>
                            <div class="row">
                                <!-- <div class="col-md-6" id="">
                                    <b>Button Name : <span id="viewButtonName"></span></b>
                                </div> -->
                                <div class="col-md-6" id="">
                                    <b>Url  : <span id="viewButtonUrl"></span></b>
                                </div>
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

    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" 
    aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content bg-danger">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="exampleModalLabel">Delete Slider</h5>
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


@endsection
@section('script')
<script>
    function view(id, title, status, image, btnname, link) {
        subtitles = $("#view"+id).attr('data-subtitles');
        $('#viewId').val(id);
        $('#PageTitle').html(title);
        $('#PageSubTitle').html(subtitles);

        if (status == 0) {
            $('#viewDisplay').html('<small class="badge badge-danger">Not Displayed</small>');
        } else {
            $('#viewDisplay').html('<small class="badge badge-success">Displayed</small>');
        }


        $('#ViewImage').attr('src', "{{ asset('storage/slider/thumbs/slide_')}}" + image);
        // $('#viewButtonName').html(btnname);
        $('#viewButtonUrl').html(link);
    }

    function delete_menu(id) {
        var conn = './sliders/delete/' + id;
        $('#delete a').attr("href", conn);
    }

</script>

@endsection
