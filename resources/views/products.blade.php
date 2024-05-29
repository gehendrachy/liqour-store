@extends('layouts.app')
@section('title','Product Name')
@section('content')
<div class="main-container container">
    <ul class="header-main ">
        <li class="home"><a href="{{ url('/') }}">Home   </a><i class="fa fa-angle-right" aria-hidden="true"></i></li>
        <li class="home"><a href="{{ url('stores/stores-name') }}">Store Name   </a><i class="fa fa-angle-right" aria-hidden="true"></i></li>
        <li>Product Name</li>
    </ul>

    <div class="row">
        <!--Middle Part Start-->
        <div id="content " class="col-md-12 col-sm-12 type-1">

            <div class="product-view row">
                <div class="left-content-product col-lg-10 col-lg-offset-1 col-xs-12">
                    <div class="row">
                        <div class="content-product-left class-honizol col-md-5 col-sm-12 col-xs-12 ">
                            <div class="large-image  ">
                                <img itemprop="image" class="product-image-zoom" src="{{ asset('frontend/img/files/products/product-3.jpg') }}" data-zoom-image="{{ asset('frontend/img/files/products/zoom/p3.jpg')}}" title="Bint Beef" alt="Bint Beef">
                            </div>
                            <div id="thumb-slider" class="owl-theme owl-loaded owl-drag full_slider owl-carousel " data-nav='yes' data-loop="yes" data-margin="10" data-items_xs="2" data-items_sm="3" data-items_md="4">
                                <a data-index="0" class="img thumbnail " data-image="{{ asset('frontend/img/files/products/zoom/p3.jpg')}}" title="Bint Beef">
                                    <img src="{{ asset('frontend/img/files/products/product-3.jpg') }}" title="Bint Beef" alt="Bint Beef">
                                </a>
                                <a data-index="1" class="img thumbnail " data-image="{{ asset('frontend/img/files/products/zoom/p7.jpg')}}" title="Bint Beef">
                                    <img src="{{ asset('frontend/img/files/products/product-7.jpg') }}" title="Bint Beef" alt="Bint Beef">
                                </a>
                                <a data-index="2" class="img thumbnail " data-image="{{ asset('frontend/img/files/products/zoom/p8.jpg')}}" title="Bint Beef">
                                    <img src=" {{ asset('frontend/img/files/products/product-8.jpg') }}" title="Bint Beef" alt="Bint Beef">
                                </a>
                                <a data-index="3" class="img thumbnail " data-image="{{ asset('frontend/img/files/products/zoom/p9.jpg')}} " title="Bint Beef">
                                    <img src="{{ asset('frontend/img/files/products/product-9.jpg') }}" title="Bint Beef" alt="Bint Beef">
                                </a>
                                <a data-index="3" class="img thumbnail " data-image="{{ asset('frontend/img/files/products/zoom/p10.jpg')}}" title="Bint Beef">
                                    <img src="{{ asset('frontend/img/files/products/product-10.jpg') }}" title="Bint Beef" alt="Bint Beef">
                                </a>
                            </div>

                        </div>

                        <div class="content-product-right col-md-7 col-sm-12 col-xs-12">
                            <div class="title-product">
                                <h1>Bint Beef</h1>
                            </div>
                            <div class="product-box-desc">
                                <p><strong>Volume:</strong> 750ML</p>

                                <p><strong>Brand: </strong>Graffigna</p>

                                <p><strong>Category: </strong>Wine / Red Wine</p>

                                <p><strong>Country: </strong>Argentina</p>

                                <p><strong>Alcohol: </strong>13%</p>
                            </div>
                            <div class="product-label form-group">
                                <div class="stock">
                                    <span>Availability:</span> <span class="instock">In Stock</span>
                                    <p>SKU: 3721 -Vlk</p>
                                </div>
                                <div class="product_page_price price" itemprop="offerDetails" itemscope="" itemtype="http://data-vocabulary.org/Offer">
                                    <div class="pr">
                                        <span class="price-new" itemprop="price">$114.00</span>
                                        <span class="price-old">$122.00</span>
                                    </div>
                                    <div class="bx">
                                        <select class="form-control" name="variation" id="">
                                            <option value="default" id="variation" selected disabled>Select Product Variation</option>
                                            <option value="500ml">500ml</option>
                                            <option value="750ml">750ml</option>
                                            <option value="1000 ml">1000ml</option>
                                            <option value="1400ml">1400ml</option>
                                        </select>
                                    </div>

                                </div>

                            </div>
                            <div id="product">
                                <div class="form-group box-info-product">
                                    <div class="option quantity">
                                        <div class="input-group quantity-control" unselectable="on" style="-webkit-user-select: none;">
                                            <label>Qty:  </label>
                                            <input class="form-control" type="text" name="quantity" value="1">
                                            <input type="hidden" name="product_id" value="50">
                                            <span class="input-group-addon product_quantity_down"><i class="fa fa-angle-down" aria-hidden="true"></i></span>
                                            <span class="input-group-addon product_quantity_up"><i class="fa fa-angle-up" aria-hidden="true"></i></span>

                                        </div>
                                    </div>
                                    <div class="info-product-right">
                                        <div class="cart">
                                            <input type="button" data-toggle="tooltip" title="" value="Add to Cart" data-loading-text="Loading..." id="button-cart" class="btn btn-mega btn-lg" onclick="cart.add('42', '1');" data-original-title="Add to Cart">
                                        </div>
                                        <div class="add-to-links wish_comp">
                                            <ul class="blank list-inline">
                                                <li class="wishlist">
                                                    <a class="icon" data-toggle="tooltip" title="" onclick="wishlist.add('50');" data-original-title="Add to Wish List"><i class="fa fa-heart"></i>
                                                    </a>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>


                                </div>

                            </div>
                            <!-- end box info product -->
                            <div class="share">
                                <p>Share This:</p>
                                <div class="share-icon">
                                    <ul>
                                        <li class="facebook"><a href=""><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                        <li class="twitter"><a href=""><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                        <li class="google"><a href=""><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                                        <li class="skype"><a href=""><i class="fa fa-skype" aria-hidden="true"></i></a></li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sx-12">
                            <div class="producttab ">
                                <div class="tabsslider  col-xs-12">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a data-toggle="tab" href="#tab-1">Description</a></li>
                                        <li class="item_nonactive"><a data-toggle="tab" href="#tab-5">Custom Tab</a></li>
                                    </ul>
                                    <div class="tab-content col-xs-12">
                                        <div id="tab-1" class="tab-pane fade active in">
                                            <p>
                                                Our Cabernet Sauvignon was the first wine we made. This is the wine that started it all, setting the exacting standards that we hold ourselves to for all of our varietals. Round and juicy, our Cabernet Sauvignon has flavors of blackberry, toasted hazelnut and cinnamon, complemented by hints of vanilla and toasted oak.<br>
                                                <br>
                                                Our Cabernet Sauvignon was the first wine we made. This is the wine that started it all, setting the exacting standards that we hold ourselves to for all of our varietals. Round and juicy, our Cabernet Sauvignon has flavors of blackberry, toasted hazelnut and cinnamon, complemented by hints of vanilla and toasted oak.<br>
                                                <br>
                                                Our Cabernet Sauvignon was the first wine we made. This is the wine that started it all, setting the exacting standards that we hold ourselves to for all of our varietals. Round and juicy, our Cabernet Sauvignon has flavors of blackberry, toasted hazelnut and cinnamon, complemented by hints of vanilla and toasted oak.<br>
                                                <br>
                                            </p>
                                        </div>
                                        <div id="tab-5" class="tab-pane fade">
                                            <p>
                                                Lorem ipsum dolor sit amet, consetetur
                                                sadipscing elitr, sed diam nonumy eirmod
                                                tempor invidunt ut labore et dolore
                                                magna aliquyam erat, sed diam voluptua.
                                                At vero eos et accusam et justo duo
                                                dolores et ea rebum. Stet clita kasd
                                                gubergren, no sea takimata sanctus est
                                                Lorem ipsum dolor sit amet. Lorem ipsum
                                                dolor sit amet, consetetur sadipscing
                                                elitr, sed diam nonumy eirmod tempor
                                                invidunt ut labore et dolore magna aliquyam
                                                erat, sed diam voluptua. 
                                            </p>
                                            <p>
                                                At vero eos et accusam et justo duo dolores
                                                et ea rebum. Stet clita kasd gubergren,
                                                no sea takimata sanctus est Lorem ipsum
                                                dolor sit amet. Lorem ipsum dolor sit
                                                amet, consetetur sadipscing elitr.
                                            </p>
                                            <p>
                                                Sed diam nonumy eirmod tempor invidunt
                                                ut labore et dolore magna aliquyam erat,
                                                sed diam voluptua. At vero eos et accusam
                                                et justo duo dolores et ea rebum. Stet
                                                clita kasd gubergren, no sea takimata
                                                sanctus est Lorem ipsum dolor sit amet.
                                            </p>
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
@endsection