@extends('admin/layouts.header-sidebar')
@section('title', isset($product) ? $product->product_name : 'Products')
@section('content')
<script>
    $(document).ready(function () {
        var updateOutput = function (e) {
            var list = e.length ? e : $(e.target), output = list.data('output');

            $.ajax({
                method: "POST",
                url: "{{ URL::route('admin.products.order_products')}}",
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
                        swal({
                            title: 'Success!',
                            buttonsStyling: false,
                            confirmButtonClass: "btn btn-success",
                            html: '<b>Products</b> Sorted Successfully',
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
                    <small>
                        <b>{{ $item->product_name }}</b>&nbsp;
                        @if($item->display == 1)
                        <span class="badge badge-success mr-0 ml-0" style="font-size: 7px;">Displayed</span>
                        @else
                        <span class="badge badge-danger mr-0 ml-0" style="font-size: 7px;">Not Displayed</span>
                        @endif

                        @if($item->featured == 1)
                        <span class="badge badge-info mr-0 ml-0" style="font-size: 7px;">Featured</span>
                        @else
                        <span class="badge badge-danger mr-0 ml-0" style="font-size: 7px;">Not Featured</span>
                        @endif

                    </small>
                    <span class="content-right">
                        
                        <a href="#viewModal"
                        class="btn btn-sm btn-outline-success" data-toggle="modal"
                        data-id="{{ $item->id }}"
                        id="view{{ $item->id }}"
                        data-short_content = '{{ addslashes($item->short_content) }}'
                        onclick="view('{{ $item->id }}','{{ $item->product_name }}','{{ $item->slug }}', '{{ $item->display }}','{{ $item->image }}','{{ $item->featured }}')"
                        title="View"><i class="fa fa-eye"></i></a>

                        <a href="{{ url('admin/products/edit/'.base64_encode($item->id)) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fa fa-edit"></i></a>

                        <a href="#delete"
                        data-toggle="modal"
                        data-id="{{ $item->id }}"
                        id="delete{{ $item->id }}"
                        class="btn btn-sm btn-outline-danger center-block"
                        onClick="delete_menu('{{ base64_encode($item->id) }}')"><i class="fa fa-trash  "></i></a>
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

function displayCategories($list, $selectedCategory){
    foreach ($list as $item){

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

function showFilterCategory($categories_list){

    foreach ($categories_list as $fItem){

        if ($fItem->parent_id != 0) {
            $parent = DB::table('categories')->where('id',$fItem->parent_id)->first();
            $fItem->title = $parent->title.' → '.$fItem->title;
        }
        ?>
        <a class="dropdown-item <?=$fItem->child == 1 ? 'disabled' : '' ?>" href="{{ route('admin.products.list',['catId' => $fItem->id]) }}">{{ $fItem->title }}</a>

        <?php 
        if (array_key_exists("children", $fItem)){
            showFilterCategory( $fItem->children);
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
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard')  }}"><i class="icon-speedometer"></i> Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.products.list') }}"><i class="icon-layers"></i> Products</a></li>
                        @if($id != 0)
                        <li class="breadcrumb-item active" aria-current="page">{{ isset($product) ? $product->product_name : 'Products' }}</li>
                        @endif
                    </ol>
                </nav>
            </div>
            <div class="col-md-4 col-sm-12 text-right hidden-xs">
                <a href="{{ $id != 0 ? url('admin/products') : url('admin') }}" class="btn btn-outline-primary btn-round"><i class="fa fa-angle-double-left"></i> Go Back</a>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12">

            <ul class="nav nav-tabs">
                @if($id == 0)
                <li class="nav-item">
                    <a class="nav-link show active" data-toggle="tab" href="#Pages">{{ isset($product) ? $product->product_name : 'Products' }}</a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link {{ $id != 0 ? 'show active' : '' }}" data-toggle="tab" href="#addPage">{{ $id == 0 ? 'Add Product' : 'Update Product' }}</a>
                </li>
            </ul>
            <div class="tab-content mt-0">
                @if($id == 0)
                <div class="tab-pane show active" id="Pages">
                    <div class="card">
                        <div class="header card-header">
                            <h6 class="title mb-0">All {{ isset($product) ? $product->product_name : 'Products' }}</h6>

                        </div>
                        <div class="body mt-0">
                           <div class="row">
                               <div class="col-md-4 offset-md-8">
                                   <div style="width: 100%" class="btn-group mb-3" role="group">
                                       <button id="btnGroupDrop1" type="button" class="btn btn-secondary text-right dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                           Filter Products By Categories
                                       </button>
                                       <div style="width: 100%; height: 300px; overflow-y: scroll;" class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                           <a class="dropdown-item" href="{{ route('admin.products.list') }}">View All</a>
                                           {{ showFilterCategory($categories) }}
                                       </div>
                                   </div>
                               </div>
                           </div>

                            @if(isset($products) && $products->count() > 0)
                                <div class="dd nestable-with-handle" id="nestable">
                                    <?php isset($products) ? displayList($products) : '' ?>
                                </div>
                            @else
                                <div class="alert alert-danger">
                                    Sorry, No Products Found.
                                </div>
                            @endif

                            {{ isset($products) ? $products->links() : '' }}
                        </div>
                    </div>

                </div>
                @endif
                <div class="tab-pane {{ $id != 0 ? 'show active' : '' }}" id="addPage">
                    <div class="card">
                        <div class="header card-header">
                            <h6 class="title mb-0">{{ isset($product) ? $product->product_name : 'Add Products' }}</h6>
                        </div>
                        <div class="body mt-2">
                            <form id="parsley-form" method="post" action="{{ $id == 0 ? route('admin.products.create') : route('admin.products.update') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="productId" name="id" value="{{ $id != 0 ? $id : '' }}"/>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-text-width"></i> &nbsp;Product Name</span>
                                            </div>
                                            <input type="text" name="product_name" class="form-control" placeholder="Enter Product Name Here" required value="{{ $id != 0 ? $product->product_name : old('product_name') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <?php $display =  $id != 0 ? $product->display : old('display')  ?>
                                                    <input type="checkbox" name="display" value="1" <?=$display == 1 ? 'checked' : ($id == 0 ? 'checked' : '') ?>>
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
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <!-- <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-text-width"></i> &nbsp;Category</span>
                                            </div> -->
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
                                            <input type="text" name="brand" class="form-control" placeholder="Enter Brand Name Here" value="{{ $id != 0 ? $product->brand : old('brand') }}">
                                        </div>
                                    </div>

                                    <!-- <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-barcode"></i> &nbsp;SKU</span>
                                            </div>
                                            <input type="text" name="sku" class="form-control" placeholder="Enter Product SKU # Here" required value="{{ $id != 0 ? $product->sku : old('sku') }}">
                                        </div>
                                    </div> -->

                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-image"></i> &nbsp;Featured Image</span>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="image" {{ $id == 0 ? 'required' : '' }}>
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
                                                                    
                                                                    <input type="text" name="size[]" placeholder="Size" required class="form-control size_class">
                                                                    

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
                                                        <textarea class="ckeditor" name="short_content">{{ $id != 0 ? $product->short_content : old('short_content') }}</textarea>

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
                                                        <textarea class="ckeditor" name="long_content">{{ $id != 0 ? $product->long_content : old('long_content') }}</textarea>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="clearfix"></div>

                                    <div class="col-md-12">
                                        @if ($id != 0)
                                        <a href="{{ route('admin.products.list') }}"
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

    <div class="modal fade modal-info" id="deleteVariaton">
        <div class="modal-dialog " role="document">
            <div class="modal-content bg-info">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="exampleModalLabel">Delete Product Variation?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-white">
                    <p>Are you Sure...??</p>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-round btn-default" data-dismiss="modal">Close</button>
                    <a href="" class="btn btn-round btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade " id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6>View Product
                        <span id="viewDisplay"></span>
                        <span id="viewFeatured"></span>
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

    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content bg-danger">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="exampleModalLabel">Delete Product.</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-white">
                    <p>Are you Sure?!</p>
                    <span>All the images and associated details will be deleted.</span>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-round btn-default" data-dismiss="modal">Close</button>
                    <a href="" class="btn btn-round btn-primary">Delete</a>
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

</div>


@endsection
@section('script')
<script>
    // $('.pack_class').keyup(function(){

    // });
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

    function view(id, title, slug, status, image, featured) {
        short_content = $("#view"+id).attr('data-short_content');
        $('#viewId').val(id);
        $('#PageTitle').html(title);
        $('#viewContent').html(short_content);

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

        $('#viewImage').attr('src', "{{ asset('storage/products/')}}/"+slug+"/thumbs/thumb_"+ image);
    }

    function delete_menu(id) {
        var conn = './products/delete/' + id;
        $('#delete a').attr("href", conn);
    }

    $(function () {
        $(".category-select").select2({
            allowClear: true,
            width: "100%"
        });

    });

</script>

@if($id != 0)
<script>
    function delete_image(image) {

        var conn = '../delete-image/<?=$product->slug;?>/' + image;
        $('#delete_image a').attr("href", conn);
    }

</script>
@endif

<script type="text/javascript">
    $(document).ready(function(){

        $("#product_variations_field").lightGallery({
            selector: '.light-link'
        }); 

        var i=1;
        if ($("#productId").val() != '') {
            i = $('#productVariationCount').val();
        }


        $('#addProductVariations').click(function(){  
            i++;

            $.ajax({
                url : "{{ url('admin/products/add-variations') }}/"+i,
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


    function delete_variation(id) {
        var conn = "{{ url('admin/products/variations/delete') }}/"+id;

        $('#deleteVariaton a').attr("href", conn);
    }

    // $('.sub_variations_multiselect').multiselect();
</script>

@endsection
