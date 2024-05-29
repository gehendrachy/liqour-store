@extends('layouts.app')
@section('title',$category->title)
@section('content')
<div class="main-container container">
    <ul class="header-main ">
        <li class="home"><a href="#">Home   </a><i class="fa fa-angle-right" aria-hidden="true"></i></li>
        <li class="home"> {{ $category->title }} <i class="fa fa-angle-right" aria-hidden="true"></i></li>
        <li> Products</li>
    </ul>

    <div class="row">
        @if(count($products) > 0)
         <aside class="col-sm-4 col-md-3 type-1" id="column-left">
            <div class="module menu-category titleLine">
                <div class="modcontent">
                    <h3>Filter Products</h3>
                    <div class="box-category">
                        <ul id="cat_accordion" class="list-group">

                            <li class="hadchild">
                                <span class="button-view ">
                                    <a href="#" class="cutom-parent">Pack Size</a> <i class="fa fa-plus-square-o"></i>
                                </span>
                                <ul style="display: block;">
                                    <?php

                                        // dd($parametersArray);
                                        
                                        function generate_url($filter_key, $ignore_id = 0, $add_id = 0){

                                            $currentUrl = url()->current();
                                            $parametersArray = array();

                                            if (isset($_GET['ps_id'])) {
                                                $parametersArray['ps_id'] = $_GET['ps_id'];
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

                                                    if ($add_id != 0) {
                                                        array_push($parametersArray[$filter_key],$add_id);
                                                    }

                                                }else{
                                                    $pos = array_search($ignore_id, $parametersArray);
                                                    if ($pos !== false) {
                                                        unset($parametersArray[$pos]);
                                                        // $parametersArray = array_values($parametersArray);
                                                    }
                                                    // return $parametersArray;
                                                    if ($add_id != 0) {
                                                        $parametersArray[$filter_key] = $add_id;
                                                    }
                                                }

                                            }else{
                                                if ($filter_key == 'size') {
                                                    $parametersArray[$filter_key] = $add_id;
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




                                    @foreach($unique_product_variations as $pack_size)

                                    <li class="filter-label">
                                        <a href="{{ isset($_GET['ps_id']) && in_array($pack_size->id, $_GET['ps_id']) ? generate_url('ps_id', $pack_size->id) : generate_url('ps_id', 0, $pack_size->id) }}"> 
                                            <input type="checkbox" <?=isset($_GET['ps_id']) && in_array($pack_size->id, $_GET['ps_id']) ? 'checked' : '' ?> name="checks">
                                            {{ $pack_size->title }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>

                            <li class="hadchild">
                                <span class="button-view ">
                                    <a href="#" class="cutom-parent">Size</a> <i class="fa fa-plus-square-o"></i>
                                </span>
                                <ul style="display: block;">
                                    @foreach($unique_product_sub_variations as $size)

                                    <?php 
                                        $currentUrl = url()->current();

                                        if (isset($_GET['ps_id'])) {

                                            for ($i=0; $i < count($_GET['ps_id']); $i++) { 

                                                if ($i==0) {
                                                    $currentUrl .= '?ps_id[]='.$_GET['ps_id'][$i];
                                                }else{
                                                    $currentUrl .= '&ps_id[]='.$_GET['ps_id'][$i];
                                                }
                                            }

                                        }
                                    ?>
                                    <li class="filter-label">
                                        <a href="{{ isset($_GET['size']) && $size->id == $_GET['size'] ? generate_url('size', $size->id) : generate_url('size', 0, $size->id) }}"> 
                                            <input type="radio" <?=isset($_GET['size']) && $size->id == $_GET['size'] ? 'checked' : ''?> name="checks">
                                            {{ $size->title }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                            <li class="hadchild">
                                <span class="button-view ">
                                    <a href="#" class="cutom-parent">Stores</a> <i class="fa fa-plus-square-o"></i>
                                </span>
                                <ul style="display: block;">

                                    @foreach($unique_vendors as $vendor)
                                    <li class="filter-label">

                                        <a href="{{ isset($_GET['stid']) && in_array($vendor->user_id, $_GET['stid']) ? generate_url('stid', $vendor->user_id) : generate_url('stid', 0, $vendor->user_id) }}" style="font-size: 12px;"> 

                                            <input type="checkbox" <?=isset($_GET['stid']) && in_array($vendor->user_id, $_GET['stid']) ? 'checked' : '' ?> name="checks">
                                            {{ $vendor->store->vendor_details->store_name }}

                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>

                            <li class="hadchild">
                                <span class="button-view ">
                                    <a href="#" class="cutom-parent">Brand  </a> <i class="fa fa-plus-square-o"></i>
                                </span>
                                <ul style="display: block;">
                                    
                                    @foreach($unique_product_brands as $prodBrand)

                                    <li class="filter-label">

                                        <a href="{{ isset($_GET['brand']) && in_array($prodBrand->product->brand_id, $_GET['brand']) ? generate_url('brand', $prodBrand->product->brand_id) : generate_url('brand', 0, $prodBrand->product->brand_id) }}" style="font-size: 12px;"> 

                                            <input type="checkbox" <?=isset($_GET['brand']) && in_array($prodBrand->product->brand_id, $_GET['brand']) ? 'checked' : '' ?> name="checks">
                                            {{ $prodBrand->product->product_brand->title }}

                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                            <li class="hadchild">
                                <span class="button-view ">
                                    <a href="#" class="cutom-parent">Price <small style="font-size: 8px;">(updating)</small></a> <i class="fa fa-plus-square-o"></i>
                                </span>
                                <ul style="display: block;">
                                    <li class="filter-label"><a href="#"><input type="checkbox" name="checks"> $1 to $10</a></li>
                                    <li class="filter-label"><a href="#"><input type="checkbox" name="checks">$10 to $20</a></li>
                                    <li class="filter-label"><a href="#"><input type="checkbox" name="checks">$20 to $30</a></li>
                                    <li class="filter-label"><a href="#"><input type="checkbox" name="checks">View All</a></li>
                                </ul>
                            </li>
                            <!-- <li class="hadchild">
                                <span class="button-view ">
                                    <a href="#" class="cutom-parent">Container</a> <i class="fa fa-plus-square-o"></i>
                                </span>
                                <ul style="display: block;">
                                    <li class="filter-label"><a href="#"><input type="checkbox" name="checks"> Bottle</a></li>
                                    <li class="filter-label"><a href="#"><input type="checkbox" name="checks"> Can</a></li>
                                </ul>
                            </li> -->
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
                                @if(isset($_GET['sort']))
                                {{ $products->appends(['sort' => $_GET['sort']])->links() }}
                                @else
                                {{ $products->links() }}
                                @endif
                            </div>
                            <div class="form-group short-by">
                                <label class="control-label" for="input-sort">Sort By:</label>
                                <select id="input-sort" class="form-control" onchange="location = this.value;">

                                    <option value="{{ url()->current() }}" selected="selected">Default </option>
                                    <option value="{{ url()->current().'?sort=alphaAZ' }}">Name (A - Z)</option>
                                    <option value="{{ url()->current().'?sort=alphaZA' }}">Name (Z - A)</option>
                                    <option value="">Price (Low &gt; High)</option>
                                    <option value="">Price (High &gt; Low)</option>
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
                    
                    @foreach($categoryInventoryProducts as $invProd)
                    <div class="product-layout">
                        <div class="product-item-container">
                            <div class="left-block">
                                <div class="product-image-container  second_img ">
                                    <a href="{{ url('product/'.$invProd->product->slug) }}" class="product-img">
                                        <img src="{{ asset('storage/products/'.$invProd->product->slug.'/thumbs/small_'.$invProd->product->image) }}" alt="{{ $invProd->product->slug }}">
                                    </a>
                                    <!--Sale Label-->
                                    <!-- <span class="new">New</span> -->

                                    <div class="hover">
                                        <ul>
                                            <li class="icon-heart"><a class="wishlist" type="button" data-toggle="tooltip" title="" onclick="wishlist.add('42');" data-original-title="Add to WishList"><i class="fa fa-heart"></i></a></li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="right-block">
                                <div class="caption">
                                    <h4><a href="{{ url('product/'.$invProd->product->slug) }}">{{ $invProd->product->product_name }}</a></h4>

                                    <!-- <div class="price">
                                        <span class="price-new">$74.00</span>
                                    </div> -->
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
                                @if(isset($_GET['sort']))
                                {{ $products->appends(['sort' => $_GET['sort']])->links() }}
                                @else
                                {{ $products->links() }}
                                @endif
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
