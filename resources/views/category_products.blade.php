@extends('layouts.app')
@section('title',$category->title)
@section('content')
<div class="main-container container">
    <ul class="header-main ">

        <li class="home"><a href="{{ url('/') }}">Home   </a><i class="fa fa-angle-right" aria-hidden="true"></i></li>
        @if(isset($parent_category))
        <li class="home"><a href="{{ route('parent_category_products',['slug' => $parent_category->slug]) }}"> {{ $parent_category->title }} </a><i class="fa fa-angle-right" aria-hidden="true"></i></li>
        @endif
        <li class="home"><a href="{{ route('category_products',['slug' => $category->slug]) }}"> {{ $category->title }} </a><i class="fa fa-angle-right" aria-hidden="true"></i></li>

        <li> Products</li>
    </ul>

    <div class="row">
        @if(count($categoryInventoryProducts) > 0)
         <aside class="col-sm-4 col-md-3 type-1" id="column-left">
            <div class="module menu-category titleLine">
                <div class="modcontent">
                    <h3>Filter Products</h3>
                    <div class="box-category">
                        <ul id="filter_accordion" class="list-group">
                            <?php
                                
                                function generate_url($filter_key, $ignore_id = 0, $add_id = 'not-set'){

                                    $currentUrl = url()->current();
                                    $parametersArray = array();

                                    if (isset($_GET['ps'])) {
                                        $parametersArray['ps'] = $_GET['ps'];
                                    }

                                    if (isset($_GET['size'])) {
                                        $parametersArray['size'] = $_GET['size'];
                                    }

                                    if (isset($_GET['stid'])) {
                                        $parametersArray['stid'] = $_GET['stid'];
                                    }

                                    if (isset($_GET['brand'])) {
                                        $parametersArray['brand'] = $_GET['brand'];
                                    }

                                    if (isset($_GET['cType'])) {
                                        $parametersArray['cType'] = $_GET['cType'];
                                    }

                                    if (isset($_GET['min_price'])) {
                                        $parametersArray['min_price'] = $_GET['min_price'];
                                        $parametersArray['max_price'] = $_GET['max_price'];
                                    }

                                    

                                    // return $parametersArray;
                                    if (isset($parametersArray[$filter_key])) {

                                        if (is_array($parametersArray[$filter_key])) {

                                            $pos = array_search($ignore_id, $parametersArray[$filter_key]);
                                            // return $pos;
                                            
                                            if ($pos !== false) {
                                                // return 'abcd';
                                                unset($parametersArray[$filter_key][$pos]);
                                                $parametersArray[$filter_key] = array_values($parametersArray[$filter_key]);
                                            }

                                            if ($add_id != 'not-set') {
                                                array_push($parametersArray[$filter_key],$add_id);
                                            }

                                        }else{
                                            $pos = array_search($ignore_id, $parametersArray);
                                            if ($pos !== false) {
                                                unset($parametersArray[$pos]);
                                                // $parametersArray = array_values($parametersArray);
                                            }
                                            // return $parametersArray;
                                            if ($add_id != 'not-set') {
                                                $parametersArray[$filter_key] = $add_id;
                                            }
                                        }

                                    }else{
                                        
                                        if ($filter_key == 'price' ) {
                                            if (isset($_GET['min_price']) && $_GET['min_price'] == $ignore_id) {
                                                unset($parametersArray['min_price']);
                                                unset($parametersArray['max_price']);
                                            }else{
                                                $parametersArray['min_price'] = $ignore_id;
                                                $parametersArray['max_price'] = $add_id;
                                            }
                                            
                                            
                                        }else{
                                            $parametersArray[$filter_key][] = $add_id;
                                        }

                                    }

                                    $counter = 0;
                                    foreach ($parametersArray as $key => $par) {

                                        if (is_array($par)) {

                                            for ($i=0; $i < count($par); $i++) { 
                                                if ($counter == 0) {
                                                    $currentUrl .= '?'.$key.'[]='.$par[$i];
                                                }else{
                                                    $currentUrl .= '&'.$key.'[]='.$par[$i];
                                                }
                                                $counter++;
                                            }

                                        }else{

                                            if ($counter == 0) {
                                                $currentUrl .= '?'.$key.'='.$par;
                                            }else{
                                                $currentUrl .= '&'.$key.'='.$par;
                                            }
                                            $counter++;

                                        }
                                    }

                                    

                                    return $currentUrl;
                                }
                            ?>
                            <li class="hadchild inactive {{ isset($_GET['ps']) ? 'active' : '' }}">
                                <span class="button-view ">
                                    <a href="javascript:void(0)" class="cutom-parent">Pack Size</a> <i class="fa fa-plus-square-o"></i>
                                </span>
                                <ul style="display: block;">
                                    @foreach($unique_product_packs as $prodPack)
                                    <li class="filter-label">
                                        <a href="{{ isset($_GET['ps']) && in_array($prodPack->pack, $_GET['ps']) ? generate_url('ps', $prodPack->pack) : generate_url('ps', 'not-set', $prodPack->pack) }}"> 

                                            <input type="checkbox" <?=isset($_GET['ps']) && in_array($prodPack->pack, $_GET['ps']) ? 'checked' : '' ?> name="checks">

                                            {{ $prodPack->pack != 1 ? $prodPack->pack.'-Pack' : 'Singles' }}

                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>

                            <li class="hadchild inactive {{ isset($_GET['size']) ? 'active' : '' }}">
                                <span class="button-view ">
                                    <a href="javascript:void(0)" class="cutom-parent">Size</a> <i class="fa fa-plus-square-o"></i>
                                </span>
                                <ul style="display: block;">
                                    @foreach($unique_product_sizes as $prodSize)

                                    <li class="filter-label">
                                        <a href="{{ isset($_GET['size']) && in_array($prodSize->size, $_GET['size']) ? generate_url('size', $prodSize->size) : generate_url('size', 'not-set', $prodSize->size) }}"> 

                                            <input type="checkbox" <?=isset($_GET['size']) && in_array($prodSize->size ,$_GET['size']) ? 'checked' : ''?> name="checks">

                                            {{ $prodSize->size }}

                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>

                             <li class="hadchild inactive {{ isset($_GET['cType']) ? 'active' : '' }}">
                                <span class="button-view ">
                                    <a href="javascript:void(0)" class="cutom-parent">Container</a> <i class="fa fa-plus-square-o"></i>
                                </span>
                                <ul style="display: block;">
                                    @foreach($unique_product_containers as $prodCont)
                                    <li class="filter-label">
                                        <a href="{{ isset($_GET['cType']) && in_array($prodCont->container, $_GET['cType']) ? generate_url('cType', $prodCont->container) : generate_url('cType', 'not-set', $prodCont->container) }}"> 

                                            <input type="checkbox" <?=isset($_GET['cType']) && in_array($prodCont->container, $_GET['cType']) ? 'checked' : '' ?> name="checks">

                                            {{ $prodCont->container }}

                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>

                            <li class="hadchild inactive {{ isset($_GET['stid']) ? 'active' : '' }}">
                                <span class="button-view ">
                                    <a href="javascript:void(0)" class="cutom-parent">Stores</a> <i class="fa fa-plus-square-o"></i>
                                </span>
                                <ul style="display: block;">

                                    @foreach($unique_vendors as $vendor)
                                    <li class="filter-label">

                                        <a href="{{ isset($_GET['stid']) && in_array($vendor->id, $_GET['stid']) ? generate_url('stid', $vendor->id) : generate_url('stid', 'not-set', $vendor->id) }}" style="font-size: 12px;"> 

                                            <input type="checkbox" <?=isset($_GET['stid']) && in_array($vendor->id, $_GET['stid']) ? 'checked' : '' ?> name="checks">
                                            {{ $vendor->vendor_details->store_name }}

                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>

                            <li class="hadchild inactive {{ isset($_GET['brand']) ? 'active' : '' }}">
                                <span class="button-view ">
                                    <a href="javascript:void(0)" class="cutom-parent">Brands</a> <i class="fa fa-plus-square-o"></i>
                                </span>
                                <ul style="display: block;">

                                    @foreach($unique_product_brands as $product)
                                    <li class="filter-label">

                                        <a href="{{ isset($_GET['brand']) && in_array($product->brand, $_GET['brand']) ? generate_url('brand', $product->brand) : generate_url('brand', 'not-set', $product->brand) }}" style="font-size: 12px;"> 

                                            <input type="checkbox" <?=isset($_GET['brand']) && in_array($product->brand, $_GET['brand']) ? 'checked' : '' ?> name="checks">
                                            {{ $product->brand }}

                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>

                            
                            <li class="hadchild inactive {{ isset($_GET['min_price']) ? 'active' : '' }}">
                                <span class="button-view ">
                                    <a href="javascript:void(0)" class="cutom-parent">Price </a> <i class="fa fa-plus-square-o"></i>
                                </span>
                                <ul style="display: block;">
                                    <li class="filter-label">
                                        <a href="{{ generate_url('price', 1, 10) }}">
                                            <input type="radio" <?=isset($_GET['min_price']) && $_GET['min_price'] ==  1 ? 'checked' : '' ?> name="checks"> 
                                            $1 to $10
                                        </a>
                                    </li>
                                    <li class="filter-label">
                                        <a href="{{ generate_url('price', 10, 20) }}">
                                            <input type="radio" <?=isset($_GET['min_price']) && $_GET['min_price'] == 10 ? 'checked' : '' ?> name="checks"> 
                                            $10 to $20
                                        </a>
                                    </li>
                                    <li class="filter-label">
                                        <a href="{{ generate_url('price', 20, 30) }}">
                                            <input type="radio" <?=isset($_GET['min_price']) && $_GET['min_price'] == 20 ? 'checked' : '' ?> name="checks"> 
                                            $20 to $30
                                        </a>
                                    </li>
                                    <li class="filter-label">
                                        <a href="{{ generate_url('price', 30, 40) }}">
                                            <input type="radio" <?=isset($_GET['min_price']) && $_GET['min_price'] == 30 ? 'checked' : '' ?> name="checks"> 
                                            $30 to $40
                                        </a>
                                    </li>
                                    <li class="filter-label">
                                        <a href="{{ generate_url('price', 40, 50) }}">
                                            <input type="radio" <?=isset($_GET['min_price']) && $_GET['min_price'] == 40 ? 'checked' : '' ?> name="checks"> 
                                            $40 to $50
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @if(isset($child_categories))
                            <li class="hadchild inactive">
                                <span class="button-view ">
                                    <a href="javascript:void(0)" class="cutom-parent">Category</a> <i class="fa fa-plus-square-o"></i>
                                </span>
                                <ul style="display: block;">
                                    @foreach($child_categories as $childCat)
                                    <li class="filter-label">
                                        <a href="{{ route('category_products',['slug' => $childCat->slug]) }}"><input type="checkbox" name="checks"> {{ $childCat->title }}</a>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                            @endif
                        </ul>
                    </div>


                </div>
            </div>
        </aside>
        <div id="content" class="col-md-9 col-sm-8 type-2">
            @if(count($categoryInventoryProducts) > 0)
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
                            <div class="box-pagination text-right">
                                
                            </div>
                            <div class="form-group short-by">
                                <label class="control-label" for="input-sort">Sort By:</label>
                                <select id="input-sort" class="form-control" onchange="location = this.value;">

                                    <option value="{{ url()->current() }}" <?=Request::get('sort') == '' ? 'selected' : '' ?>>Default </option>
                                    <option value="{{ url()->current().'?sort=alphaAZ' }}" <?=Request::get('sort') == 'alphaAZ' ? 'selected' : '' ?>>Name (A - Z)</option>
                                    <option value="{{ url()->current().'?sort=alphaZA' }}" <?=Request::get('sort') == 'alphaZA' ? 'selected' : '' ?>>Name (Z - A)</option>
                                    <option value="{{ url()->current().'?sort=priceLH' }}" <?=Request::get('sort') == 'priceLH' ? 'selected' : '' ?>>Price (Low &gt; High)</option>
                                    <option value="{{ url()->current().'?sort=priceHL' }}" <?=Request::get('sort') == 'priceHL' ? 'selected' : '' ?>>Price (High &gt; Low)</option>
                                    <!-- <option value="">Rating (Highest)</option>
                                    <option value="">Rating (Lowest)</option>
                                    <option value="">Model (A - Z)</option>
                                    <option value="">Model (Z - A)</option> -->
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="products-list grid">
                    @php $counter = 0 @endphp
                    @foreach($categoryInventoryProducts as $invProd)
                    @php
                        $productImage = asset('storage/products/'.$invProd->product->slug.'/thumbs/small_'.$invProd->product_variation->image)
                    @endphp
                    <div class="product-layout">
                        <div class="product-item-container">
                            <div class="left-block">
                                <div class="product-image-container  second_img ">
                                    <a href="{{ url('product/'.$invProd->product->slug) }}" class="product-img">
                                        <img src="{{ asset('storage/products/'.$invProd->product->slug.'/thumbs/small_'.$invProd->product_variation->image) }}" alt="{{ $invProd->product->slug }}">
                                        <!-- <img src="{{ asset('storage/products/'.$invProd->product->slug.'/thumbs/small_'.$invProd->product->image) }}" alt="{{ $invProd->product->slug }}"> -->
                                    </a>
                                    <!--Sale Label-->
                                    <!-- <span class="new">New</span> -->

                                    @if(Auth::check())
                                    <div class="hover">
                                        <ul>
                                            <li class="icon-heart">
                                                <a class="wishlist btn-add-to-wishlist" data-toggle="tooltip"  data-product-id="{{ $invProd->product->id }}" data-product-image="{{ $productImage }}" data-product-name="{{ $invProd->product->product_name }}" data-product-slug="{{ $invProd->product->slug }}" data-original-title="Add to Wish List"><i class="fa fa-heart"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="right-block">
                                <div class="caption">
                                    <h4>
                                        <a href="{{ url('product/'.$invProd->product->slug) }}">
                                            {{ $invProd->product->product_name }}
                                        </a>
                                    </h4>

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
                                        {!! $invProd->product->short_content !!}
                                    </div>
                                </div>

                                <!-- <div class="button-group">
                                    <button class="addToCart btn btn-default " type="button" data-toggle="tooltip" title="" onclick="add_to_cart('{{ addslashes($invProd->product->product_name) }}','{{ $invProd->product->slug }}', '{{ $invProd->product->image }}');" data-original-title="Add to Cart"> <span class="">Add to Cart</span></button>
                                </div> -->
                            </div><!-- right block -->
                        </div>
                    </div>

                    @if( ($counter+1)%4 == 0)
                        <div class="clearfix"></div>
                    @endif
                    @php $counter++; @endphp
                    @endforeach


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
                            
                            <div class="box-pagination text-right">
                                
                                <!-- <ul class="pagination">
                                    <li class="active"><span>1</span></li>
                                    <li><a href="#">2</a></li>
                                    <li><a href="#">3</a></li>
                                    <li><a href="#">4</a></li>
                                    <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
                                </ul> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="col-sm-12 col-xs-12 text-center alert alert-danger">
                <h3>Sorry No Products Available!! </h3>
                <p>SUPPORT TEAM 24/7 AT {{ $setting->phone }}</p>
            </div>
            @endif
        </div>
        @else
        <div class="col-sm-12 col-xs-12 text-center alert alert-danger">
            <h3>Sorry No Products Available!! </h3>
            <p>SUPPORT TEAM 24/7 AT {{ $setting->phone }}</p>
        </div>
        @endif
    </div>

</div>
@endsection
