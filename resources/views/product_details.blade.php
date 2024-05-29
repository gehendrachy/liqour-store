@extends('layouts.app')
@section('title', $product->product_name )
@section('content')
<div class="main-container container">
    <ul class="header-main ">
        <li class="home">
            <a href="{{ route('home') }}"> Home </a><i class="fa fa-angle-right" aria-hidden="true"></i>
        </li>
        @php
            $parentCategory = \App\Category::where('id',$product->category->parent_id)->first();
        @endphp
        
        @if(isset($parentCategory))
        <li class="home">
            <a href="{{ route('parent_category_products',['slug' => $parentCategory->slug]) }}">{{ $parentCategory->title }}</a> <i class="fa fa-angle-right" aria-hidden="true"></i>
        </li>
        @endif

        <li class="home">
            <a href="{{ route('category_products',['slug' => $product->category->slug]) }}">{{ $product->category->title }}</a> <i class="fa fa-angle-right" aria-hidden="true"></i>
        </li>

        <li> {{ $product->product_name }}</li>
    </ul>

    <div class="row">
        <!--Middle Part Start-->
        <div id="content " class="col-md-12 col-sm-12 type-1">
            <div class="row">
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-4">
                            <img src="{{ asset('storage/products/'.$product->slug.'/thumbs/small_'.$product->image) }}" class="img-responsive main-image" alt="{{ $product->slug }}">
                        </div>
                        <div class="col-sm-8">
                            <div class="title-product">
                                <h1>{{ $product->product_name }}</h1>
                            </div>
                            <br>
                            <p>Select The Type</p>
                            <ul class="list-inline">
                                @foreach($inventory_product_variations as $key => $invProdVar)
                                <li onclick="get_related_vendors({{ $invProdVar->product_variation_id }})" class="select-button {{ $key == 0 ? 'selected' : '' }}" data-product-variation-id="{{ $invProdVar->product_variation_id }}" data-image="{{ asset('storage/products/'.$product->slug.'/thumbs/small_'.$invProdVar->product_variation->image) }}">

                                    <p>
                                        {{ $invProdVar->product_variation->pack != 1 ? $invProdVar->product_variation->pack.'x' : $invProdVar->product_variation->size}}
                                    </p>
                                    <span>
                                        {{$invProdVar->product_variation->pack != 1 ? $invProdVar->product_variation->size : '' }} 

                                        {{ $invProdVar->product_variation->pack != 1 ? $invProdVar->product_variation->container.'s' : $invProdVar->product_variation->container }}
                                    </span>
                                    
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="detail-wrapper">
                        <h2>Product Details</h2>
                        <br>
                        {!! $product->short_content !!}
                    
                    </div>
                    <hr>
                    <h2>Product Description</h2>
                    <hr>
                    <br>
                    {!! $product->long_content !!}
                </div>
                <div class="col-sm-4 side-product">
                    <div class="producttab ">
                        <div class="tabsslider  col-xs-12">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#getItNowVendors">Get It Now</a></li>
                                <li class="item_nonactive "><a data-toggle="tab" href="#getInNowLocation">Location</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="tab-content col-xs-12">
                        <div id="getItNowVendors" class="tab-pane fade active in show_vendors">

                        </div>
                        <div id="getInNowLocation" class="tab-pane fade in show_vendors">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('post-scripts')
    <script>
        
    </script>
    <script type="text/javascript">

        function call_ajax_function(product_variation_id){

            $.ajax({
                url : "{{ URL::route('get-related-vendors') }}",
                type : "POST",
                data : {
                    '_token': '{{ csrf_token() }}',
                    product_variation_id: product_variation_id
                },
                cache : false,
                beforeSend : function (){

                },
                complete : function($response, $status){
                    if ($status != "error" && $status != "timeout") {
                        $('.show_vendors').html($response.responseText);
                        
                        $(".btn-add-to-cart").click(function(){

                            var inventoryId = $(this).data("inventory-id");
                            var productId = $(this).data("product-id");
                            var productVariationId = $(this).data("product-variation-id");
                            var vendorId = $(this).data("vendor-id");
                            var variationPrice = $(this).data("price");
                            var stock = $(this).data("stock-qty");
                            var productSlug = $(this).data("product-slug");
                            var productName = $(this).data("product-name");
                            var productImage = $(this).data("product-image");

                            var orderedQtyField = $(this).data("order-qty-field");
                            var orderedQty = $("."+orderedQtyField).val();

                            


                            addItem(inventoryId, productId, productVariationId, vendorId, variationPrice, stock, productSlug, productName, productImage, orderedQty);

                        });
                        // $('.sub_variations_multiselect').multiselect();
                    }
                },
                error : function ($responseObj){
                    alert("Something went wrong while processing your request.\n\nError => "
                        + $responseObj.responseText);
                }
            }); 
        }

        function get_related_vendors(product_variation_id) {
            call_ajax_function(product_variation_id);
        }

        $(".select-button").each(function(){

            if ($(this).hasClass('select-button selected')) {

                product_variation_id = $(this).data('product-variation-id');
                
                $(".main-image").attr('src', $(this).data("image"));

                call_ajax_function(product_variation_id);

                console.log(product_variation_id);
            }


        });

    </script>
    <script>

        $(".select-button").click(function(){
            $(".main-image").attr('src', $(this).data("image"));
            $(".select-button").removeClass("selected");
            $(this).addClass("selected");
        })
    </script>
@endpush