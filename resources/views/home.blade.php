@extends('layouts.app')
@section('title','Home')
@section('content')
<!-- Block Spotlight1  -->
<section class="so-spotlight1">
    <div id="so-slideshow" >
        <div class="owl-carousel" data-loop="yes" data-margin="0" data-nav="yes" data-dots="yes" data-items_xs="1" data-items_sm="1" data-items_md="1">
            @foreach($sliders as $slider)
            <div>
                <a href="{{ $slider->link }}" target="_blank">
                    <img src="{{ asset('storage/slider/thumbs/slide_'.$slider->image) }}" alt="{{ $slider->title }}">
                </a>
            </div>
            @endforeach
        </div>

    </div>
</section>
<!-- //Block Spotlight1  -->

<!-- Block Spotlight2  -->
<section class="so-spotlight2">
    <div class="modcontent clearfix">
        <div class="policy-detail">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="banner-policy">
                            <div class="policy policy1">
                                <a href="javascript:void(0)">
                                    <span class="ico-policy"></span>
                                    <div class="service-info">
                                        <span class="title">Shop Quickly Near You</span> <br> <span>Find the best Deals Near You</span>
                                    </div>
                                </a>
                            </div>
                            <div class="policy policy2">
                                <a href="javascript:void(0)">
                                    <span class="ico-policy"></span>
                                    <div class="service-info">
                                        <span class="title">Competetive Prices</span> <br><span>Look FOr Best Price on Products Near You</span>
                                    </div>
                                </a>
                            </div>
                            <div class="policy policy3">
                                <a href="javascript:void(0)">
                                    <span class="ico-policy"></span>
                                    <div class="service-info">
                                        <span class="title">Online Support</span> <br><span>We support online 24/24 on day</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<!-- //Block Spotlight2  -->

<!-- Main Container  -->
<div class="main-container container">

    <div class="row">

        <div id="content" class="col-md-12 col-sm-12  col-xs-12">
            <div class="digital">
                <div class="row">
                    <div class=" col-sm-12">
                        <h3 class="modtitle">Popular Near you</h3>
                        <hr>
                        <hr>
                        <hr>
                        <div class="digital-owl">
                            <div class=" owl-carousel digital-owl " data-dots="no" data-nav="yes" data-loop="yes" data-items_xs="2" data-items_sm="2" data-items_md="5" data-margin="20">
                                @foreach($latProducts as $latpro)
                                @php
                                    $invProd = $latpro->inventory_products->sortBy('retail_price')->first();
                                    $productImage = asset('storage/products/'.$invProd->product->slug.'/thumbs/small_'.$invProd->product_variation->image);
                                @endphp
                                <div class="product-layout">
                                    <div class="product-item-container">
                                        <div class="left-block">
                                            <div class="product-image-container  second_img ">
                                                <a href="{{ url('product/'.$latpro->slug) }}" class="product-img">
                                                    <img src="{{ $productImage }}" alt="{{ $latpro->slug }}">
                                                </a>
                                                <!--Sale Label-->
                                                <!-- <span class="new">New</span> -->
                                                @if(Auth::check())
                                                <div class="hover">
                                                    <ul>
                                                        <li class="icon-heart">
                                                            <a class="wishlist btn-add-to-wishlist" data-toggle="tooltip"  data-product-id="{{ $latpro->id }}" data-product-image="{{ $productImage }}" data-product-name="{{ $latpro->product_name }}" data-product-slug="{{ $latpro->slug }}" data-original-title="Add to Wish List"><i class="fa fa-heart"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="right-block">
                                            <div class="caption">
                                                <h4><a href="{{ url('product/'.$latpro->slug) }}">{{ $latpro->product_name }}</a></h4>


                                                <div class="price">
                                                    <span class="price-new">
                                                        ${{ $invProd->retail_price }}

                                                        <?php 
                                                            if ($invProd->product_variation->pack != 1) {
                                                                $variationName = $invProd->product_variation->pack.'x - '.$invProd->product_variation->size.' '.$invProd->product_variation->container.'s';
                                                            }else{
                                                                $variationName = $invProd->product_variation->size.' '.$invProd->product_variation->container;
                                                            }
                                                        ?>
                                                        <small>{{ $variationName }}</small>
                                                    </span>
                                                </div>
                                                
                                                <div class="description item-desc hidden">
                                                    {!! $latpro->summary !!}
                                                </div>
                                            </div>

                                            <!-- <div class="button-group">
                                                <button class="addToCart btn btn-default "   data-toggle="tooltip" title="" onclick="add_to_cart('{{ addslashes($latpro->title) }}','{{ $latpro->slug }}', '{{ $latpro->image }}');" data-original-title="Add to Cart"> <span class="">Add to Cart</span></button>
                                            </div> -->
                                        </div><!-- right block -->
                                    </div>
                                </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class=" fashion-jewerly">
                <div class="row">
                    <div class=" col-sm-12">
                        <h3 class="modtitle">Featured Products</h3>
                        <hr>
                        <hr>
                        <hr>
                        <div class="fashion-jewerly-owl">
                            <div class=" owl-carousel fashion-jewerly-owl " data-dots="no" data-nav="yes" data-loop="yes" data-items_xs="2" data-items_sm="2" data-items_md="5" data-margin="10">
                                @foreach($featProducts as $featpro)

                                @php
                                    $invProd = $featpro->inventory_products->sortBy('retail_price')->first();

                                    $productImage = asset('storage/products/'.$invProd->product->slug.'/thumbs/small_'.$invProd->product_variation->image);
                                @endphp

                                <div class="product-layout">
                                    <div class="product-item-container">
                                        <div class="left-block">
                                            <div class="product-image-container second_img ">
                                                <a href="{{ url('product/'.$featpro->slug) }}" class="product-img">
                                                    <img src="{{ $productImage }}" alt="{{ $featpro->slug }}">
                                                </a>
                                                <!--Sale Label-->
                                                <!-- <span class="new">New</span> -->
                                                @if(Auth::check())
                                                <div class="hover">
                                                    <ul>
                                                        <li class="icon-heart">
                                                            <a class="wishlist btn-add-to-wishlist" data-toggle="tooltip"  data-product-id="{{ $featpro->id }}" data-product-image="{{ $productImage }}" data-product-name="{{ $featpro->product_name }}" data-product-slug="{{ $featpro->slug }}" data-original-title="Add to Wish List"><i class="fa fa-heart"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="right-block">
                                            <div class="caption">
                                                <h4><a href="{{ url('product/'.$featpro->slug) }}">{{ $featpro->product_name }}</a></h4>


                                                <div class="price">
                                                    <span class="price-new">
                                                        ${{ $invProd->retail_price }}

                                                        <?php 
                                                            if ($invProd->product_variation->pack != 1) {
                                                                $variationName = $invProd->product_variation->pack.'x - '.$invProd->product_variation->size.' '.$invProd->product_variation->container.'s';
                                                            }else{
                                                                $variationName = $invProd->product_variation->size.' '.$invProd->product_variation->container;
                                                            }
                                                        ?><br>
                                                        <small>{{ $variationName }}</small>
                                                    </span>
                                                </div>
                                                <div class="description item-desc hidden">
                                                    {!! $featpro->summary !!}
                                                </div>
                                            </div>

                                            <!-- <div class="button-group">
                                                <button class="addToCart btn btn-default "   data-toggle="tooltip" title="" onclick="add_to_cart('{{ addslashes($featpro->title) }}','{{ $featpro->slug }}', '{{ $featpro->image }}');" data-original-title="Add to Cart"> <span class="">Add to Cart</span></button>
                                            </div> -->
                                        </div><!-- right block -->
                                    </div>
                                </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mid-container type_footer3">    
                <div class="footer-mid">
                    <div class=" newsletter">
                        <div class=" container">
                            <p class="text-center">Lorem ipsum dolor sit amet consectetur adipisicing elit. Inventore doloribus, ratione expedita autem qui deserunt nemo ullam nulla sint? Perspiciatis deleniti veniam reiciendis saepe placeat exercitationem animi corrupti iure, nesciunt non in dolorem et ut nihil totam doloribus neque vel?</p>
                            <br>
                            <div class=" row">
                                <div class="col-xs-12 col-sm-12 col-md-9 col-md-offset-2 news-letter">
                                    <h3 class="modtitle3"><span>Search By </span><br> Location</h3> 
                                    <div class="email">
                                        <input type="email" placeholder="Enter your Location & Start Search" value="" class="form-control" id="txtemail" name="txtemail" size="55">
                                        <div class="subcribe">
                                            <button class="btn btn-default btn-lg" type="submit" name="submit">Search</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="wrap-categories">
                <div class="row">
                    <div class="col-sm-4 col-xs-12">
                        <div class=" new-arrivals">
                            <div class="module latest-product titleLine">
                                <h3 class="modtitle">New Arrivals</h3>
                                <hr>
                                <hr>
                                <hr>
                                <div class="modcontent owl-carousel owl-new-arrivals " data-dots="no" data-nav="yes" data-loop="yes" data-items_xs="1" data-items_sm="1" data-items_md="1" data-margin="10">
                                    <div class="product-latest-item">
                                        <div class="media">
                                            <div class="media-left">
                                                <a href="{{ asset('products/products-name') }}"><img src="{{ asset('frontend/img/files/products/product-2.jpg') }}" alt="Cisi Chicken" title="Cisi Chicken" class="img-responsive" style="width: 78px; height: 104px;"></a>
                                            </div>
                                            <div class="media-body">
                                                <div class="caption">
                                                    <h4><a href="{{ asset('products/products-name') }}">Sunt Molup</a></h4>

                                                    <div class="price">
                                                        <span class="price-new">$100.00</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="media">
                                            <div class="media-left">
                                                <a href="{{ asset('products/products-name') }}"><img src="{{ asset('frontend/img/files/products/product-3.jpg') }}" alt="Cisi Chicken" title="Cisi Chicken" class="img-responsive" style="width: 78px; height: 104px;"></a>
                                            </div>
                                            <div class="media-body">
                                                <div class="caption">
                                                    <h4><a href="{{ asset('products/products-name') }}">Et Spare</a></h4>



                                                    <div class="price">
                                                        <span class="price-new">$36.00</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="media">
                                            <div class="media-left">
                                                <a href="{{ asset('products/products-name') }}"><img src="{{ asset('frontend/img/files/products/product-4.jpg') }}" alt="Cisi Chicken" title="Cisi Chicken" class="img-responsive" style="width: 78px; height: 104px;"></a>
                                            </div>
                                            <div class="media-body">
                                                <div class="caption">
                                                    <h4><a href="{{ asset('products/products-name') }}">Cisi Chicken</a></h4>



                                                    <div class="price">
                                                        <span class="price-new">$71.00</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-latest-item">
                                        <div class="media">
                                            <div class="media-left">
                                                <a href="{{ asset('products/products-name') }}"><img src="{{ asset('frontend/img/files/products/product-5.jpg') }}" alt="Cisi Chicken" title="Cisi Chicken" class="img-responsive" style="width: 78px; height: 104px;"></a>
                                            </div>
                                            <div class="media-body">
                                                <div class="caption">
                                                    <h4><a href="{{ asset('products/products-name') }}">Sunt Molup</a></h4>

                                                    <div class="price">
                                                        <span class="price-new">$126.00</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="media">
                                            <div class="media-left">
                                                <a href="{{ asset('products/products-name') }}"><img src="{{ asset('frontend/img/files/products/product-6.jpg') }}" alt="Cisi Chicken" title="Cisi Chicken" class="img-responsive" style="width: 78px; height: 104px;"></a>
                                            </div>
                                            <div class="media-body">
                                                <div class="caption">
                                                    <h4><a href="{{ asset('products/products-name') }}">Et Spare</a></h4>



                                                    <div class="price">
                                                        <span class="price-new">$65.00</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="media">
                                            <div class="media-left">
                                                <a href="{{ asset('products/products-name') }}"><img src="{{ asset('frontend/img/files/products/product-7.jpg') }}" alt="Cisi Chicken" title="Cisi Chicken" class="img-responsive" style="width: 78px; height: 104px;"></a>
                                            </div>
                                            <div class="media-body">
                                                <div class="caption">
                                                    <h4><a href="{{ asset('products/products-name') }}">Cisi Chicken</a></h4>



                                                    <div class="price">
                                                        <span class="price-new">$68.00</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-latest-item">
                                        <div class="media">
                                            <div class="media-left">
                                                <a href="{{ asset('products/products-name') }}"><img src="{{ asset('frontend/img/files/products/product-8.jpg') }}" alt="Cisi Chicken" title="Cisi Chicken" class="img-responsive" style="width: 78px; height: 104px;"></a>
                                            </div>
                                            <div class="media-body">
                                                <div class="caption">
                                                    <h4><a href="{{ asset('products/products-name') }}">Sunt Molup</a></h4>

                                                    <div class="price">
                                                        <span class="price-new">$130.00</span>
                                                        <span class="price-old">$165.00</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="media">
                                            <div class="media-left">
                                                <a href="{{ asset('products/products-name') }}"><img src="{{ asset('frontend/img/files/products/product-1.jpg') }}" alt="Cisi Chicken" title="Cisi Chicken" class="img-responsive" style="width: 78px; height: 104px;"></a>
                                            </div>
                                            <div class="media-body">
                                                <div class="caption">
                                                    <h4><a href="{{ asset('products/products-name') }}">Et Spare</a></h4>



                                                    <div class="price">
                                                        <span class="price-new">$69.00</span>
                                                        <span class="price-old">$89.00</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="media">
                                            <div class="media-left">
                                                <a href="{{ asset('products/products-name') }}"><img src="{{ asset('frontend/img/files/products/product-2.jpg') }}" alt="Cisi Chicken" title="Cisi Chicken" class="img-responsive" style="width: 78px; height: 104px;"></a>
                                            </div>
                                            <div class="media-body">
                                                <div class="caption">
                                                    <h4><a href="{{ asset('products/products-name') }}">Cisi Chicken</a></h4>



                                                    <div class="price">
                                                        <span class="price-new">$96.00</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <div class=" featured">
                            <div class="module latest-product titleLine">
                                <h3 class="modtitle">Featured</h3>
                                <hr>
                                <hr>
                                <hr>
                                <div class="modcontent">
                                    <div class="owl-carousel owl-featured" data-nav="yes" data-loop='yes' data-margin="0" data-items_xs="1" data-items_sm="1" data-items_md="1">
                                        <div class="product-latest-item">
                                            <div class="media">
                                                <div class="media-left">
                                                    <a href="{{ asset('products/products-name') }}"><img src="{{ asset('frontend/img/files/products/product-3.jpg') }}" alt="Cisi Chicken" title="Cisi Chicken" class="img-responsive" style="width: 78px; height: 104px;"></a>
                                                </div>
                                                <div class="media-body">
                                                    <div class="caption">
                                                        <h4><a href="{{ asset('products/products-name') }}">Sunt Molup</a></h4>



                                                        <div class="price">
                                                            <span class="price-new">$98.00</span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="media">
                                                <div class="media-left">
                                                    <a href="{{ asset('products/products-name') }}"><img src="{{ asset('frontend/img/files/products/product-4.jpg') }}" alt="Cisi Chicken" title="Cisi Chicken" class="img-responsive" style="width: 78px; height: 104px;"></a>
                                                </div>
                                                <div class="media-body">
                                                    <div class="caption">
                                                        <h4><a href="{{ asset('products/products-name') }}">Et Spare</a></h4>



                                                        <div class="price">
                                                            <span class="price-new">$165.00</span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="media">
                                                <div class="media-left">
                                                    <a href="{{ asset('products/products-name') }}"><img src="{{ asset('frontend/img/files/products/product-5.jpg') }}" alt="Cisi Chicken" title="Cisi Chicken" class="img-responsive" style="width: 78px; height: 104px;"></a>
                                                </div>
                                                <div class="media-body">
                                                    <div class="caption">
                                                        <h4><a href="{{ asset('products/products-name') }}">Cisi Chicken</a></h4>



                                                        <div class="price">
                                                            <span class="price-new">$59.00</span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-latest-item">
                                            <div class="media">
                                                <div class="media-left">
                                                    <a href="{{ asset('products/products-name') }}"><img src="{{ asset('frontend/img/files/products/product-6.jpg') }}" alt="Cisi Chicken" title="Cisi Chicken" class="img-responsive" style="width: 78px; height: 104px;"></a>
                                                </div>
                                                <div class="media-body">
                                                    <div class="caption">
                                                        <h4><a href="{{ asset('products/products-name') }}">Sunt Molup</a></h4>



                                                        <div class="price">
                                                            <span class="price-new">$98.00</span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="media">
                                                <div class="media-left">
                                                    <a href="{{ asset('products/products-name') }}"><img src="{{ asset('frontend/img/files/products/product-7.jpg') }}" alt="Cisi Chicken" title="Cisi Chicken" class="img-responsive" style="width: 78px; height: 104px;"></a>
                                                </div>
                                                <div class="media-body">
                                                    <div class="caption">
                                                        <h4><a href="{{ asset('products/products-name') }}">Et Spare</a></h4>



                                                        <div class="price">
                                                            <span class="price-new">$165.00</span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="media">
                                                <div class="media-left">
                                                    <a href="{{ asset('products/products-name') }}"><img src="{{ asset('frontend/img/files/products/product-8.jpg') }}" alt="Cisi Chicken" title="Cisi Chicken" class="img-responsive" style="width: 78px; height: 104px;"></a>
                                                </div>
                                                <div class="media-body">
                                                    <div class="caption">
                                                        <h4><a href="{{ asset('products/products-name') }}">Cisi Chicken</a></h4>



                                                        <div class="price">
                                                            <span class="price-new">$59.00</span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <div class=" bestsellers">
                            <div class="module latest-product titleLine">
                                <h3 class="modtitle">Bestsellers</h3>
                                <hr>
                                <hr>
                                <hr>
                                <div class="modcontent">
                                    <div class="owl-carousel owl-bestsellers" data-nav="yes" data-loop='yes' data-margin="0" data-items_xs="1" data-items_sm="1" data-items_md="1">
                                        <div class="product-latest-item">
                                            <div class="media">
                                                <div class="media-left">
                                                    <a href="{{ asset('products/products-name') }}"><img src="{{ asset('frontend/img/files/products/product-1.jpg') }}" alt="Cisi Chicken" title="Cisi Chicken" class="img-responsive" style="width: 78px; height: 104px;"></a>
                                                </div>
                                                <div class="media-body">
                                                    <div class="caption">
                                                        <h4><a href="{{ asset('products/products-name') }}">Sunt Molup</a></h4>

                                                        <div class="price">
                                                            <span class="price-new">$98.00</span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="media">
                                                <div class="media-left">
                                                    <a href="{{ asset('products/products-name') }}"><img src="{{ asset('frontend/img/files/products/product-2.jpg') }}" alt="Cisi Chicken" title="Cisi Chicken" class="img-responsive" style="width: 78px; height: 104px;"></a>
                                                </div>
                                                <div class="media-body">
                                                    <div class="caption">
                                                        <h4><a href="{{ asset('products/products-name') }}">Et Spare</a></h4>



                                                        <div class="price">
                                                            <span class="price-new">$165.00</span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="media">
                                                <div class="media-left">
                                                    <a href="{{ asset('products/products-name') }}"><img src="{{ asset('frontend/img/files/products/product-3.jpg') }}" alt="Cisi Chicken" title="Cisi Chicken" class="img-responsive" style="width: 78px; height: 104px;"></a>
                                                </div>
                                                <div class="media-body">
                                                    <div class="caption">
                                                        <h4><a href="{{ asset('products/products-name') }}">Cisi Chicken</a></h4>



                                                        <div class="price">
                                                            <span class="price-new">$59.00</span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-latest-item">
                                            <div class="media">
                                                <div class="media-left">
                                                    <a href="{{ asset('products/products-name') }}"><img src="{{ asset('frontend/img/files/products/product-4.jpg') }}" alt="Cisi Chicken" title="Cisi Chicken" class="img-responsive" style="width: 78px; height: 104px;"></a>
                                                </div>
                                                <div class="media-body">
                                                    <div class="caption">
                                                        <h4><a href="{{ asset('products/products-name') }}">Sunt Molup</a></h4>

                                                        <div class="price">
                                                            <span class="price-new">$98.00</span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="media">
                                                <div class="media-left">
                                                    <a href="{{ asset('products/products-name') }}"><img src="{{ asset('frontend/img/files/products/product-5.jpg') }}" alt="Cisi Chicken" title="Cisi Chicken" class="img-responsive" style="width: 78px; height: 104px;"></a>
                                                </div>
                                                <div class="media-body">
                                                    <div class="caption">
                                                        <h4><a href="{{ asset('products/products-name') }}">Et Spare</a></h4>



                                                        <div class="price">
                                                            <span class="price-new">$165.00</span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="media">
                                                <div class="media-left">
                                                    <a href="{{ asset('products/products-name') }}"><img src="{{ asset('frontend/img/files/products/product-6.jpg') }}" alt="Cisi Chicken" title="Cisi Chicken" class="img-responsive" style="width: 78px; height: 104px;"></a>
                                                </div>
                                                <div class="media-body">
                                                    <div class="caption">
                                                        <h4><a href="{{ asset('products/products-name') }}">Cisi Chicken</a></h4>



                                                        <div class="price">
                                                            <span class="price-new">$59.00</span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="brands">
                        <h3 class="modtitle">Fetured brands</h3>
                        <hr><hr><hr>
                        <div class="owl-carousel owl-fetured-brand" data-dots="yes" data-nav="yes" data-loop="yes" data-items_xs="2" data-items_sm="4" data-items_md="5" data-margin="10">
                            <div class="img-brand">
                                <a href="javascript:void(0)"><img src="{{ asset('frontend/img/demo/brands/brand-1.jpg') }}" alt=""></a>
                            </div>
                            <div class="img-brand">
                                <a href="javascript:void(0)"><img src="{{ asset('frontend/img/demo/brands/brand-2.jpg') }}" alt=""></a>
                            </div>
                            <div class="img-brand">
                                <a href="javascript:void(0)"><img src="{{ asset('frontend/img/demo/brands/brand-3.jpg') }}" alt=""></a>
                            </div>
                            <div class="img-brand">
                                <a href="javascript:void(0)"><img src="{{ asset('frontend/img/demo/brands/brand-4.jpg') }}" alt=""></a>
                            </div>
                            <div class="img-brand">
                                <a href="javascript:void(0)"><img src="{{ asset('frontend/img/demo/brands/brand-5.jpg') }}" alt=""></a>
                            </div>
                            <div class="img-brand">
                                <a href="javascript:void(0)"><img src="{{ asset('frontend/img/demo/brands/brand-6.jpg') }}" alt=""></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- //Main Container -->

<script type="text/javascript">

    var $typeheader = 'header-home2';

</script>
@endsection
