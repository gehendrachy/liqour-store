@extends('admin/layouts.header-sidebar')
@section('title', ' Add New Product')
@section('content')
<?php

function displayCategories($list, $selectedCategory){
    foreach ($list as $item){

        if ($item->parent_id != 0) {
            $parent = DB::table('categories')->where('id',$item->parent_id)->first();
            $item->title = $parent->title.' → '.$item->title;
        }
        ?>
        <option 
            <?=$item->child == 1 ? 'disabled' : '' ?> 
            @php 
                if ($selectedCategory != 0 && $item->id == $selectedCategory) {
                    echo "selected";
                }
            @endphp 
            value="{{$item->id}}">
                {{ $item->title}}
        </option>
        <?php if (array_key_exists("children", $item)){
            displayCategories( $item->children, $selectedCategory);
        }
    }
}
?>

<div class="container-fluid">
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-md-8 col-sm-12">

                <h2>Add New Products</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard', ['username' => $username])  }}"><i class="icon-speedometer"></i> Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('vendor.add-products', ['username' => $username]) }}"><i class="icon-layers"></i> Add New Product</a></li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-4 col-sm-12 text-right hidden-xs">
                <a href="{{ route('vendor.inventory-products.list', ['username' => $username]) }}" class="btn btn-outline-primary btn-round"><i class="fa fa-angle-double-left"></i> Go Back To Inventory Products</a>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12">

            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link show active" data-toggle="tab" href="#addPage"> Add New Product</a>
                </li>
            </ul>
            <div class="tab-content mt-0">
                <div class="tab-pane show active" id="addPage">
                    <div class="card">
                        <div class="header card-header">
                            <h6 class="title mb-0"> 
                                Add New Product
                                <small class="pull-right" style="font-size: 10px;">Enter Necessary Details About Product. (It will be displayed once the Administrator verifies it).</small>
                            </h6>
                        </div>
                        <div class="body mt-2">
                            <form method="post" action="{{ route('vendor.add-products.store', ['username' => $username]) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-text-width"></i> &nbsp;Product Name</span>
                                            </div>
                                            <input type="text" name="product_name" class="form-control" placeholder="Enter Product Name Here" required value="{{ old('product_name') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <select name="category_id" class="custom-select category-select" required>
                                                <option selected disabled>Choose Product Category</option>
                                                @php
                                                    $selectedCategory = ($id != 0 ? $product->category_id : old('category_id'));

                                                @endphp
                                                {{ displayCategories($categories, $selectedCategory) }}
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-anchor"></i> &nbsp;Brand</span>
                                            </div>
                                            <input type="text" name="brand" class="form-control" placeholder="Enter Product Brand Name Here" required value="{{ old('brand') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-image"></i> &nbsp;Featured Image</span>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="image" required>
                                                    <label class="custom-file-label"><i class="fa fa-image"></i> Choose file</label>
                                                </div>  
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header" style="background: #d2d6de;">
                                            <p class="title mb-0">
                                                
                                                Select <b>Available Product Variations</b> & <b>Upload Image</b> for the respective product.
                                                <small class="pull-right">Recommended Size: 800px X 1200px for best fit.</small>
                                            </p>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">

                                                <div class="col-md-12">
                                                    <div class="card-body border-info" id="product_variations_field">  
                                                        @if(isset($product) && $product->product_variations->count() > 0)

                                                            <input type="hidden" id="productVariationCount" value="{{$product->product_variations->count()}}">
                                                            @foreach($product->product_variations as $key => $prodVar)
                                                            <input type="hidden" name="product_variation_id[]" value="{{ $prodVar->id }}">
                                                            <div class="card-body pb-0 mb-1">
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <small class="text-muted">
                                                                            <i class="fa fa-anchor"></i> &nbsp; Pack*
                                                                        </small>
                                                                        <div class="input-group mb-3">
                                                                            <input type="text" name="pack_db[]" placeholder="Pack" class="form-control pack_class" onkeypress="return isNumberKey(event)" value="{{ $prodVar->pack }}">
                                                                        </div>

                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <small class="text-muted">
                                                                            <i class="fa fa-anchor"></i> &nbsp; Size*
                                                                        </small>
                                                                        <div class="input-group mb-3">
                                                                            <input type="text" name="size_db[]" placeholder="Size" required class="form-control size_class" value="{{ $prodVar->size }}">
                                                                        </div>

                                                                    </div>

                                                                    <div class="col-md-2">
                                                                        <small class="text-muted">
                                                                            <i class="fa fa-anchor"></i> &nbsp; Container*
                                                                        </small>
                                                                        <div class="mb-3">
                                                                            <select name="container_db[]" class="form-control" required>
                                                                                
                                                                                <option <?=$prodVar->container == 'Bottle' ? 'selected' : '' ?> value="Bottle">Bottle</option>

                                                                                <option <?=$prodVar->container == 'Plastic Bottle' ? 'selected' : '' ?> value="Plastic Bottle">Plastic Bottle</option>

                                                                                <option <?=$prodVar->container == 'Can' ? 'selected' : '' ?> value="Can">Can</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4 col-sm-12">
                                                                        <small class="text-muted">
                                                                            <i class="fa fa-anchor"></i> &nbsp; Upload Image*
                                                                        </small>
                                                                        <div class="input-group mb-3">
                                                                            <div class="custom-file">
                                                                                <input type="file" class="custom-file-input" name="variation_image_db[{{$prodVar->id}}]">
                                                                                <label class="custom-file-label"><i class="fa fa-image"></i> Choose file</label>
                                                                            </div>
                                                                            
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-1 col-sm-12">
                                                                        <a class="light-link" href="{{ asset('storage/products/'.$product->slug.'/thumbs/large_'.$prodVar->image) }}" data-sub-html="{{ $product->product_name.' - '. $prodVar->pack.'x - '.$prodVar->size.' '.$prodVar->container }} ">

                                                                            <img class="img img-thumbnail" width="40" src="{{ asset('storage/products/'.$product->slug.'/thumbs/thumb_'.$prodVar->image) }}">
                                                                        </a>
                                                                    </div>
                                                                    @if($product->product_variations->count() > 1)
                                                                    <div class="col-md-1 col-sm-12 text-right">
                                                                        <a href="#deleteVariaton"
                                                                           data-toggle="modal"
                                                                           data-id="{{ $prodVar->id }}"
                                                                           id="delete_variation{{ $prodVar->id }}"
                                                                           class="btn btn-sm btn-danger delete mt-3"
                                                                           onclick="delete_variation('{{ base64_encode($prodVar->id) }}')">
                                                                           <i class=" fa fa-trash"></i>
                                                                        </a>
                                                                    </div>  
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        @else
                                                        <input type="hidden" id="productVariationCount" value="0">
                                                        <div class="card-body pb-0 mb-1">
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <small class="text-muted">
                                                                        <i class="fa fa-anchor"></i> &nbsp; Pack*
                                                                    </small>
                                                                    <div class="input-group mb-3">
                                                                        <input type="text" name="pack[]" placeholder="Pack"  class="form-control pack_class" onkeypress="return isNumberKey(event)">
                                                                    </div>

                                                                </div>
                                                                <div class="col-md-2">
                                                                    <small class="text-muted">
                                                                        <i class="fa fa-anchor"></i> &nbsp; Size*
                                                                    </small>
                                                                    <div class="input-group mb-3">
                                                                        <input type="text" name="size[]" placeholder="Size" required class="form-control size_class">
                                                                    </div>

                                                                </div>

                                                                <div class="col-md-2">
                                                                    <small class="text-muted">
                                                                        <i class="fa fa-anchor"></i> &nbsp; Container*
                                                                    </small>
                                                                    <div class="mb-3">
                                                                        <select name="container[]" class="form-control" required>
                                                                            <option value="Bottle">Bottle</option>
                                                                            <option value="Plastic Bottle">Plastic Bottle</option>
                                                                            <option value="Can">Can</option>
                                                                        </select>
                                                                    </div>
                                                                    
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <small class="text-muted">
                                                                        <i class="fa fa-anchor"></i> &nbsp; Upload Image*
                                                                    </small>
                                                                    <div class="input-group mb-3">
                                                                        <div class="custom-file">
                                                                            <input type="file" class="custom-file-input" name="variation_image[]" required>
                                                                            <label class="custom-file-label"><i class="fa fa-image"></i> Choose file</label>
                                                                        </div>  
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif

                                                    </div>
                                                    <div class="card-footer text-center">
                                                        <button type="button" class="btn btn-outline-primary" id="addProductVariations">Add Another Variation Field</button>
                                                    </div>
                                                </div> 
                                            </div>    
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header" style="background: #d2d6de;">
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
                                                        <textarea class="ckeditor" name="short_content">{{ old('short_content') }}</textarea>

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
                                                        <textarea class="ckeditor" name="long_content">{{ old('long_content') }}</textarea>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="clearfix"></div>

                                    <div class="col-md-12">
                                        
                                        <a href="{{ route('vendor.dashboard', ['username' => $username]) }}"
                                        class="btn btn-outline-danger">CANCEL</a>

                                        <button type="submit" style="float: right;" class="btn btn-outline-success"> save</button>
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
<script type="text/javascript">
    

    function isNumberKey(evt){ 
        var charCode = (evt.which) ? evt.which : event.keyCode 
        if (charCode > 31 && (charCode < 48 || charCode > 57)) 
            return false; 
        return true; 
    }

    
    $('.size_class').bind('input', function(){
        $(this).val(function(_, v){

            return v.replace(/[^a-zA-Z0-9]/g, '').toLowerCase();
        });
    });

    $(function () {
        $(".category-select").select2({
            allowClear: true,
            width: "100%"
        });

    });

    $(document).ready(function(){
        var i=1;
        $('#addProductVariations').click(function(){  
            i++;

            $.ajax({
                url : "{{ url('vendor/'.$username.'/add-products/add-variations') }}/"+i,
                cache : false,
                beforeSend : function (){

                },
                complete : function($response, $status){
                    if ($status != "error" && $status != "timeout") {
                        $('#product_variations_field').append($response.responseText);
                        
                        $('.size_class').bind('input', function(){
                            $(this).val(function(_, v){

                                return v.replace(/[^a-zA-Z0-9]/g, '').toLowerCase();
                            });
                        });
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
            $('#variation'+button_id).remove();  
        });

    });

</script>

@endsection
