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
                                    <a href="#" class="cutom-parent">Price</a> <i class="fa fa-plus-square-o"></i>
                                </span>
                                <ul style="display: block;">
                                    <li class="filter-label"><a href="#"><input type="checkbox" name="checks"> $1 to $10</a></li>
                                    <li class="filter-label"><a href="#"><input type="checkbox" name="checks">$10 to $20</a></li>
                                    <li class="filter-label"><a href="#"><input type="checkbox" name="checks">$20 to $30</a></li>
                                    <li class="filter-label"><a href="#"><input type="checkbox" name="checks">View All</a></li>
                                </ul>
                            </li>
                            <li class="hadchild">
                                <span class="button-view ">
                                    <a href="#" class="cutom-parent">Pack Size</a> <i class="fa fa-plus-square-o"></i>
                                </span>
                                <ul style="display: block;">
                                    @foreach($unique_product_variations as $pack_size)

                                    <?php 
                                        $currentUrl = url()->current();

                                        if (isset($_GET['ps_id']) ) {
                                            
                                            $psIdArray = $_GET['ps_id'];
                                            // dd($psIdArray);
                                            if (in_array($pack_size->id, $psIdArray)) {
                                                
                                                $pos = array_search($pack_size->id, $psIdArray);
                                                unset($psIdArray[$pos]);
                                                // dd($pos);

                                            }
                                            // dd(array_values($psIdArray));
                                            $psIdArray = array_values($psIdArray);
                                            // $currentUrl = url()->current();

                                            for ($i=0; $i < count($psIdArray); $i++) { 

                                                if ($i==0) {
                                                    $currentUrl .= '?ps_id[]='.$psIdArray[$i];
                                                }else{
                                                    $currentUrl .= '&ps_id[]='.$psIdArray[$i];
                                                }
                                            }

                                            if (!in_array($pack_size->id, $_GET['ps_id'])) {
                                                if (count($psIdArray)>0) {
                                                    $currentUrl .= '&ps_id[]='.$pack_size->id;
                                                }else{
                                                    $currentUrl .= '?ps_id[]='.$pack_size->id;
                                                }
                                            }

                                        }else{
                                            $currentUrl = url()->full().'?ps_id[]='.$pack_size->id;
                                        }
                                    ?>
                                    <li class="filter-label">
                                        <a href="{{ $currentUrl }}"> 
                                            <input type="checkbox" name="checks">
                                            {{ $pack_size->title }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                            <li class="hadchild">
                                <span class="button-view ">
                                    <a href="#" class="cutom-parent">Container</a> <i class="fa fa-plus-square-o"></i>
                                </span>
                                <ul style="display: block;">
                                    <li class="filter-label"><a href="#"><input type="checkbox" name="checks"> Bottle</a></li>
                                    <li class="filter-label"><a href="#"><input type="checkbox" name="checks"> Can</a></li>

                                    <!-- <li><a href="#">Butter Scotch</a></li>
                                    <li><a href="#">Accessories</a></li> -->
                                </ul>
                            </li>
                            <li class="hadchild">
                                <span class="button-view ">
                                    <a href="#" class="cutom-parent">Size</a> <i class="fa fa-plus-square-o"></i>
                                </span>
                                <ul style="display: block;">
                                    @foreach($unique_product_sub_variations as $size)
                                    <li class="filter-label">
                                        <a href="{{ $size->id }}"> 
                                            <input type="checkbox" name="checks">
                                            {{ $size->title }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                            <li class="hadchild">
                                <span class="button-view ">
                                    <a href="#" class="cutom-parent">Brand</a> <i class="fa fa-plus-square-o"></i>
                                </span>
                                <ul style="display: block;">
                                    <li class="filter-label"><a href="#"><input type="checkbox" name="checks"> Budweiser</a></li>
                                    <li class="filter-label"><a href="#"><input type="checkbox" name="checks"> Corona</a></li>
                                    <li class="filter-label"><a href="#"><input type="checkbox" name="checks"> Bud Light</a></li>
                                </ul>
                            </li>
                            <li class="hadchild">
                                <span class="button-view ">
                                    <a href="#" class="cutom-parent">Stores</a> <i class="fa fa-plus-square-o"></i>
                                </span>
                                <ul style="display: block;">

                                    @foreach($unique_vendors as $vendor)
                                    <li class="filter-label">
                                        <a href="{{ $vendor->store->vendor_details->slug }}" style="font-size: 12px;"> 
                                            <input type="checkbox" name="checks">
                                            {{ $vendor->store->vendor_details->store_name }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                        </ul>
                    </div>


                </div>
            </div>
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
                    @foreach($products as $product)
                    <div class="product-layout">
                        <div class="product-item-container">
                            <div class="left-block">
                                <div class="product-image-container  second_img ">
                                    <a href="{{ url('product/'.$product->slug) }}" class="product-img">
                                        <img src="{{ asset('storage/products/'.$product->slug.'/thumbs/small_'.$product->image) }}" alt="{{ $product->slug }}">
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
                                    <h4><a href="{{ url('product/'.$product->slug) }}">{{ $product->product_name }}</a></h4>

                                    <!-- <div class="price">
                                        <span class="price-new">$74.00</span>
                                    </div> -->
                                    <div class="description item-desc hidden">
                                        {!! $product->short_content !!}
                                    </div>
                                </div>

                                <!-- <div class="button-group">
                                    <button class="addToCart btn btn-default " type="button" data-toggle="tooltip" title="" onclick="add_to_cart('{{ addslashes($product->product_name) }}','{{ $product->slug }}', '{{ $product->image }}');" data-original-title="Add to Cart"> <span class="">Add to Cart</span></button>
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
