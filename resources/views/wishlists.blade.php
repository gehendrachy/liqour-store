@extends('layouts.app')
@section('title','Wishlist')
@section('content')
<div class="main-container container">
    <ul class="header-main ">
        <li class="home"><a href="{{ route('home') }}">Home </a><i class="fa fa-angle-right" aria-hidden="true"></i></li>
        <li class="home"><a href="{{ route('customer.my-account') }}">Account </a><i class="fa fa-angle-right" aria-hidden="true"></i></li>
        <li>  Wishlist</li>
    </ul>

    <div class="row">
        <!--Middle Part Start-->
        <div class="col-sm-9 type-2" id="content">
            <h2 class="title">Wishlist</h2>
            <hr>
            <br>
            <p class="lead">Hello, <strong>{{ $customer->name }}!</strong> - <small>This page contains your Wishlist.</small></p>
            <div class="digital">
                <div class="row">
                    <div class=" col-sm-12">
                        <div class="product-category">
                            <div class="products-list grid" id="wishlistItems">
                                @foreach($wishlists as $wishlist)
                                @php
                                    $productImage = asset('storage/products/'.$wishlist->product->slug.'/thumbs/small_'.$wishlist->product->image);
                                @endphp
                                <div class="product-layout">
                                    <div class="product-item-container">
                                        <div class="left-block">
                                            <div class="product-image-container  second_img ">
                                                <a href="{{ url('product/'.$wishlist->product->slug) }}" class="product-img">
                                                    <img src="{{ asset('storage/products/'.$wishlist->product->slug.'/thumbs/small_'.$wishlist->product->image) }}" alt="{{ $wishlist->product->slug }}">
                                                </a>
                                                <!--Sale Label-->
                                                <!-- <span class="new">New</span> -->
                                                @if(Auth::check())
                                                <div class="hover">
                                                    <ul>
                                                        <li class="icon-heart">
                                                            <a class="wishlist btn-add-to-wishlist" data-toggle="tooltip"  data-product-id="{{ $wishlist->product->id }}" data-product-image="{{ $productImage }}" data-product-name="{{ $wishlist->product->product_name }}" data-product-slug="{{ $wishlist->product->slug }}" data-original-title="Add to Wish List"><i class="fa fa-heart"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="right-block">
                                            <div class="caption">
                                                <h4><a href="{{ url('product/'.$wishlist->product->slug) }}">{{ $wishlist->product->product_name }}</a></h4>

                                                @php
                                                    $invProd = $wishlist->product->inventory_products->sortBy('retail_price')->first();

                                                @endphp

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
                                                    {!! $wishlist->product->summary !!}
                                                </div>
                                            </div>

                                        </div><!-- right block -->
                                    </div>
                                </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            
        </div>
        <!--Middle Part End-->
        <!--Right Part Start -->
        <aside class="col-sm-3 hidden-xs" id="column-right">
            <h2 class="subtitle">Account</h2>
            <div class="list-group">
                <ul class="list-item">
                    <li><a href="{{ route('customer.my-account') }}">My Account</a></li>
                    <li><a href="{{ route('customer.account-settings') }}">Account Settings</a>
                    </li>
                    <li>
                        <b>Wishlist</b>
                    </li>
                    <li><a href="{{ route('customer.orders') }}">Order History</a>
                    </li>

                </ul>
            </div>
        </aside>
        <!--Right Part End -->
    </div>
</div>

@endsection

@push('post-scripts')


@endpush