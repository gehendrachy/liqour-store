<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ $setting->sitetitle }} | @yield('title')</title>
    <meta charset="utf-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    
    <link rel="shortcut icon" href="{{ asset('storage/setting/favicon/thumb_'.$setting->favicon) }}">
    
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    
    <link href="{{ asset('frontend/css/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/js/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/js/owl-carousel/assets/owl.carousel.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/js/owl-carousel/assets/owl.theme.default.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/themecss/lib.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/js/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet">

    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('frontend/plugins/toastr/toastr.min.css')}}">
    
    <link href="{{ asset('frontend/css/themecss/so_megamenu.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/themecss/so-categories.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/themecss/so-listing-tabs.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/footer2.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/header11.css') }}" rel="stylesheet">
    <link id="color_scheme" href="{{ asset('frontend/css/home2.css')}}" rel="stylesheet">

    <link href="{{ asset('frontend/css/main.css') }}" rel="stylesheet">
    <!-- <link href="css/responsive.css" rel="stylesheet"> -->
    
    
    
</head>

<body class="common-home res layout-home2 banners-effect-7">
    <div id="wrapper" class="wrapper-full">
        <div  class="">

            <!-- Header Container  -->
            <header id="header" class=" variantleft type_11 ">
                <!-- Header Top -->
                <div class="header-top">
                    <div class="container">
                        <div class="row">
                            <div class="header-top-left form-inline  col-sm-6 col-xs-6 compact-hidden">
                                <div class="form-group currencies-block">
                                    <form action="{{ url('/') }}" method="post" enctype="multipart/form-data" id="currency">
                                        <a class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
                                            <span class="icon icon-credit "></span> USD <span class="fa fa-caret-down"></span>
                                        </a>
                                        <ul class="dropdown-menu btn-xs">
                                            <li> <a href="javascript:void(0)">(€)&nbsp;Euro</a></li>
                                            <li> <a href="javascript:void(0)">(£)&nbsp;Pounds</a></li>
                                            <li> <a href="javascript:void(0)">($)&nbsp;USD</a></li>
                                        </ul>
                                    </form>
                                </div>
                                <div class="form-group languages-block ">
                                    <form action="{{ url('/') }}" method="post" enctype="multipart/form-data" id="bt-language">
                                        <a class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
                                            <img src="{{ asset('frontend/img/demo/flags/gb.png') }}" alt="English" title="English">
                                            <span class="hidden-xs">English</span>
                                            <span class="fa fa-caret-down"></span>
                                        </a>
                                        <ul class="dropdown-menu" >
                                            <li><a href="{{ url('/') }}"><img class="image_flag" src="{{ asset('frontend/img/demo/flags/gb.png') }}" alt="English" title="English"> English </a></li>
                                            <li> <a href="html_width_RTL/home11.html"> <img class="image_flag" src="{{ asset('frontend/img/demo/flags/lb.png') }}" alt="Arabic" title="Arabic"> Arabic </a> </li>
                                        </ul>
                                    </form>
                                </div>
                            </div>
                            <div class="header-top-right collapsed-block text-right  col-sm-6 col-xs-6 hidden-xs">

                                <div class="tabBlock" id="TabBlock-1">
                                    <ul class="top-link list-inline">
                                        <li class="my-accout ">
                                            <a href="my-account.html" id="wishlist-total" class="top-link-wishlist" title="My Accout"><span>My Account</span></a>
                                        </li>
                                        <li class="wishlist">
                                            <a href="wishlist.html" title="Wishlist"><span>Wishlist(0)</span></a>
                                        </li>
                                        @if(Auth::check())
                                        <li class="checkout">
                                            <a  class="top-link-checkout" title="Checkout" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span>Log Out</span></a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                                            
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- //Header Top -->

                <!-- Header center -->
                <div class="header-center">
                    <div class="container">
                        <div class="row">
                            <!-- Logo -->
                            <div class="navbar-logo col-lg-5 col-md-4 col-sm-5 col-xs-12">
                                <a href="{{ url('/') }}"><img src="{{ asset('frontend/img/files/logo.png') }}" title="Your Store" alt="Your Store"></a>
                            </div>
                            <!-- //end Logo -->

                            <!-- Search -->
                            <div id="sosearchpro" class=" col-lg-4 col-md-4 col-sm-5 col-xs-12 search-pro">

                                <form method="GET" action="{{ url('/') }}">
                                    <div id="search0" class="search input-group">
                                        <input class="autosearch-input form-control" type="text" value="" size="50" autocomplete="off" placeholder="Enter Your Location To Begin..." name="search">
                                        <button type="submit" class="button-search btn btn-primary" name="submit_search"><i class="fa fa-search" aria-hidden="true"></i></button>
                                    </div>
                                    
                                </form>

                            </div>
                            <!-- //end Search -->

                            <!-- Secondary menu -->
                            <div class="col-lg-3 col-md-4 col-sm-5 col-xs-12 shopping_cart">
                                <!--cart-->
                                <div id="cart" class=" btn-group btn-shopping-cart">
                                    <a data-loading-text="Loading..." class="top_cart dropdown-toggle" data-toggle="dropdown">
                                        <div class="shopcart">
                                            <span class="handle pull-left"></span>
                                            <span class="number-shopping-cart" id="cartCount">{{ count((array)session()->get('cart')) }}</span>
                                        </div>
                                    </a>

                                    <ul class="tab-content content dropdown-menu pull-right shoppingcart-box" role="menu" id="cartQuickView">
                                        @php
                                            $items = array();
                                            $items = (array)session()->get('cart');
                                            $cartTotalPrice = session()->get('total_price');
                                            $totalPrice = 0;
                                            $count =count($items);
                                        @endphp
                                        @if($count > 0)
                                            <li style="overflow-y: scroll; max-height: 320px;">
                                                <table class="table table-striped"  id="cart_product_name">
                                                    <tbody>
                                                        @for ($i=0; $i < $count; $i++) 

                                                            @php
                                                                $cProd = \App\Product::where("id", $items[$i]["product_id"])->first();
                                                                $invProd = \App\InventoryProduct::where('id',$items[$i]["cart_id"])->first();
                                                                $totalPrice += $items[$i]['cart_subTotal'];
                                                            @endphp
                                                            <tr>
                                                                <td class="text-center" style="width:70px">
                                                                    <a href="{{ url('product/'.$cProd->slug) }}">

                                                                        <img src="{{ asset('storage/products/'.$cProd->slug.'/thumbs/thumb_'.$invProd->product_variation->image) }}" style="width:70px" alt="{{ $cProd->product_name }}" title="{{ $cProd->product_name }}" class="preview">

                                                                    </a>
                                                                </td>
                                                                <td class="text-left">
                                                                    <a class="cart_product_name" href="{{ url('product/'.$cProd->slug) }}">
                                                                        <b>{{ $cProd->product_name }}<br>
                                                                            ({{ $invProd->product_variation->variation->title }} - {{ $invProd->product_variation->sub_variation->title }})
                                                                        </b> 
                                                                    </a>

                                                                    <p>${{$items[$i]['cart_subTotal']/$items[$i]['cart_orderedQty']}} x {{$items[$i]['cart_orderedQty']}} </p>
                                                                    <a>{{ $invProd->store->vendor_details->store_name }}</a>
                                                                </td>
                                                                <td class="text-center"><strong> ${{$items[$i]['cart_subTotal']}} </strong> </td>
                                                                <td class="text-right">
                                                                    <a onclick="cartDelete('{{ $i }}','{{ addslashes($cProd->product_name) }}','{{ $cProd->slug }}', '{{ $cProd->image }}')" class="fa fa-times fa-delete"></a>
                                                                </td>
                                                            </tr>
                                                        @endfor
                                                    </tbody>
                                                </table>
                                            </li>
                                            <li>
                                                <div>
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-left"><strong>Sub-Total</strong>
                                                                </td>
                                                                <td class="text-right">${{ $totalPrice }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-left"><strong>Shipping Charge</strong>
                                                                </td>
                                                                <td class="text-right">$0</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-left"><strong>Total</strong>
                                                                </td>
                                                                <td class="text-right">${{ $totalPrice }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <p class="text-right">
                                                        <a class="btn view-cart" href="{{ route('cart') }}">
                                                            <i class="fa fa-shopping-cart"></i>View Cart
                                                        </a>&nbsp;&nbsp;&nbsp; 

                                                        <a class="btn btn-mega checkout-cart" href="javascript:void(0)">
                                                            <i class="fa fa-share"></i>Checkout
                                                        </a> 
                                                    </p>
                                                </div>
                                            </li>
                                        @else
                                            <div class="text-center">
                                                <strong>No Items in the Cart</strong>
                                                <p>Keep Shopping</p>
                                            </div>
                                        @endif
                                    </ul>
                                </div>
                                <!--//cart-->
                                <div class="sign-in pull-right">
                                    <span class="icon-sign-in"></span>
                                    <div class="link">
                                        @if(Auth::check())
                                            @if (Auth::user()->hasRole(['Vendor'])) 

                                                <a href="{{ url('user/my-account') }}">Dashboard</a>
                                            @elseif (Auth::user()->hasRole(['Super Admin'])) 

                                                <a href="{{ url('/admin') }}">Administrator</a>
                                            @else 
                                                <a href="{{ url('/') }}">My Account</a>
                                            @endif
                                            <br>
                                            <span class="welcome">Welcome!</span>
                                            
                                        @else
                                            <a href="{{ url('user/login') }}">Sign In</a>
                                            <span> / </span>
                                            <a href="{{ url('user/register') }}">Register</a>
                                            <br>
                                            <span class="welcome">Welcome Guest</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- //Header center -->
                <div class="header-bottom">
                    <div class="container">
                        <div class="row">


                            <!-- Main menu -->
                            <div class="megamenu-hori header-bottom-left  col-md-12 col-sm-2 col-xs-12 ">
                                <div class="responsive so-megamenu ">
                                    <nav class="navbar-default">
                                        <div class=" container-megamenu  horizontal">

                                            <div class="navbar-header">
                                                <button   id="show-megamenu" data-toggle="collapse" class="navbar-toggle">
                                                    <span class="icon-bar"></span>
                                                    <span class="icon-bar"></span>
                                                    <span class="icon-bar"></span>
                                                </button>
                                            </div>

                                            <div class="megamenu-wrapper">
                                                <span id="remove-megamenu" class="fa fa-times"></span>
                                                <div class="megamenu-pattern">
                                                    <div class="container">
                                                        <ul class="megamenu " data-transition="slide" data-animationtime="250">
                                                            <li class="with-sub-menu hover">
                                                                <p class="close-menu"></p>
                                                                <a  class="clearfix menu1">
                                                                    <strong>Stores</strong>
                                                                    <b class="caret"></b>
                                                                </a>
                                                                <div class="sub-menu" style="width: 50%; right: 0px; display: none;">
                                                                    <div class="content" style="height: 288px; display: none;">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="column">
                                                                                    <a href="javascript:void(0)" class="title-submenu">Featured Stores</a>
                                                                                    <div>
                                                                                        <ul class="row-list">
                                                                                            @php
                                                                                                $vendors = \App\User::role('Vendor')->has('vendor_details')->limit(5)->get();
                                                                                            @endphp
                                                                                            @foreach($vendors as $vendor)
                                                                                                <li><a href="{{ url('store/'.$vendor->vendor_details->slug) }}">{{ $vendor->vendor_details->store_name }}</a></li>
                                                                                            @endforeach
                                                                                        </ul>

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="column">
                                                                                    <a href="javascript:void(0)" class="title-submenu">Our Stores</a>
                                                                                    <div>
                                                                                        <ul class="row-list">
                                                                                             @foreach($vendors as $vendor)
                                                                                                <li><a href="{{ url('store/'.$vendor->vendor_details->slug) }}">{{ $vendor->vendor_details->store_name }}</a></li>
                                                                                            @endforeach
                                                                                        </ul>

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            @php
                                                                $categories =  DB::table('categories')->where([['display', 1],['parent_id', 0],['child', 1]])->select('id','title','parent_id', 'child','category','slug','image')->orderBy('order_item')->get();
                                                            @endphp
                                                            @foreach($categories as $key => $category)
                                                            <li class="with-sub-menu hover">
                                                                <p class="close-menu"></p>
                                                                <a  class="clearfix menu1">
                                                                    <strong>{{$category->title}}</strong>
                                                                    <b class="caret"></b>
                                                                </a>
                                                                <div class="sub-menu" style="width: 40%; right: auto; display: none;">
                                                                    <div class="content" style="height: 160px; display: none;">
                                                                        <div class="row">
                                                                            @php
                                                                                $subCategories =  DB::table('categories')->where([['display', 1],['parent_id', $category->id]])->select('id','title','parent_id', 'child','category','slug')->orderBy('order_item')->limit(4)->get();
                                                                                $subCategories = collect($subCategories);
                                                                                $subCategories = $subCategories->chunk(round($subCategories->count()/2));
                                                                            @endphp
                                                                            @foreach($subCategories as $subCategory)

                                                                            <div class="col-md-6">
                                                                                <ul class="row-list">
                                                                                    @foreach ($subCategory as $key => $subCat)
                                                                                    <li>
                                                                                        <a class="subcategory_item" href="{{ url('categories/'.$subCat->slug) }}">{{ $subCat->title }}</a>
                                                                                    </li>
                                                                                    @endforeach
                                                                                </ul>
                                                                            </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            @endforeach
                                                            <li class="home hover">
                                                                <p class="close-menu"></p>
                                                                <a href="javascript:void(0)" class="menu1">
                                                                    <strong>Order Now</strong>
                                                                </a>
                                                            </li>
                                                            <li class="with-sub-menu hover">
                                                                <p class="close-menu"></p>
                                                                <a  class="clearfix menu1">
                                                                    <strong>Near You</strong>
                                                                    <b class="caret"></b>
                                                                    <img class="label-hot" src="{{ asset('frontend/img/theme/icon/hot-icon.png') }}" alt="icon items">
                                                                    
                                                                </a>
                                                                <div class="sub-menu" style="width: 100%; right: 0px; display: none;">
                                                                    <div class="content" style="height: 398px; display: none;">
                                                                        <div class="row">
                                                                            <div class="col-sm-12">
                                                                                <div class="row">
                                                                                    <div class="col-md-3 img img1">
                                                                                        <a href="{{ url('stores/stores-name') }}"><img src="{{ asset('frontend/img/files/slider/slider-1.jpg') }}" alt="banner1"></a>
                                                                                    </div>
                                                                                    <div class="col-md-3 img img2">
                                                                                        <a href="{{ url('stores/stores-name') }}"><img src="{{ asset('frontend/img/files/slider/slider-2.jpg') }}" alt="banner2"></a>
                                                                                    </div>
                                                                                    <div class="col-md-3 img img3">
                                                                                        <a href="{{ url('stores/stores-name') }}"><img src="{{ asset('frontend/img/files/slider/slider-3.jpg') }}" alt="banner3"></a>
                                                                                    </div>
                                                                                    <div class="col-md-3 img img4">
                                                                                        <a href="{{ url('stores/stores-name') }}"><img src="{{ asset('frontend/img/files/slider/slider-1.jpg') }}" alt="banner4"></a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-3">
                                                                                <a href="{{ url('stores/stores-name') }}" class="title-submenu">The Brown Bag Liquor Shop</a>
                                                                                <div class="row">
                                                                                    <div class="col-md-12 hover-menu">
                                                                                        <div class="menu">
                                                                                            <ul>
                                                                                                <li><a href="{{ url('stores/stores-name') }}" class="main-menu">7422  Ave. North Tonawanda, NY 14120</a></li>
                                                                                            </ul>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <a href="{{ url('stores/stores-name') }}" class="title-submenu">Ye Olde Liquor Shoppe</a>
                                                                                <div class="row">
                                                                                    <div class="col-md-12 hover-menu">
                                                                                        <div class="menu">
                                                                                            <ul>
                                                                                                <li><a href="{{ url('stores/stores-name') }}" class="main-menu">910 Wolcott St, Waterbury CT 6705</a></li>
                                                                                            </ul>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <a href="{{ url('stores/stores-name') }}" class="title-submenu">Cheers Wine and Spirits</a>
                                                                                <div class="row">
                                                                                    <div class="col-md-12 hover-menu">
                                                                                        <div class="menu">
                                                                                            <ul>
                                                                                                <li><a href="{{ url('stores/stores-name') }}" class="main-menu">701 Mcmeans Ave, Bay Minette AL </a></li>
                                                                                            </ul>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <a href="{{ url('stores/stores-name') }}" class="title-submenu">The Tap House</a>
                                                                                <div class="row">
                                                                                    <div class="col-md-12 hover-menu">
                                                                                        <div class="menu">
                                                                                            <ul>
                                                                                                <li><a href="{{ url('stores/stores-name') }}" class="main-menu">250 Hartford Avenue, Bellingham MA</a></li>
                                                                                            </ul>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li class="">
                                                                <p class="close-menu"></p>
                                                                <a href="{{ url('contact') }}" class="clearfix menu1">
                                                                    <strong>Contact</strong>
                                                                    <span class="label"></span>
                                                                </a>
                                                            </li>

                                                            
                                                        </ul>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </nav>
                                </div>
                            </div>
                            <!-- //end Main menu -->
                        </div>
                    </div>

                </div>
            </header>
            <!-- //Header Container  -->

            @yield('content')
            
            <!-- Footer Container -->
            <footer class="footer-container type_footer2">


                <!-- Footer Top Container -->
                
                <div class="footer-mid">
                    <div class="container">
                        <div class="row">
                            <div class=" help">
                                <div class="footer-mid-left col-sm-6 col-xs-12">
                                    <h3>NEED HELP? </h3>
                                    <p>SUPPORT TEAM 24/7 AT (844) 555-8386</p>
                                </div>
                                <div class="footer-mid-right col-sm-6 col-xs-12">
                                    <div class="btn-sub">
                                        <i class="fa fa-envelope" aria-hidden="true"></i>
                                        <input class="autosearch-input form-control" type="text" value="" size="50" autocomplete="off" placeholder="Your email address ..." name="search">
                                        <button type="submit" class="button-search btn btn-primary" name="submit_search">Subscribe</button>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer Mid -->
                <section class="footer-top">
                    <div class="container content">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12">
                                <div class=" collapsed-block ">
                                    <div class="module clearfix">
                                        <h3 class="modtitle">Contact Us </h3>
                                        <div class="modcontent">
                                            <ul class="contact-address">
                                                <li><p><span class="fa fa-home"></span><span>Address : </span> {{ $setting->address }}</p></li>
                                                <li><span class="fa fa-envelope-o"></span><span>Email : </span> <a href="mailto:{{ $setting->siteemail }}"> {{ $setting->siteemail }}</a></li>
                                                <li><p><span class="fa fa-phone"> </span><span>Phone : </span> {{ $setting->phone }}</p> </li>
                                            </ul>
                                        </div>
                                        <div class="share-icon">
                                            <ul>
                                                <li class="facebook"><a href="javascript:void(0)"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                                <li class="twitter"><a href="javascript:void(0)"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                                <li class="google"><a href="javascript:void(0)"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                                                <li class="skype"><a href="javascript:void(0)"><i class="fa fa-skype" aria-hidden="true"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-information">
                                    <div class="module clearfix">
                                        <h3 class="modtitle">Information</h3>
                                        <div class="modcontent">
                                            <ul class="menu">
                                                <li><a href="javascript:void(0)">About Us</a></li>
                                                <li><a href="javascript:void(0)">FAQ</a></li>
                                                <li><a href="javascript:void(0)l">Order history</a></li>
                                                <li><a href="javascript:void(0)">Order information</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class=" box-extras">
                                    <div class="module clearfix">
                                        <h3 class="modtitle">Extras</h3>
                                        <div class="modcontent">
                                            <ul class="menu">
                                                <li><a href="javascript:void(0)">Contact Us</a></li>
                                                <li><a href="javascript:void(0)">Returns</a></li>
                                                <li><a href="javascript:void(0)">Site Map</a></li>
                                                <li><a href="javascript:void(0)">My Account</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="box-account">
                                    <div class="module clearfix">
                                        <h3 class="modtitle">My Account</h3>
                                        <div class="modcontent">
                                            <ul class="menu">
                                                <li><a href="javascript:void(0)">Brands</a></li>
                                                <li><a href="javascript:void(0)">Gift Vouchers</a></li>
                                                <li><a href="javascript:void(0)">Affiliates</a></li>
                                                <li><a href="javascript:void(0)">Specials</a></li>
                                                <li><a href="javascript:void(0)" target="_blank">Our Blog</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- /Footer Top Container -->
                
                
                <div class="footer-bottom-block ">
                    <div class=" container">
                        <div class="row">
                            <div class="footer-bottom-header">
                                <div class="col-xs-12 col-sm-5 download">
                                    <div class="text-footer-bot" style="float:none;">
                                        <p>Coded with <i class="fa fa-heart"></i> by <a href="https://www.ktmrush.com" style="float:none;">KTMRush</a></p>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-7 pay">
                                    <ul>
                                        <li><img src="{{ asset('frontend/img/demo/payment/visa.jpg') }}" alt="#"></li>
                                        <li><img src="{{ asset('frontend/img/demo/payment/meastro.jpg') }}" alt=""></li>
                                        <li><img src="{{ asset('frontend/img/demo/payment/paypal.jpg') }}" alt=""></li>
                                        <li><img src="{{ asset('frontend/img/demo/payment/union.jpg') }}" alt=""></li>
                                        <li><img src="{{ asset('frontend/img/demo/payment/cirrus.jpg') }}" alt=""></li>
                                        <li><img src="{{ asset('frontend/img/demo/payment/ebay.jpg') }}" alt=""></li>
                                    </ul>
                                    <div class="text-footer-bot">
                                        <p>Copyright @2020 - LiquorStore</p>
                                    </div>
                                </div>
                            </div>
                            <div class="back-to-top"><i class="fa fa-angle-up"></i><span> Top </span></div>
                        </div>
                    </div>
                </div>
            </footer>
            
        </div>
        <!-- Social widgets -->
        <!-- End Social widgets -->
    </div>
    
    <!-- Include Libs & Plugins ============================================ -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script type="text/javascript" src="{{ asset('frontend/js/jquery-2.2.4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/owl-carousel/owl.carousel.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/themejs/libs.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/unveil/jquery.unveil.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/countdown/jquery.countdown.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/dcjqaccordion/jquery.dcjqaccordion.2.8.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/datetimepicker/moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/jquery-ui/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/modernizr/modernizr-2.6.2.min.js') }}"></script>

    <!-- Toastr -->
    <script src="{{ asset('frontend/plugins/toastr/toastr.js') }}"></script>
    
    
    <!-- Theme files        ============================================ -->
    <script type="text/javascript" src="{{ asset('frontend/js/themejs/application.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/themejs/homepage.js') }}"></script>
    <!-- <script type="text/javascript" src="js/themejs/toppanel.js"></script> -->
    <script type="text/javascript" src="{{ asset('frontend/js/themejs/so_megamenu.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/themejs/addtocart.js') }}"></script>
    <!-- <script type="text/javascript" src="js/themejs/pathLoader.js"></script>     -->
    
    @stack('post-scripts')  

    <script type="text/javascript">
        toastr.options.timeOut = "4000";
        toastr.options.closeButton = true;
        toastr.options.positionClass = 'toast-top-right';
    </script>
    @if (session('status'))
    <script>
        toastr['success']('{{ session('status') }}', 'Success!');
    </script>
    @elseif (session('success_status'))
    <script>
        toastr['success']('{{ session('success_status') }}');
    </script>
    @elseif (session('error'))

    <script>
        toastr['error']('{{ session('error') }}','Sorry!');
    </script>

    @elseif (session('log_status'))
    <script>
        toastr['error']('{{ session('log_status') }}','');
    </script>

    @elseif (session('log_success'))
    <script>
        toastr['success']('{{ session('log_success') }}','Logged In..');
    </script>

    @elseif (session("parent_status"))
    <script>
        toastr['error']('{{ session("parent_status")["secondary"] }}', '{{ session("parent_status")["primary"] }}');
    </script>

    @endif
    @if ($errors->any())
    @foreach ($errors->all() as $key=>$error)
    <script>
        toastr['error']('{{ $error }}','');
    </script>
    @endforeach
    @endif

    <script type="text/javascript">
        function addItem(inventoryId, productId, productVariationId, vendorId, variationPrice, stock, productSlug, productName, productImage, orderedQty) {

            // custId = custId ? custId : 0;


            totalPrice = orderedQty * variationPrice;

            // console.log('inventoryId :' + inventoryId + ' || productVariationId :' + productVariationId + '|| variationPrice :' + variationPrice + '|| vendorId :' +  vendorId + '|| orderedQty :' + orderedQty + '|| productId :' + productId + '|| stock :' + stock + '|| Total Price :' + totalPrice + '|| product Name :' + productName + '|| product Slug :' + productSlug + '|| product Image :' + productImage);

            cartAdd(inventoryId, productId, productVariationId, vendorId, variationPrice, stock, productSlug, productName, productImage, orderedQty,totalPrice);
        }

        function cartAdd(inventoryId, productId, productVariationId, vendorId, variationPrice, stock, productSlug, productName, productImage, orderedQty,totalPrice) {
            $.ajax({
                url : "{{ URL::route('addToCart') }}",
                type : "POST",
                data :{ '_token': '{{ csrf_token() }}',
                        inventory_id: inventoryId,
                        product_id: productId,
                        product_variation_id: productVariationId,
                        vendor_id: vendorId,
                        variation_price: variationPrice,
                        orderedQty: orderedQty,
                        stock: stock,
                        tPrice: totalPrice
                    },
                beforeSend: function(){                

                },
                success : function(response)
                {
                    console.log("success");
                    console.log("response "+ response);
                    var obj = jQuery.parseJSON( response);

                    if (obj.status=='success') {
                        
                        $('#cartCount').html(obj.totalQty);
                        $('#cartQuickView').load(document.URL + ' #cartQuickView>*');

                        addProductNotice('Product added to Cart', 
                            '<img src="'+productImage+'" alt="'+productName+'">', 
                            '<h3><a href="{{ url("product/")}}/'+productSlug+'">'+productName+'</a> added to <a href="{{ route("cart") }}">Shopping Cart</a>!</h3>', 'success');

                    }else if(obj.status == 'stockerror') {

                        var stock = obj.stock;
                        sweetAlert('Oops! Out of Stock', 'Available stock: ' + stock  , 'error');

                        addProductNotice('Oops! Out of Stock', 
                            '<img src="'+productImage+'" alt="'+productName+'">', 
                            '<h3><a href="{{ url("product/")}}/'+productSlug+'">'+productName+'</a> <br>Available Stock: '+stock+'!</h3>', 'error');
                    };
                }
            });
        }

        function cartDelete(cart_id,title, slug, image) {

            $.ajax({
                url : "{{ URL::route('delete_cart_item') }}",
                type: "POST",
                data: {
                        '_token' : '{{ csrf_token() }}',
                        action: 'delete',
                        id: cart_id
                    },
                beforeSend: function () {

                },
                success: function (response) {
                    console.log("success");
                    console.log("response " + response);

                    var obj = jQuery.parseJSON(response);

                    if (obj.status == 'deleted') {
                        var totalQty = obj.totalQty; 
                     
                        
                            $('#cartCount').html(obj.totalQty);
                            $('#cartQuickView').load(location.href + ' #cartQuickView>*');
                            $('#cartTable').load(document.URL + ' #cartTable>*');

                            addProductNotice('Product Removed from Cart', 
                            '<img src="{{ asset("storage/products/")}}/'+slug+'/thumbs/thumb_'+image+'" alt="'+title+'">', 
                            '<h3><a href="{{ url("product/")}}/'+slug+'">'+title+'</a> removed from <a href="{{ url("cart") }}">Shopping Cart</a>!</h3>', 'error');

                        
                    };
                }
            });
        }

        function add_to_cart(title, slug, image){

            console.log(slug);
            addProductNotice('Product added to Cart', 
                            '<img src="{{ asset("storage/products/")}}/'+slug+'/thumbs/thumb_'+image+'" alt="'+title+'">', 
                            '<h3><a href="{{ url("product/")}}/'+slug+'">'+title+'</a> added to <a href="{{ url("cart") }}">Shopping Cart</a>!</h3>', 'success');
        }
    </script>
    
    
</body>
</html>