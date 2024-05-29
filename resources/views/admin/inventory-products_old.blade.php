@extends('admin/layouts.header-sidebar')
@section('title', isset($inventory_product) ? $inventory_product->product->product_name : 'Inventory Products')
@section('content')
<script>
    $(document).ready(function () {
        var updateOutput = function (e) {
            var list = e.length ? e : $(e.target), output = list.data('output');

            $.ajax({
                method: "POST",
                url: "{{ URL::route('vendor.inventory-products.order_products',['username' => $username])}}",
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
                            html: '<b>Inventory Products</b> Sorted Successfully',
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
                        <b>{{ $item->product->product_name }} | {{ @$item->product_variation->pack }} - {{ @$item->product_variation->size }} </b>&nbsp;
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
                        data-id="{{ $item->id }} "
                        id="view{{ $item->id }}"
                        data-short_content = '{{ addslashes($item->short_content) }}'
                        onclick="view('{{ $item->id }}','{{ $item->product_name }}','{{ $item->slug }}', '{{ $item->display }}','{{ $item->image }}','{{ $item->featured }}')"
                        title="View"><i class="fa fa-eye"></i></a>
                        <a href="{{ url('vendor/'.$username.'/inventory-products/edit/'.base64_encode($prod->id)) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fa fa-edit"></i></a>
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
?>

<div class="container-fluid">
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-md-8 col-sm-12">

                <h2>Inventory Products</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('vendor.dashboard',['username' => $username])  }}"><i class="icon-speedometer"></i> Dashboard</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('vendor.inventory-products.list',['username' => $username]) }}"><i class="icon-layers"></i> Inventory Products</a>
                        </li>

                        @if($id != 0)
                        <li class="breadcrumb-item active" aria-current="page">{{ isset($inventory_product) ? $inventory_product->product->product_name : 'Inventory Products' }}</li>
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
                    <a class="nav-link show active" data-toggle="tab" href="#Pages">{{ isset($inventory_product) ? $inventory_product->product->product_name : 'Inventory Products' }}</a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link {{ $id != 0 ? 'show active' : '' }}" data-toggle="tab" href="#addPage">{{ $id == 0 ? 'Add Product' : 'Update Product' }}</a>
                </li>
            </ul>
            <div class="tab-content mt-0">
                @if($id == 0)
                <div class="tab-pane show active" id="Pages">
                    <div class="">
                        <div class="header card-header">
                            <h6 class="title mb-0">All {{ isset($inventory_product) ? $inventory_product->product->product_name : 'Inventory Products' }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-custom spacing5">
                                            <tbody id="product_variations_field">
                                                @foreach($inventory_products as $key => $inv_products)
                                                <tr>
                                                    <th>
                                                        <a target="_blank" href="{{ route('product_details',['slug' => $inv_products[0]->product->slug]) }}" title="{{ $inv_products[0]->product->product_name }}">
                                                            {{ $inv_products[0]->product->product_name }}
                                                        </a>
                                                        @if($inv_products[0]->sku != '')
                                                            <small>( {{ $inv_products[0]->sku }} )</small>
                                                        @endif
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table class="table table-responsive">
                                                            <thead>
                                                                <tr>
                                                                    <th>SN.</th>
                                                                    <th>Product Variation</th>
                                                                    <th>Cost Price</th>
                                                                    <th>Retail Price</th>
                                                                    <th>Image</th>
                                                                    <th>SKU</th>
                                                                    <th>Barcode</th>
                                                                    <th>Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($inv_products as $key => $inv_prod)
                                                            <tr>
                                                                <td>
                                                                    {{ $key+1 }}
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        if ($inv_prod->product_variation->pack != 1) {
                                                                            $variationName = $inv_prod->product_variation->pack.'x - '.$inv_prod->product_variation->size.' '.$inv_prod->product_variation->container.'s';
                                                                        }else{
                                                                            $variationName = $inv_prod->product_variation->size.' '.$inv_prod->product_variation->container;
                                                                        }
                                                                    @endphp
                                                                    <strong>{{ $variationName }}</strong>
                                                                </td>
                                                                
                                                                <td class="w350" style="white-space: normal;">
                                                                    ${{ $inv_prod->cost_price }}
                                                                </td>
                                                                <td class="w350" style="white-space: normal;">
                                                                    ${{ $inv_prod->retail_price }}
                                                                </td>
                                                                <td>
                                                                    <a class="light-link" href="{{ asset('storage/products/'.$inv_products[0]->product->slug.'/thumbs/large_'.$inv_prod->product_variation->image) }}" data-sub-html="{{ $inv_products[0]->product->product_name.' - '. $variationName }} ">

                                                                        <img class="img img-thumbnail" width="40" src="{{ asset('storage/products/'.$inv_products[0]->product->slug.'/thumbs/thumb_'.$inv_prod->product_variation->image) }}">
                                                                    </a>
                                                                </td>
                                                                <td class="w350" style="white-space: normal;">
                                                                    {{ $inv_prod->barcode }}
                                                                </td>
                                                                <td class="w350" style="white-space: normal;">
                                                                    {{ $inv_prod->stock }}
                                                                </td>
                                                                <td>
                                                                    <a href="#viewModal"
                                                                    class="btn btn-sm btn-outline-success" data-toggle="modal"
                                                                    data-id="{{ $inv_prod->id }} "
                                                                    id="view{{ $inv_prod->id }}"
                                                                    data-short_content = '{{ addslashes($inv_prod->short_content) }}'
                                                                    onclick="view('{{ $inv_prod->id }}','{{ $inv_prod->product_name }}','{{ $inv_prod->slug }}', '{{ $inv_prod->display }}','{{ $inv_prod->image }}','{{ $inv_prod->featured }}')"
                                                                    title="View"><i class="fa fa-eye"></i></a>
                                                                    
                                                                    <a href="{{ url('vendor/'.$username.'/inventory-products/edit/'.base64_encode($inv_prod->id)) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fa fa-edit"></i></a>
                                                                    
                                                                    <a href="#delete"
                                                                    data-toggle="modal"
                                                                    data-id="{{ $inv_prod->id }}"
                                                                    id="delete{{ $inv_prod->id }}"
                                                                    class="btn btn-sm btn-outline-danger center-block"
                                                                    onClick="delete_inventory_product('{{ base64_encode($inv_prod->id) }}')"><i class="fa fa-trash  "></i></a>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>

                </div>
                @endif
                <div class="tab-pane {{ $id != 0 ? 'show active' : '' }}" id="addPage">
                    <div class="card">
                        <div class="header card-header">
                            <h6 class="title mb-0">{{ isset($inventory_product) ? $inventory_product->product->product_name : 'Add Inventory Products' }}</h6>
                        </div>
                        <div class="body mt-2">
                            <form method="post" action="{{ $id == 0 ? route('vendor.inventory-products.create',['username' => $username]) : route('vendor.inventory-products.update',['username' => $username]) }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="inventoryProductId" name="id" value="{{ $id != 0 ? base64_encode($id) : '' }}"/>
                                <input type="hidden" name="username" value="{{ base64_encode($username) }}">
                                <input type="hidden" name="user_id" value="{{ base64_encode(session()->get('vendorID')) }}">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group mb-3">
                                                <!-- <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fa fa-text-width"></i> &nbsp;Select Product</span>
                                                </div> -->
                                                <select name="product_id" class="custom-select" required id="selectProduct">
                                                    <option selected disabled>Choose Product...</option>
                                                    @foreach($products as $key => $prod)
                                                        <option data-product-image="{{ $prod->image }}" {{ $id != 0 && $inventory_product->product_id == $prod->id ? 'selected' : (old('product_id') == $prod->id ? 'selected' : '') }} value="{{ $prod->id }}">{{ $prod->product_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <?php $display =  $id != 0 ? $inventory_product->display : old('display')  ?>
                                                        <input type="checkbox" name="display" value="1" <?=$display == 1 ? 'checked' : ($id == 0 ? 'checked' : '') ?>>
                                                    </div>
                                                </div>
                                                <input type="button " class="form-control bg-indigo text-muted"
                                                value="Display" disabled>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group mb-3">
                                                
                                                <div id="selectProductVariation" style="width: 590px;">
                                                    <select name="product_variations_id" class="custom-select variation-select2" required style="width: 100%">
                                                        <option selected disabled>Choose Product First...</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fa fa-database"></i> &nbsp;Stock Quantity</span>
                                                </div>
                                                <input type="text" name="stock" class="form-control" placeholder="Product's Stock Quantity" required value="{{ $id != 0 ? $inventory_product->stock : old('stock') }}">
                                            </div>
                                        </div>  

                                        <div class="col-md-4">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fa fa-barcode"></i> &nbsp;SKU</span>
                                                </div>
                                                <input type="text" name="sku" class="form-control" placeholder="Product's SKU" value="{{ $id != 0 ? $inventory_product->sku : old('sku') }}">
                                            </div>
                                        </div>  

                                        <div class="col-md-4">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fa fa-dollar"></i> &nbsp;Cost Price</span>
                                                </div>
                                                <input type="text" name="cost_price" class="form-control" placeholder="Product's Cost Price" required value="{{ $id != 0 ? $inventory_product->cost_price : old('cost_price') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fa fa-dollar"></i> &nbsp;Retail Price</span>
                                                </div>
                                                <input type="text" name="retail_price" class="form-control" placeholder="Product's Retail Price" required value="{{ $id != 0 ? $inventory_product->retail_price : old('retail_price') }}">
                                            </div>
                                        </div> 
                                        <div class="col-md-4">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fa fa-barcode"></i> &nbsp;Barcode</span>
                                                </div>
                                                <input type="text" name="barcode" class="form-control" placeholder="Product's Barcode" value="{{ $id != 0 ? $inventory_product->barcode : old('barcode') }}">
                                            </div>
                                        </div>  

                                        
                                        <div class="col-md-6 clearfix">
                                            <div class="card-header">
                                                Tax Type
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text" style="background-color: #e1e8ed">

                                                                    <input type="checkbox" name="tax_type" value="1" class="tax_type_checkbox" {{ $id != 0 && $inventory_product->tax_type == 1 ? 'checked' : (old('tax_type') == 1 ? 'checked' : '' )}}>

                                                                </div>
                                                            </div>
                                                            <span class="form-control disabled">Tax Type 1 </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-group mb-3">

                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text" style="background-color: #e1e8ed">

                                                                    <input type="checkbox" name="tax_type" value="2" class="tax_type_checkbox" {{ $id != 0 && $inventory_product->tax_type == 2 ? 'checked' : (old('tax_type') == 2 ? 'checked' : '' )}}>

                                                                </div>
                                                            </div>
                                                            <span class="form-control disabled">Tax Type 2 </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text" style="background-color: #e1e8ed">

                                                                    <input type="checkbox" name="tax_type" value="3" class="tax_type_checkbox" {{ $id != 0 && $inventory_product->tax_type == 3 ? 'checked' : (old('tax_type') == 3 ? 'checked' : '' )}}>

                                                                </div>
                                                            </div>
                                                            <span class="form-control disabled">Tax Type 3 </span>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    

                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-6">
                                            <div class="card-header">
                                                Bottle Deposit Type
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text" style="background-color: #e1e8ed">

                                                                    <input type="checkbox" name="bottle_deposit_type" value="1" class="bottle_deposit_type_checkbox" {{ $id != 0 && $inventory_product->bottle_deposit_type == 1 ? 'checked' : (old('bottle_deposit_type') == 1 ? 'checked' : '' )}}>

                                                                </div>
                                                            </div>
                                                            <span class="form-control disabled">Bottle Deposit Type 1 </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="input-group mb-3">

                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text" style="background-color: #e1e8ed">
                                                                    
                                                                    <input type="checkbox" name="bottle_deposit_type" value="2" class="bottle_deposit_type_checkbox" {{ $id != 0 && $inventory_product->bottle_deposit_type == 2 ? 'checked' : (old('bottle_deposit_type') == 2 ? 'checked' : '' )}}>

                                                                </div>
                                                            </div>
                                                            <span class="form-control disabled">Bottle Deposit Type 2 </span>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                </div>
                                            </div>

                                        </div>
                                        
                                        <div class="clearfix"></div>

                                        
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                    <div class="col-md-12">
                                        @if ($id != 0)
                                        <a href="{{ route('vendor.inventory-products.list',['username' => $username]) }}"
                                        class="btn btn-outline-danger">CANCEL</a>

                                        <button type="submit" style="float: right;" class="btn btn-outline-success"> UPDATE</button>
                                        @else
                                        <button type="submit" style="float: right;" class="btn btn-outline-success"> SAVE</button>
                                        @endif
                                    </div>
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

    $("#product_variations_field").lightGallery({
        selector: '.light-link'
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

    function delete_inventory_product(id) {
        var conn = './inventory-products/delete/' + id;
        $('#delete a').attr("href", conn);
    }

    $(document).ready(function() {
        $('#selectProduct').select2({
            width: '100%',
            placeholder: 'Select One Product',
            language: {
                noResults: function() {
                    return '<button id="no-results-btn" onclick="noResultsButtonClicked()">No Match Found, Add New Product??</button>';
                },
            },
            escapeMarkup: function(markup) {
                return markup;
            },
        });

    });

    function addVariation() {
        var win = window.open("{{ URL::route('vendor.add-variations', ['username' => $username]) }}", '_blank');
        win.focus();
        // window.location.replace("http://stackoverflow.com");
    }

    function noResultsButtonClicked() {
        var win = window.open("{{ URL::route('vendor.add-products', ['username' => $username]) }}", '_blank');
        win.focus();
        // window.location.replace("http://stackoverflow.com");
    }

</script>

<script type="text/javascript">
    $(document).ready(function(){
        var i=1;

        if ($("#inventoryProductId").val() != '') {
            var i = $('#productVariationCount').val();
        }
        
        var product_id = $("#selectProduct").val();
        var inventory_prod_id = $("#inventoryProductId").val();

        call_ajax_function(product_id, inventory_prod_id);

        $('#selectProduct').change(function(){  
            var product_id = $(this).val();
            var inventory_prod_id = $("#inventoryProductId").val();
            
            
            // console.log(product_id);
            call_ajax_function(product_id, inventory_prod_id);

        });  

        function call_ajax_function(product_id, inventory_prod_id) {
            $.ajax({
                url : "{{ URL::route('vendor.inventory-products.get_product_variations', ['username' => $username]) }}",
                type : "POST",
                data : {
                    '_token': '{{ csrf_token() }}',
                    product_id: product_id,
                    inventory_prod_id: inventory_prod_id
                },
                cache : false,
                beforeSend : function (){

                },
                complete : function($response, $status){
                    if ($status != "error" && $status != "timeout") {
                        $('#selectProductVariation').html($response.responseText);
                        // $('.sub_variations_multiselect').multiselect();
                        $('.variation-select2').select2({
                            width: '100%',
                            placeholder: 'Select Variation',
                            language: {
                                // noResults: function() {
                                //     return '<button id="no-results-btn" onclick="addVariation()">No Match Found, Add New Variation??</button>';
                                // },
                            },
                            escapeMarkup: function(markup) {
                                return markup;
                            },
                        });
                    }
                },
                error : function ($responseObj){
                    alert("Something went wrong while processing your request.\n\nError => "
                        + $responseObj.responseText);
                }
            }); 
        }

        $('.tax_type_checkbox').click(function() {
            $('.tax_type_checkbox').not(this).prop('checked', false);
        });

        $('.bottle_deposit_type_checkbox').click(function() {
            $('.bottle_deposit_type_checkbox').not(this).prop('checked', false);
        });

        // $(".tax_type_checkbox").on('click', function() {
        //     // in the handler, 'this' refers to the box clicked on
        //     var $box = $(this);
        //     if ($box.is(":checked")) {
        //         // the name of the box is retrieved using the .attr() method
        //         // as it is assumed and expected to be immutable
        //         var group = "input:checkbox[name='" + $box.attr("name") + "']";
        //         // the checked state of the group/box on the other hand will change
        //         // and the current value is retrieved using .prop() method
        //         $(group).prop("checked", false);
        //         $box.prop("checked", true);
        //     } else {
        //         $box.prop("checked", false);
        //     }
        // });        


        var taxRadios = $(".tax_type_radio");
        var bottleRadios = $(".bottle_deposit_type_radio");
        var boolTaxRadio;
        var boolBottleRadio;



        for (var i = 0; i < taxRadios.length; i++) {

            taxRadios[i].onclick = function(){

                if (boolTaxRadio == this) {
                    this.checked = false;
                    boolTaxRadio = null;
                    console.log('null');
                }else{
                    boolTaxRadio = this;
                    console.log('this');
                }
            };

        }

        for (var i = 0; i < bottleRadios.length; i++) {

            bottleRadios[i].onclick = function(){

                if (boolBottleRadio == this) {
                    this.checked = false;
                    boolBottleRadio = null;
                    
                }else{
                    boolBottleRadio = this;
                    
                }
            };

        }

        

    });


    function delete_variation(id) {
        var conn = "{{ url('admin/products/variations/delete') }}/"+id;

        $('#deleteVariaton a').attr("href", conn);
    }

    $('.inventory_products_table').DataTable({
        "pageLength" : 20,
        "lengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "All"]]
    });

</script>

@endsection
