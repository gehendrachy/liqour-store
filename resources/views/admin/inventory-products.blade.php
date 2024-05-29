@extends('admin/layouts.header-sidebar')
@section('title', isset($product) ? $product->product_name : 'Inventory Products')
@section('content')


<div class="container-fluid">
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-md-8 col-sm-12">

                <h2>Inventory Products</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('vendor.dashboard',['username' => $username])  }}"><i class="icon-speedometer"></i> {{ \App\User::check_vendor($username)->vendor_details->store_name }}</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('vendor.inventory-products.list',['username' => $username]) }}"><i class="fa fa-shopping-cart"></i> Inventory Products</a>
                        </li>

                        @if($id != 0)
                        <li class="breadcrumb-item active" aria-current="page">{{ isset($product) ? $product->product_name : 'Inventory Products' }}</li>
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
                    <a class="nav-link show active" data-toggle="tab" href="#Pages">{{ isset($product) ? $product->product_name : 'Inventory Products' }}</a>
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
                            <h6 class="title mb-0">All {{ isset($product) ? $product->product_name : 'Inventory Products' }}</h6>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-custom spacing5">

                                            <tbody id="product_variations_field">
                                                <tr>
                                                    <th width="5%">Image</th>
                                                    <th width="22%">Product Variation</th>
                                                    <th width="15%" class="text-center">Cost Price</th>
                                                    <th width="12%" class="text-center">Retail Price</th>
                                                    <th width="15%" class="text-center">Stock</th>
                                                    <th width="10%" class="text-center">SKU</th>
                                                    <th width="10%" class="text-center">Barcode</th>
                                                    <th class="text-right">Actions</th>
                                                </tr>
                                                @foreach($inventory_products as $key => $inv_products)
                                                <tr>
                                                    <th colspan="8">
                                                        <a href="javascript:void(0)" class="collapsed mr-1" data-toggle="collapse" data-target="#collapse{{ $key }}" aria-expanded="false" aria-controls="collapse{{ $key }}">
                                                            {{ $inv_products[0]->product->product_name }}
                                                        </a>
                                                        <i class="fa fa-plus"></i>

                                                        <!-- <a target="_blank" href="{{ route('product_details',['slug' => $inv_products[0]->product->slug]) }}" title="{{ $inv_products[0]->product->product_name }}">
                                                            {{ $inv_products[0]->product->product_name }}
                                                        </a> -->

                                                        <a href="#deleteAllInventoryProducts"
                                                        data-toggle="modal"
                                                        data-id="{{ $inv_products[0]->product->id }}"
                                                        id="delete{{ $inv_products[0]->product->id }}"
                                                        class="btn btn-sm btn-outline-danger pull-right ml-1"
                                                        onClick="delete_all_inventory_product('{{ base64_encode($inv_products[0]->product->id) }}')"><i class="fa fa-trash"></i></a>

                                                        <a href="{{ url('vendor/'.$username.'/inventory-products/bulk-edit/'.base64_encode($inv_products[0]->product->id)) }}" class="btn btn-sm btn-outline-primary pull-right" title="Edit"><i class="fa fa-edit"></i></a>

                                                        <!-- <a class="collapsed mr-1" data-toggle="collapse" data-target="#collapse{{ $key }}" aria-expanded="false" aria-controls="collapse{{ $key }}">
                                                            Product Name
                                                        </a> -->

                                                    </th>
                                                </tr>
                                                <tr id="collapse{{ $key }}" class="collapse" data-parent="#product_variations_field">
                                                    <td colspan="8">
                                                        <table class="table table-responsive">
                                                            <tbody>
                                                            @foreach($inv_products as $key => $inv_prod)
                                                            @php
                                                                if ($inv_prod->product_variation->pack != 1) {
                                                                    $variationName = $inv_prod->product_variation->pack.'x - '.$inv_prod->product_variation->size.' '.$inv_prod->product_variation->container.'s';
                                                                }else{
                                                                    $variationName = $inv_prod->product_variation->size.' '.$inv_prod->product_variation->container;
                                                                }
                                                            @endphp
                                                            <tr>
                                                                <td width="5%" class="text-center">
                                                                    <a class="light-link" href="{{ asset('storage/products/'.$inv_products[0]->product->slug.'/thumbs/large_'.$inv_prod->product_variation->image) }}" data-sub-html="{{ $inv_products[0]->product->product_name.' - '. $variationName }} ">

                                                                        <img class="img img-thumbnail" width="400" src="{{ asset('storage/products/'.$inv_products[0]->product->slug.'/thumbs/thumb_'.$inv_prod->product_variation->image) }}">
                                                                    </a>
                                                                </td>
                                                                <td width="22%">
                                                                    <strong>{{ $variationName }}</strong>
                                                                </td>
                                                                
                                                                <td width="15%" class="text-center" style="white-space: normal;">
                                                                    ${{ $inv_prod->cost_price }}
                                                                </td>
                                                                <td width="12%" class="text-center" style="white-space: normal;">
                                                                    ${{ $inv_prod->retail_price }}
                                                                </td>
                                                                <td width="15%" class="text-center"  style="white-space: normal;">
                                                                    {{ $inv_prod->stock }}
                                                                </td>
                                                                <td width="10%" class="text-center" style="white-space: normal;">
                                                                    {{ $inv_prod->sku }}
                                                                </td>
                                                                <td width="10%" class="text-center" style="white-space: normal;">
                                                                    {{ $inv_prod->barcode }}
                                                                </td>
                                                                <td class="text-right">
                                                                    <!-- <a href="#viewModal"
                                                                    class="btn btn-sm btn-outline-success" data-toggle="modal"
                                                                    data-id="{{ $inv_prod->id }} "
                                                                    id="view{{ $inv_prod->id }}"
                                                                    data-short_content = '{{ addslashes($inv_prod->short_content) }}'
                                                                    onclick="view('{{ $inv_prod->id }}','{{ $inv_prod->product_name }}','{{ $inv_prod->slug }}', '{{ $inv_prod->display }}','{{ $inv_prod->image }}','{{ $inv_prod->featured }}')"
                                                                    title="View"><i class="fa fa-eye"></i></a> -->
                                                                    
                                                                    <!-- <a href="{{ url('vendor/'.$username.'/inventory-products/bulk-edit/'.base64_encode($inv_products[0]->product->id)) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fa fa-edit"></i></a> -->
                                                                    
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
                            <h6 class="title mb-0">{{ isset($product) ? $product->product_name : 'Add Inventory Products' }}</h6>
                        </div>
                        <div class="body mt-2">
                            <form id="parsley-form" method="post" action="{{ route('vendor.inventory-products.create_update',['username' => $username]) }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="username" value="{{ base64_encode($username) }}">
                                <input type="hidden" name="user_id" value="{{ base64_encode(session()->get('vendorID')) }}">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group mb-3">
                                                <!-- <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fa fa-text-width"></i> &nbsp;Select Product</span>
                                                </div> -->
                                                @if($id == 0)
                                                <select name="product_id" class="custom-select" required id="selectProduct">
                                                    <option selected disabled>Choose Product...</option>
                                                    
                                                    @foreach($products as $key => $prod)
                                                        <option data-product-image="{{ $prod->image }}" {{ $id != 0 && $product->product_id == $prod->id ? 'selected' : (old('product_id') == $prod->id ? 'selected' : '') }} value="{{ $prod->id }}">{{ $prod->product_name }}</option>
                                                    @endforeach
                                                </select>
                                                @else
                                                    <select name="product_id" class="custom-select" required id="selectProduct">
                                                        <option selected value="{{ $product->id }}">{{ $product->product_name }}</option>
                                                    </select>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 clearfix">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text" style="background-color: #e1e8ed">

                                                                <input type="checkbox" name="tax_type" value="1" class="tax_type_checkbox tax_type_index_1">

                                                            </div>
                                                        </div>
                                                        <span class="form-control disabled">Tax Type 1 </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group mb-3">

                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text" style="background-color: #e1e8ed">

                                                                <input type="checkbox" name="tax_type" value="2" class="tax_type_checkbox tax_type_index_2">

                                                            </div>
                                                        </div>
                                                        <span class="form-control disabled">Tax Type 2 </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text" style="background-color: #e1e8ed">

                                                                <input type="checkbox" name="tax_type" value="3" class="tax_type_checkbox tax_type_index_3">

                                                            </div>
                                                        </div>
                                                        <span class="form-control disabled">Tax Type 3 </span>
                                                    </div>
                                                </div>
                                                <hr>
                                            </div>
                                            

                                        </div>


                                        <div class="col-md-12">
                                            <style>
                                                .light_version .table tr td, .light_version .table tr th {
                                                    border-color: #00000038 !important;
                                                    background: #fff !important;
                                                }
                                            </style>
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th><strong style="font-size: 8px;">Add | Remove </strong></th>
                                                            <th width="15%"><strong >Product Variation</strong></th>
                                                            <th><strong>Cost Price</strong></th>
                                                            <th><strong>Retail Price</strong></th>
                                                            <th><strong>Stock </strong></th>
                                                            <th><strong>SKU &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></th>
                                                            <th><strong>BarCode</strong></th>
                                                            <th><strong>Display</strong></th>
                                                            <th><strong >Bottle Deposit Type</strong></th>
                                                        </tr>
                                                    </thead>
                                                    
                                                    <tbody id="tableProductVariation">
                                                        <!-- Product Variations Here by Ajax -->
                                                    </tbody>
                                                </table>
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

    <div class="modal fade" id="deleteAllInventoryProducts" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
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
                    <span>All the Variations of this product will be deleted from your Inventory.</span>
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

    function delete_all_inventory_product(id) {
        var conn = './inventory-products/delete-all/' + id;
        $('#deleteAllInventoryProducts a').attr("href", conn);
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

        
        var product_id = $("#selectProduct").val();

        call_ajax_function(product_id);

        $('#selectProduct').change(function(){  
            var product_id = $(this).val();
            $('#modal-loader').show();
            call_ajax_function(product_id);

        });  

        function call_ajax_function(product_id) {
            $.ajax({
                url : "{{ URL::route('vendor.inventory-products.get_product_variations', ['username' => $username]) }}",
                type : "POST",
                data : {
                    '_token': '{{ csrf_token() }}',
                    product_id: product_id
                },
                cache : false,
                beforeSend : function (){
                    $('.tax_type_checkbox').attr('checked',false);
                },
                complete : function($response, $status){

                    if ($status != "error" && $status != "timeout") {
                        
                        // console.log($response.responseText);
                        // return;

                        var obj = jQuery.parseJSON($response.responseText);

                        $('#tableProductVariation').html(obj.tableResponse);
                        $(".tax_type_index_"+obj.tax_type_index).attr('checked',true);

                        $(".active_status").change(function(){
                            var key = this.value;

                            if (this.checked) {
                                $('.active_inactive'+key).attr('disabled',false);
                                $('.req_not_req'+key).attr('required',true);
                                $('.variation_display'+key).attr('checked',true);
                            }else{
                                $('.active_inactive'+key).attr('disabled',true);
                                $('.req_not_req'+key).attr('required',false);
                                $('.variation_display'+key).attr('checked',false);
                            }
                        });

                        $('.bottle_deposit_type_checkbox').on('change', function() {
                            $('input[name="' + this.name + '"]').not(this).prop('checked', false);
                        });

                        $(".decimal-input").keypress(function(event){
                            return isDecimalNumber(event, this);
                        });

                        $(".number-input").keypress(function(event){
                            return isNumberKey(event, this);
                        });
                        $('#modal-loader').hide();
                    }
                },
                error : function ($responseObj){
                    alert("Something went wrong while processing your request.\n\nError => "
                        + $responseObj.responseText);
                }
            }); 
        }

        $('.tax_type_checkbox, .bottle_deposit_type_checkbox').on('change', function() {
            $('input[name="' + this.name + '"]').not(this).prop('checked', false);
        });

        // $('.tax_type_checkbox').click(function() {
        //     $('.tax_type_checkbox').not(this).prop('checked', false);
        // });

        // $('.bottle_deposit_type_checkbox').click(function() {
        //     $('.bottle_deposit_type_checkbox').not(this).prop('checked', false);
        // });

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

    <script>        

        function isNumberKey(evt, element) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
        }

        function isDecimalNumber(evt, element){ 

            var charCode = (evt.which) ? evt.which : event.keyCode 

            if  ((charCode != 46 || ($(element).val().match(/\./g) || []).length > 0) && (charCode < 48 || charCode > 57))
                return false; 
            return true; 
        }
    </script>


@endsection
