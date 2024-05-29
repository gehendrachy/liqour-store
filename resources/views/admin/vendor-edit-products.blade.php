@extends('admin/layouts.header-sidebar')
@section('title', isset($product) ? $product->title : 'Products')
@section('content')
<?php
    function displayCategories($list, $selectedCategories){
        foreach ($list as $item){
            if ($item->parent_id != 0) {
                $parent = DB::table('categories')->where('id',$item->parent_id)->first();
                $item->title = $parent->title.' → '.$item->title;
            }
            ?>
            <option
                <?=$item->child == 1 ? 'disabled' : '' ?>
                @php
                    if ($selectedCategories != 0 && in_array($item->id, $selectedCategories)) {
                        echo "selected";
                    }
                @endphp
                value="{{$item->id}}">
                    {{ $item->title}}
            </option>
            <?php if (array_key_exists("children", $item)){
                displayCategories( $item->children, $selectedCategories);
            }
        }
    }
?>

<div class="container-fluid">
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-md-8 col-sm-12">

                <h2>Products</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard',['username' => $username])  }}"><i class="icon-speedometer"></i> Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('vendor.products.list',['username' => $username]) }}"><i class="icon-layers"></i> Products</a></li>
                        @if($id != 0)
                        <li class="breadcrumb-item active" aria-current="page">{{ isset($product) ? $product->title : 'Products' }}</li>
                        @endif
                    </ol>
                </nav>
            </div>
            <div class="col-md-4 col-sm-12 text-right hidden-xs">
                <a href="{{ route('vendor.dashboard',['username' => $username]) }}" class="btn btn-outline-primary btn-round"><i class="fa fa-angle-double-left"></i> Go Back</a>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12">

            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link {{ $id != 0 ? 'show active' : '' }}" data-toggle="tab" href="#addPage">{{ $id == 0 ? 'Add Product' : 'Update Product' }}</a>
                </li>
            </ul>
            <div class="tab-content mt-0">
                <div class="tab-pane {{ $id != 0 ? 'show active' : '' }}" id="addPage">
                    <div class="card">
                        <div class="header card-header">
                            <h6 class="title mb-0">{{ isset($product) ? $product->title : 'Add Products' }}</h6>
                        </div>
                        <div class="body mt-2">
                            <form method="post" action="{{ $id == 0 ? route('vendor.products.create',['username' => $username]) : route('vendor.products.update',['username' => $username]) }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="productId" name="id" value="{{ $id != 0 ? $id : '' }}"/>
                                <input type="hidden" name="username" value="{{ $username }}">
                                <input type="hidden" name="vendor_id" value="{{ session()->get('vendorID') }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-text-width"></i> &nbsp;Title</span>
                                            </div>
                                            <input type="text" name="title" class="form-control" placeholder="Enter Product Title Here" required value="{{ $id != 0 ? $product->title : old('title') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <?php $display =  $id != 0 ? $product->display : old('display')  ?>
                                                    <input type="checkbox" name="display" value="1" <?=$display == 1 ? 'checked' : '' ?>>
                                                </div>
                                            </div>
                                            <input type="button " class="form-control bg-indigo text-muted"
                                            value="Display" disabled>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <?php $featured =  $id != 0 ? $product->featured : old('featured')  ?>
                                                    <input type="checkbox" name="featured" value="1" <?=$featured == 1 ? 'checked' : '' ?>>
                                                </div>
                                            </div>
                                            <input type="button " class="form-control bg-indigo text-muted"
                                            value="Featured" disabled>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <?php $stockStatus =  $id != 0 ? $product->stockStatus : old('stockStatus')  ?>
                                                    <input type="checkbox" name="stockStatus" value="1" <?=$stockStatus == 1 ? 'checked' : '' ?>>
                                                </div>
                                            </div>
                                            <input type="button " class="form-control bg-indigo text-muted"
                                            value="Check if Available" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <select name="categories[]" class="form-control select2" multiple="multiple" data-placeholder="Select Product Categories" style="width: 100%;" required>
                                                {{ displayCategories($categories, $selectedCategories) }}
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-barcode"></i> &nbsp;SKU</span>
                                            </div>
                                            <input type="text" name="sku" class="form-control" placeholder="Enter Product SKU # Here" required value="{{ $id != 0 ? $product->sku : old('sku') }}">
                                        </div>
                                    </div>

                                    <div class="card text-white bg-secondary">
                                        <div class="card-header" >
                                            <p class="title mb-0">Enter <b>Prices, Colors, Sizes </b> &amp; <b> Stock Quantities</b> for the product.</p>
                                        </div>
                                        <div class="card-body">
                                            <!-- <div class="row">
                                                <div class="col-md-4">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fa fa-dollar"></i> &nbsp;Original Price</span>
                                                        </div>
                                                        <input type="text" name="originalPrice" class="form-control" placeholder="Product's Original Price" required value="{{ $id != 0 ? $product->originalPrice : old('originalPrice') }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fa fa-dollar"></i> &nbsp;Discounted Price</span>
                                                        </div>
                                                        <input type="text" name="discountedPrice" class="form-control" placeholder="Product's Discounted Price" required value="{{ $id != 0 ? $product->discountedPrice : old('discountedPrice') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                Product Type
                                                            </div>
                                                        </div>
                                                        <input type="hidden" id="productVariations" value="{{ $product->variation_type >= 1 ? json_encode($productVariations) : $product->stockQty}}">
                                                        @if($product->variation_type >= 1)
                                                        <input type="hidden" id="childVariationCount" value="{{ $variationCount }}">
                                                        <input type="hidden" id="parentVariationCount" value="{{ count($productVariations) }}">
                                                        @endif
                                                        <select id="variationType" class="form-control" name="variation_type">
                                                            <option data-flag="<?=$product->variation_type == 0 ? 1 : 0 ?>" value="0" <?=$product->variation_type == 0 ? 'selected' : '' ?>>Has None(Stock Only)</option>
                                                            <option data-flag="<?=$product->variation_type == 1 ? 1 : 0 ?>" value="1" <?=$product->variation_type == 1 ? 'selected' : '' ?>>Has Single Variation Only</option>
                                                            <option data-flag="<?=$product->variation_type == 2 ? 1 : 0 ?>" value="2" <?=$product->variation_type == 2 ? 'selected' : '' ?>>Has 2 Dimensional Variations</option>
                                                        </select>

                                                    </div>
                                                </div>
                                            </div>    -->

                                            <input type="hidden" data-flag="1" name="variation_type" id="variationType" value="1">

                                            <input type="hidden" id="productVariations" value="{{ $product->variation_type >= 1 ? json_encode($productVariations) : $product->stockQty}}">

                                            <input type="hidden" id="childVariationCount" value="{{ $variationCount }}">

                                            <input type="hidden" id="parentVariationCount" value="{{ count($productVariations) }}">

                                            <hr>
                                            <div class="row ">
                                                <div class="col-lg-12 colorSizeContent">
                                                    <div class="row" >

                                                        <div id="dynamic_field" class="col-md-12">
                                                            <!-- Dynamic price field Here -->
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-2">

                                                            <button type="button" name="add" id="addVariation" class="btn btn-success">Add More</button>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card text-white bg-secondary">
                                        <div class="card-header">
                                            <p class="title mb-0">
                                                <strong>Add Images</strong>
                                                <small class="pull-right">
                                                    <i><b>Featured & Gallery Image resolution :</b> 900x1200px image recommended. </i>
                                                </small>
                                            </p>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <i class="fa fa-image"></i> &nbsp; Featured Image
                                                        </div>
                                                        <div class="alert alert-success border-success">
                                                            <input type="file" name="image" class="dropify bg-primary form-control" <?=$id == 0 ? 'required' : '' ?>>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-8">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <i class="fa fa-image"></i> &nbsp; Gallery Images
                                                        </div>
                                                        <div class="alert alert-info border-info">
                                                            <input type="file" class="dropify bg-info form-control" name="other_images[]" multiple>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- <div class="col-md-4"></div> -->
                                                @if($id != 0)
                                                <div class="col-md-4">

                                                    <div class="alert alert-success">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">
                                                                    <i class="fa fa-image"></i>
                                                                    &nbsp;Current <br> Featured <br>Image
                                                                </span>
                                                            </div>
                                                            <img width="50%" class="img-thumbnail" src="{{ asset('storage/products/'.$product->slug.'/thumbs/thumb_'.$product->image) }}">
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="col-md-8">

                                                    <div class="alert alert-info">
                                                        <div class="input-group">

                                                            <?php
                                                                $images = Storage::files('public/products/'.$product->slug.'/');
                                                            ?>
                                                            @for ($i = 0; $i < count($images); $i++)
                                                                @if(strpos($images[$i], $product->image) != true)

                                                                    <a href="#delete_image" data-toggle="modal"
                                                                       data-photo=""
                                                                       onclick="delete_image('<?= basename($images[$i]); ?>')"
                                                                       id="" title="Delete Image">
                                                                        <i style="position: absolute; top: -9px;padding: 4px; color: #fff;border-radius: 50%; opacity: 1;" class="btn-danger close fa fa-trash"></i>
                                                                    </a>
                                                                    <img class="img-thumbnail" src="{{ asset('storage/').str_replace('public/products/'.$product->slug.'/','/products/'.$product->slug.'/thumbs/thumb_',$images[$i])}}" alt="no-image" style="max-width: 100px; margin-right: 10px;">

                                                                @endif
                                                            @endfor
                                                        </div>
                                                    </div>

                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card text-white bg-secondary">
                                        <div class="card-header">
                                            <p class="title mb-0">
                                                <strong>Add Necessary Content about product</strong>
                                            </p>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-file-text-o"></i>
                                                                &nbsp;Short Content
                                                            </span>
                                                        </div>
                                                        <textarea class="ckeditor" name="summary">{{ $id != 0 ? $product->summary : old('summary') }}</textarea>

                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-file-text"></i>
                                                                &nbsp;Long Content
                                                            </span>
                                                        </div>
                                                        <textarea class="ckeditor" name="description">{{ $id != 0 ? $product->description : old('description') }}</textarea>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="clearfix"></div>

                                    <div class="col-md-12">
                                        @if ($id != 0)
                                        <a href="{{ route('vendor.products.list',['username' => $username]) }}"
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
                    <h6>View Product
                        <span id="viewDisplay"></span>
                        <span id="viewFeatured"></span>
                        <span id="viewStockStatus"></span>
                    </h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body pricing_page text-center pt-4 mb-4">
                        <div class="card ">
                            <div class="card-header">
                                <h5 id="PageTitle"></h5>
                                <small class="text-muted" id="viewContent"></small>
                            </div>
                            <div class="card-body">
                                <img id="viewImage" class="img-fluid"
                                src="https://via.placeholder.com/1584x1058?text=Sample + Image + For + Product">
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

    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content bg-danger">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="exampleModalLabel">Delete Product</h5>
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

<div class="modal fade modal-danger" id="delete_image">
    <div class="modal-dialog " role="document">
            <div class="modal-content bg-warning">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="exampleModalLabel">Delete Gallery Image</h5>
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

<div class="modal fade modal-danger" id="deleteVariation">
    <div class="modal-dialog " role="document">
            <div class="modal-content bg-default">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Variation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you Sure?</p>
                    <small id="warningMessage"></small>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-round btn-default" data-dismiss="modal">Close</button>
                    <a href="" class="btn btn-round btn-danger">Delete</a>
                </div>
            </div>
        </div>
</div>
@if($id != 0)
<script>
    function delete_image(image) {

        var conn = '../delete-image/<?=$product->slug;?>/' + image;
        $('#delete_image a').attr("href", conn);
    }

    function delete_variation(id, flag) {
        if (flag == 0) {
            $("#warningMessage").html('Once You Delete it, it cannot be recovered back!');
        }else{
            $("#warningMessage").html('Once You Delete it, it cannot be recovered back! <br> Deleting this variation, Its all child variations will also be deleted!');
        }
        var conn = '../delete-variation/' + id + '/flag/'+flag;
        $('#deleteVariation a').attr("href", conn);
    }

    // function delete_multiple_variation(id) {

    //     var conn = '../delete-variation/' + id;
    //     $('#deleteVariation a').attr("href", conn);
    // }

</script>
@endif


@endsection
@section('script')
<script>

    function view(id, title, slug, status, image, featured, stockStatus) {
        summary = $("#view"+id).attr('data-summary');
        $('#viewId').val(id);
        $('#PageTitle').html(title);
        $('#viewContent').html(summary);

        if (status == 0) {
            $('#viewDisplay').html('<small class="badge badge-danger">Not Displayed</small>');
        } else {
            $('#viewDisplay').html('<small class="badge badge-success">Displayed</small>');
        }

        if (featured == 0) {
            $('#viewFeatured').html('<small class="badge badge-warning">Not Featured</small>');
        } else {
            $('#viewFeatured').html('<small class="badge badge-info">Featured</small>');
        }

        if (status == 0) {
            $('#viewStockStatus').html('<small class="badge badge-danger">Out of Stock</small>');
        } else {
            $('#viewStockStatus').html('<small class="badge badge-warning">Available</small>');
        }

        $('#viewImage').attr('src', "{{ asset('storage/products/')}}/"+slug+"/thumbs/small_"+ image);
    }

    function delete_menu(id) {
        var conn = './products/delete/' + id;
        $('#delete a').attr("href", conn);
    }

    $(function () {
        $(".select2").select2({
            allowClear: true,
            placeholder: 'Select Category'
        });
    });

    $(document).ready(function(){
        var i=1;
        var j=0;

        if ($("#productId").val() != '') {
            i = $('#parentVariationCount').val();
            j = $('#childVariationCount').val();
        }

        $('#addVariation').click(function(){
            // alert($("#variationType").val());
            // return;
            i++;

            $.ajax({
                url : "{{ URL::route('vendor.products.add_extra_variation_fields', ['username' => $username]) }}",
                type : "POST",
                data :{ '_token': '{{ csrf_token() }}',
                        variation: $("#variationType").val(),
                        cIndex: i,
                    },
                cache : false,
                beforeSend : function (){

                },
                complete : function($response, $status){
                    if ($status != "error" && $status != "timeout") {
                        if ($("#variationType").val() == 1) {

                            $("#singleVariation").append($response.responseText);

                        }else if($("#variationType").val() == 2){

                            $('#dynamic_field').append($response.responseText);
                            $('.colorpicker').colorpicker();
                        }
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

        $(document).on('click', '.btn_remove_child', function(){
            var button_id = $(this).attr("id");
            $('#child'+button_id).remove();
        });



        $(document).on('click', '.btn_add_multiple_child_variation', function(){
            j++;

            var button_id = $(this).attr("id");

            $.ajax({
                url : "{{ URL::route('vendor.products.get_multiple_child_variation', ['username' => $username]) }}",
                type : "POST",
                data :{ '_token': '{{ csrf_token() }}',
                        pIndex: button_id,
                        cIndex: j,
                    },
                beforeSend : function (){

                },
                complete : function($response, $status){

                    if ($status != "error" && $status != "timeout") {
                        $('#multipleVariation'+button_id).append($response.responseText);
                    }
                },
                error : function ($responseObj){
                    alert("Something went wrong while processing your request.\n\nError => "
                        + $responseObj.responseText);
                }
            });
        });
    });

    get_variation_price_fields($("#variationType").val(), $("#variationType").data('flag'));

    if ($("#variationType").val() >= 1) {
        $(".colorSizeContent").show();
        $("#addVariation").show();
    }else{
        $("#addVariation").hide();
    }

    $("#variationType").change(function(){

        get_variation_price_fields(this.value, $(this).data('flag'));

        if (this.value >= 1) {
            $("#addVariation").show();
        }else{
            $("#addVariation").hide();
        }
    });

    function get_variation_price_fields(variationType, flag){

        $.ajax({
            url : "{{ URL::route('vendor.products.get_variation_price_fields', ['username' => $username]) }}",
            type : "POST",
            data :{ '_token': '{{ csrf_token() }}',
                    variation: variationType,
                    dbVariations: $("#productVariations").val(),
                    flag: flag,
                },
            beforeSend : function (){
                $(".colorSizeContent").slideUp();
                $('#dynamic_field').empty();
            },
            complete : function($response, $status){

                if ($status != "error" && $status != "timeout") {
                    $(".colorSizeContent").slideDown(500);
                    $('#dynamic_field').append($response.responseText);
                    $('.colorpicker').colorpicker();
                }
            },
            error : function ($responseObj){
                alert("Something went wrong while processing your request.\n\nError => "
                    + $responseObj.responseText);
            }
        });
    }

</script>

@endsection
