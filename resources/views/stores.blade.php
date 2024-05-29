@extends('layouts.app')
@section('title','Store Name')
@section('content')
<div class="main-container container">
    <ul class="header-main ">
        <li class="home"><a href="#">Home   </a><i class="fa fa-angle-right" aria-hidden="true"></i></li>
        <li> The Liquor Tap House</li>
    </ul>

    <div class="row">
        <div class="col-sm-7">
            <div class="about-section">
                <h2>The Liquor Tap House</h2>
                <br>
                <p>Our Cabernet Sauvignon was the first wine we made. This is the wine that started it all, setting the exacting standards that we hold ourselves to for all of our varietals. Round and juicy, our Cabernet Sauvignon has flavors of blackberry, toasted hazelnut and cinnamon, complemented by hints of vanilla and toasted oak.Our Cabernet Sauvignon was the first wine we made. This is the wine that started it all, setting the exacting standards that we hold ourselves to for all of our varietals. Round and juicy, our Cabernet Sauvignon has flavors of blackberry, toasted hazelnut and cinnamon, complemented by hints of vanilla and toasted oak.</p>
                <br>
            </div>
        </div>
        <div class="col-sm-5">
            <img src="{{ asset('frontend/img/files/slider-3.jpg') }}" alt="">
        </div>
        <aside class="col-sm-4 col-md-3 type-2" id="column-left">

            <div class="module latest-product titleLine">
                <div class="modcontent ">
                    <form class="type_2">

                        <div class="table_layout filter-shopby">
                            <div class="table_row">
                                <!-- - - - - - - - - - - - - - Category filter - - - - - - - - - - - - - - - - -->
                                <div class="table_cell" style="z-index: 103;">
                                    <legend>Search</legend>
                                    <input class="form-control" type="text" value="" size="50" autocomplete="off" placeholder="Search" name="search">
                                </div>
                                <!--/ .table_cell -->
                                <!-- - - - - - - - - - - - - - End of category filter - - - - - - - - - - - - - - - - -->
                                <!-- - - - - - - - - - - - - - SUB CATEGORY - - - - - - - - - - - - - - - - -->
                                <div class="table_cell">
                                    <fieldset>
                                        <legend>Sub Category</legend>
                                        <ul class="checkboxes_list">
                                            <li>
                                                <input type="checkbox" checked="" name="category" id="category_1">
                                                <label for="category_1">Once Upon a Bottle</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" name="category" id="category_2">
                                                <label for="category_2">Streetlights Liquor Shop</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" name="category" id="category_3">
                                                <label for="category_3">Liquorty Splitz</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" name="category" id="category_4">
                                                <label for="category_4">Liquor Town</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" name="category" id="category_5">
                                                <label for="category_5">The Liquor Tap</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" name="category" id="category_6">
                                                <label for="category_6">Accessories</label>
                                            </li>

                                        </ul>

                                    </fieldset>

                                </div>
                                <!--/ .table_cell -->
                                <!-- - - - - - - - - - - - - - End SUB CATEGORY - - - - - - - - - - - - - - - - -->
                                <!-- - - - - - - - - - - - - - Manufacturer - - - - - - - - - - - - - - - - -->
                                <div class="table_cell">
                                    <fieldset>
                                        <legend>Manufacturer</legend>
                                        <ul class="checkboxes_list">
                                            <li>
                                                <input type="checkbox" checked="" name="manufacturer" id="manufacturer_1">
                                                <label for="manufacturer_1">Manufacturer 1</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" name="manufacturer" id="manufacturer_2">
                                                <label for="manufacturer_2">Manufacturer 2</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" name="manufacturer" id="manufacturer_3">
                                                <label for="manufacturer_3">Manufacturer 3</label>
                                            </li>

                                        </ul>

                                    </fieldset>

                                </div>
                                <!--/ .table_cell -->
                                <!-- - - - - - - - - - - - - - End manufacturer - - - - - - - - - - - - - - - - -->

                            </div>
                            <!--/ .table_row -->
                            <footer class="bottom_box">
                                <div class="buttons_row">
                                    <button type="submit" class="button_grey button_submit">Search</button>
                                    <button type="reset" class="button_grey filter_reset">Reset</button>
                                </div>
                            </footer>
                        </div><!--/ .table_layout -->
                    </form>
                </div>

            </div>
            <!-- <div class="module tags-product titleLine">
                <h3 class="modtitle">Tags</h3>
                <div class="modcontent ">
                    <ul class="tags_cloud">
                        <li><a href="#" class="button_grey">Lager</a></li>
                        <li><a href="#" class="button_grey">ipa</a></li>
                        <li><a href="#" class="button_grey">belgian ale</a></li>
                        <li><a href="#" class="button_grey">cider</a></li>
                        <li><a href="#" class="button_grey">kegs</a></li>
                        <li><a href="#" class="button_grey">white wine</a></li>
                        <li><a href="#" class="button_grey">red wine</a></li>
                        <li><a href="#" class="button_grey">vodka</a></li>
                        <li><a href="#" class="button_grey">tequila</a></li>
                        <li><a href="#" class="button_grey">gin</a></li>
                        <li><a href="#" class="button_grey">rum</a></li>
                        <li><a href="#" class="button_grey">mixer</a></li>
                        <li><a href="#" class="button_grey">soda</a></li>
                        <li><a href="#" class="button_grey">Champagne</a></li>
                        <li><a href="#" class="button_grey">Whiskey</a></li>

                    </ul>

                </div>

            </div> -->
        </aside>
        <div id="content" class="col-md-9 col-sm-8 type-2">
            <div class="product-category">
                <div class="product-filter filters-panel">
                    <div class="row">
                        <div class="col-md-5 visible-lg">
                            <div class="view-mode">
                                <div class="list-view">
                                    <button class="btn btn-default grid active" data-view="grid" data-toggle="tooltip" data-original-title="Grid"><i class="fa fa-th-large" aria-hidden="true"></i></button>
                                    <button class="btn btn-default list" data-view="list" data-toggle="tooltip" data-original-title="List"><i class="fa fa-th-list"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="short-by-show form-inline text-right col-lg-7 col-sm-12 col-xs-12">
                            <div class="text">
                                <p>item 01 - 16 of 47 total</p>
                            </div>
                            <div class="form-group short-by">
                                <label class="control-label" for="input-sort">Sort By:</label>
                                <select id="input-sort" class="form-control" onchange="location = this.value;">

                                    <option value="" selected="selected">Default </option>
                                    <option value="">Name (A - Z)</option>
                                    <option value="">Name (Z - A)</option>
                                    <option value="">Price (Low &gt; High)</option>
                                    <option value="">Price (High &gt; Low)</option>
                                    <option value="">Rating (Highest)</option>
                                    <option value="">Rating (Lowest)</option>
                                    <option value="">Model (A - Z)</option>
                                    <option value="">Model (Z - A)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <select id="input-limit" class="form-control" onchange="location = this.value;">
                                    <option value="" selected="selected">16</option>
                                    <option value="">25</option>
                                    <option value="">50</option>
                                    <option value="">75</option>
                                    <option value="">100</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="products-list grid">
                    <div class="product-layout">
                        <div class="product-item-container">
                            <div class="left-block">
                                <div class="product-image-container  second_img ">
                                    <a href="{{ url('products/products-name') }}" class="product-img"><img src="{{ asset('frontend/img/files/products/product-1.jpg') }}" alt=""></a>
                                    <!--Sale Label-->
                                    <span class="new">New</span>

                                    <div class="hover">
                                        <ul>
                                            <li class="icon-heart"><a class="wishlist" type="button" data-toggle="tooltip" title="" onclick="wishlist.add('42');" data-original-title="Add to Wish List"><i class="fa fa-heart"></i></a></li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="right-block">
                                <div class="caption">
                                    <h4><a href="{{ url('products/products-name') }}">Dummy product #01</a></h4>

                                    <div class="price">
                                        <span class="price-new">$74.00</span>

                                    </div>
                                    <div class="description item-desc hidden">
                                        <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est . </p>
                                    </div>
                                </div>

                                <div class="button-group">
                                    <button class="addToCart btn btn-default " type="button" data-toggle="tooltip" title="" onclick="cart.add('42', '1');" data-original-title="Add to Cart"> <span class="">Add to Cart</span></button>
                                </div>
                            </div><!-- right block -->
                        </div>
                    </div>


                    <div class="product-layout">
                        <div class="product-item-container">
                            <div class="left-block">
                                <div class="product-image-container  second_img ">
                                    <a href="{{ url('products/products-name') }}" class="product-img"><img src="{{ asset('frontend/img/files/products/product-2.jpg') }}" alt=""></a>
                                    <!--Sale Label-->
                                    <span class="new">New</span>

                                    <div class="hover">
                                        <ul>
                                            <li class="icon-heart"><a class="wishlist" type="button" data-toggle="tooltip" title="" onclick="wishlist.add('42');" data-original-title="Add to Wish List"><i class="fa fa-heart"></i></a></li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="right-block">
                                <div class="caption">
                                    <h4><a href="{{ url('products/products-name') }}">Dummy product #02</a></h4>

                                    <div class="price">
                                        <span class="price-new">$90.00</span>

                                    </div>
                                    <div class="description item-desc hidden">
                                        <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est . </p>
                                    </div>
                                </div>

                                <div class="button-group">
                                    <button class="addToCart btn btn-default " type="button" data-toggle="tooltip" title="" onclick="cart.add('42', '1');" data-original-title="Add to Cart"> <span class="">Add to Cart</span></button>
                                </div>
                            </div><!-- right block -->
                        </div>
                    </div>


                    <div class="product-layout">
                        <div class="product-item-container">
                            <div class="left-block">
                                <div class="product-image-container  second_img ">
                                    <a href="{{ url('products/products-name') }}" class="product-img"><img src="{{ asset('frontend/img/files/products/product-3.jpg') }}" alt=""></a>
                                    <!--Sale Label-->
                                    <span class="new">New</span>

                                    <div class="hover">
                                        <ul>
                                            <li class="icon-heart"><a class="wishlist" type="button" data-toggle="tooltip" title="" onclick="wishlist.add('42');" data-original-title="Add to Wish List"><i class="fa fa-heart"></i></a></li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="right-block">
                                <div class="caption">
                                    <h4><a href="{{ url('products/products-name') }}">Dummy product #03</a></h4>

                                    <div class="price">
                                        <span class="price-new">$45.00</span>

                                    </div>
                                    <div class="description item-desc hidden">
                                        <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est . </p>
                                    </div>
                                </div>

                                <div class="button-group">
                                    <button class="addToCart btn btn-default " type="button" data-toggle="tooltip" title="" onclick="cart.add('42', '1');" data-original-title="Add to Cart"> <span class="">Add to Cart</span></button>
                                </div>
                            </div><!-- right block -->
                        </div>
                    </div>


                    <div class="product-layout">
                        <div class="product-item-container">
                            <div class="left-block">
                                <div class="product-image-container  second_img ">
                                    <a href="{{ url('products/products-name') }}" class="product-img"><img src="{{ asset('frontend/img/files/products/product-4.jpg') }}" alt=""></a>
                                    <!--Sale Label-->

                                    <span class="sale">-25%</span>
                                    <div class="hover">
                                        <ul>
                                            <li class="icon-heart"><a class="wishlist" type="button" data-toggle="tooltip" title="" onclick="wishlist.add('42');" data-original-title="Add to Wish List"><i class="fa fa-heart"></i></a></li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="right-block">
                                <div class="caption">
                                    <h4><a href="{{ url('products/products-name') }}">Dummy product #04</a></h4>

                                    <div class="price">
                                        <span class="price-new">$89.00</span>
                                        <span class="price-old">$132.00</span>
                                    </div>
                                    <div class="description item-desc hidden">
                                        <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est . </p>
                                    </div>
                                </div>

                                <div class="button-group">
                                    <button class="addToCart btn btn-default " type="button" data-toggle="tooltip" title="" onclick="cart.add('42', '1');" data-original-title="Add to Cart"> <span class="">Add to Cart</span></button>
                                </div>
                            </div><!-- right block -->
                        </div>
                    </div>


                    <div class="product-layout">
                        <div class="product-item-container">
                            <div class="left-block">
                                <div class="product-image-container  second_img ">
                                    <a href="{{ url('products/products-name') }}" class="product-img"><img src="{{ asset('frontend/img/files/products/product-6.jpg') }}" alt=""></a>
                                    <!--Sale Label-->


                                    <div class="hover">
                                        <ul>
                                            <li class="icon-heart"><a class="wishlist" type="button" data-toggle="tooltip" title="" onclick="wishlist.add('42');" data-original-title="Add to Wish List"><i class="fa fa-heart"></i></a></li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="right-block">
                                <div class="caption">
                                    <h4><a href="{{ url('products/products-name') }}">Dummy product #05</a></h4>

                                    <div class="price">
                                        <span class="price-new">$95.00</span>

                                    </div>
                                    <div class="description item-desc hidden">
                                        <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est . </p>
                                    </div>
                                </div>

                                <div class="button-group">
                                    <button class="addToCart btn btn-default " type="button" data-toggle="tooltip" title="" onclick="cart.add('42', '1');" data-original-title="Add to Cart"> <span class="">Add to Cart</span></button>
                                </div>
                            </div><!-- right block -->
                        </div>
                    </div>


                    <div class="product-layout">
                        <div class="product-item-container">
                            <div class="left-block">
                                <div class="product-image-container  second_img ">
                                    <a href="{{ url('products/products-name') }}" class="product-img"><img src="{{ asset('frontend/img/files/products/product-7.jpg') }}" alt=""></a>
                                    <!--Sale Label-->


                                    <div class="hover">
                                        <ul>
                                            <li class="icon-heart"><a class="wishlist" type="button" data-toggle="tooltip" title="" onclick="wishlist.add('42');" data-original-title="Add to Wish List"><i class="fa fa-heart"></i></a></li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="right-block">
                                <div class="caption">
                                    <h4><a href="{{ url('products/products-name') }}">Dummy product #06</a></h4>

                                    <div class="price">
                                        <span class="price-new">$34.00</span>

                                    </div>
                                    <div class="description item-desc hidden">
                                        <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est . </p>
                                    </div>
                                </div>

                                <div class="button-group">
                                    <button class="addToCart btn btn-default " type="button" data-toggle="tooltip" title="" onclick="cart.add('42', '1');" data-original-title="Add to Cart"> <span class="">Add to Cart</span></button>
                                </div>
                            </div><!-- right block -->
                        </div>
                    </div>

                    <div class="product-layout">
                        <div class="product-item-container">
                            <div class="left-block">
                                <div class="product-image-container  second_img ">
                                    <a href="{{ url('products/products-name') }}" class="product-img"><img src="{{ asset('frontend/img/files/products/product-7 (2') }}).jpg" alt=""></a>
                                    <!--Sale Label-->

                                    <span class="sale">-25%</span>
                                    <div class="hover">
                                        <ul>
                                            <li class="icon-heart"><a class="wishlist" type="button" data-toggle="tooltip" title="" onclick="wishlist.add('42');" data-original-title="Add to Wish List"><i class="fa fa-heart"></i></a></li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="right-block">
                                <div class="caption">
                                    <h4><a href="{{ url('products/products-name') }}">Dummy product #07</a></h4>

                                    <div class="price">
                                        <span class="price-new">$87.00</span>
                                        <span class="price-old">$109.00</span>
                                    </div>
                                    <div class="description item-desc hidden">
                                        <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est . </p>
                                    </div>
                                </div>

                                <div class="button-group">
                                    <button class="addToCart btn btn-default " type="button" data-toggle="tooltip" title="" onclick="cart.add('42', '1');" data-original-title="Add to Cart"> <span class="">Add to Cart</span></button>
                                </div>
                            </div><!-- right block -->
                        </div>
                    </div>


                    <div class="product-layout">
                        <div class="product-item-container">
                            <div class="left-block">
                                <div class="product-image-container  second_img ">
                                    <a href="{{ url('products/products-name') }}" class="product-img"><img src="{{ asset('frontend/img/files/products/product-8.jpg') }}" alt=""></a>
                                    <!--Sale Label-->

                                    <span class="sale">-25%</span>
                                    <div class="hover">
                                        <ul>
                                            <li class="icon-heart"><a class="wishlist" type="button" data-toggle="tooltip" title="" onclick="wishlist.add('42');" data-original-title="Add to Wish List"><i class="fa fa-heart"></i></a></li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="right-block">
                                <div class="caption">
                                    <h4><a href="{{ url('products/products-name') }}">Dummy product #08</a></h4>

                                    <div class="price">
                                        <span class="price-new">$80.00</span>
                                        <span class="price-old">$98.00</span>
                                    </div>
                                    <div class="description item-desc hidden">
                                        <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est . </p>
                                    </div>
                                </div>

                                <div class="button-group">
                                    <button class="addToCart btn btn-default " type="button" data-toggle="tooltip" title="" onclick="cart.add('42', '1');" data-original-title="Add to Cart"> <span class="">Add to Cart</span></button>
                                </div>
                            </div><!-- right block -->
                        </div>
                    </div>
                    <div class="product-layout">
                        <div class="product-item-container">
                            <div class="left-block">
                                <div class="product-image-container  second_img ">
                                    <a href="{{ url('products/products-name') }}" class="product-img"><img src="{{ asset('frontend/img/files/products/product-9.jpg') }}" alt=""></a>
                                    <!--Sale Label-->
                                    <span class="new">New</span>

                                    <div class="hover">
                                        <ul>
                                            <li class="icon-heart"><a class="wishlist" type="button" data-toggle="tooltip" title="" onclick="wishlist.add('42');" data-original-title="Add to Wish List"><i class="fa fa-heart"></i></a></li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="right-block">
                                <div class="caption">
                                    <h4><a href="{{ url('products/products-name') }}">Dummy product #01</a></h4>

                                    <div class="price">
                                        <span class="price-new">$74.00</span>

                                    </div>
                                    <div class="description item-desc hidden">
                                        <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est . </p>
                                    </div>
                                </div>

                                <div class="button-group">
                                    <button class="addToCart btn btn-default " type="button" data-toggle="tooltip" title="" onclick="cart.add('42', '1');" data-original-title="Add to Cart"> <span class="">Add to Cart</span></button>
                                </div>
                            </div><!-- right block -->
                        </div>
                    </div>

                </div>
                <div class="product-filter filters-panel">
                    <div class="row">
                        <div class="col-md-5 visible-lg">
                            <div class="view-mode">
                                <div class="list-view">
                                    <button class="btn btn-default grid active" data-view="grid" data-toggle="tooltip" data-original-title="Grid"><i class="fa fa-th-large" aria-hidden="true"></i></button>
                                    <button class="btn btn-default list" data-view="list" data-toggle="tooltip" data-original-title="List"><i class="fa fa-th-list"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="short-by-show form-inline text-right col-lg-7 col-sm-12 col-xs-12">
                            <div class="text">
                                <p>item 01 - 16 of 47 total</p>
                            </div>
                            <div class="box-pagination text-right">
                                <ul class="pagination">
                                    <li class="active"><span>1</span></li>
                                    <li><a href="#">2</a></li>
                                    <li><a href="#">3</a></li>
                                    <li><a href="#">4</a></li>
                                    <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection