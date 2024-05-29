<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Services\ProductPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use App;
use App\User;
use App\Vendor;
use App\Pages;
use App\Slider;
use App\Category;
use App\Product;
use App\InventoryProduct;
use App\ProductVariation;
use App\Variation;
use App\SubVariation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $sliders = Slider::where('status',1)->orderBy('order_item')->get();
        $latProducts = Product::where('display',1)->whereHas('inventory_products')->orderBy('created_at','asc')->get();
        
        // dd($latProducts[6]);

        $featProducts = Product::where([['display',1],['featured',1]])->whereHas('inventory_products')->inRandomOrder()->get();
        return view('home',compact('sliders','latProducts','featProducts'));
    }

    public function store_details($vendor_slug)
    {
        $vendor_details = Vendor::where('slug',$vendor_slug)->firstOrFail(); 
        $vendor_products = $vendor_details->user->inventory_products;
        $vendor_products = collect($vendor_products)->unique('product_id');

        $pageDetails = ProductPaginator::get_current_page();
        $currentPage = $pageDetails['currentPage'];
        $perPage = $pageDetails['perPage'];

        $vendor_products = ProductPaginator::paginate_products($vendor_products, $currentPage, $perPage);

        // dd($vendor_products);

        return view('store-details',compact('vendor_details','vendor_products'));
    }

    public function stores()
    {
        return view('stores');
    }

    public function products()
    {
        return view('products');
    }

    public function contact()
    {
        return view('contact');
    }

    public function login()
    {
        if (!Auth::check()) {

            return view('login');

        }else{

            if(Auth::user()->status == '1') {

                if (Auth::user()->hasRole(['Vendor'])) {

                    return redirect('/vendor/'.Auth::user()->username.'/dashboard')->with('error','Already Logged In as a Vendor!');

                } elseif (Auth::user()->hasRole(['Super Admin'])) {

                    return redirect('/admin')->with('error','Already Logged In!');
                } else {

                    return redirect('/')->with('error','You are Already Logged In as Customer!');
                }
            }else{
                Auth::logout();
            }
            
        }
    }
    public function register()
    {
        if (!Auth::check()) {
            return view('register');
        }else{

            if(Auth::user()->status == '1') {

                if (Auth::user()->hasRole(['Vendor'])) {

                    return redirect('/vendor/'.Auth::user()->username.'/dashboard')->with('error','Already Logged In as a Vendor!');

                } elseif (Auth::user()->hasRole(['Super Admin'])) {

                    return redirect('/admin')->with('error','Already Logged In!');
                } else {

                    return redirect('/')->with('error','You are Already Logged In as Customer!');
                }
            }else{
                Auth::logout();
            }

        }
    }

    public function check_user_availability(Request $request)
    {
        $checkUsernameAvailability = User::where('username', $request->username)->doesntExist();

        if ($checkUsernameAvailability) {
            return 1;
        }else{
            return 0;
        }
    }

    public function check_user_email_availability(Request $request)
    {
        $checkUserEmailAvailability = User::where('email', $request->email)->doesntExist();

        if ($checkUserEmailAvailability) {
            return 1;
        }else{
            return 0;
        }
    }

    public function category_products($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $parent_category = Category::where('id', $category->parent_id)->firstOrFail();
        
        // ============================== <<<<< FILTER OPTIONS >>>>>============================================
        $unique_product_packs = $category->categoryInventoryProducts->pluck('product_variation')->unique('pack')->sortByDesc('pack')->all();

        $unique_product_sizes = $category->categoryInventoryProducts->pluck('product_variation')->unique('size')->sortByDesc('size')->all();

        $unique_product_containers = $category->categoryInventoryProducts->pluck('product_variation')->unique('container')->all();

        // $unique_vendors = collect($category->categoryInventoryProducts)->unique('user_id')->all();
        $unique_vendors = $category->categoryInventoryProducts->pluck('store')->unique('id')->all();

        $unique_product_brands = $category->categoryInventoryProducts->pluck('product')->unique('brand')->all();
        // dd($unique_product_brands);

        // ============================== <<<<< FILTER OPTION ENDS >>>>>========================================

        // dd($unique_vendors);

        // dd($unique_product_packs);

        $pageDetails = ProductPaginator::get_current_page();
        $currentPage = $pageDetails['currentPage'];
        $perPage = $pageDetails['perPage'];

        // dd($products);

        // if (isset($_GET['brand'])) {
        //     $category->categoryInventoryProducts()->whereHas('product',function(Builder $query){
        //                 $query->whereIn('brand',$_GET['brand']);
        //             })->get()
        // }

        if (isset($_GET['ps']) || isset($_GET['size']) || isset($_GET['cType']) || isset($_GET['stid']) || isset($_GET['brand'])) {
 
            $filteredInventoryProducts =  $category->categoryInventoryProducts();

            if (isset($_GET['brand'])) {
                $filteredInventoryProducts = $filteredInventoryProducts->whereHas('product',function(Builder $query){
                            $query->whereIn('brand',$_GET['brand']);
                        });
            }

            $filteredInventoryProducts = $filteredInventoryProducts->whereHas('product_variation', function(Builder $query){

                if (isset($_GET['ps'])) {
                    $query->whereIn('pack', $_GET['ps']);
                }

                if (isset($_GET['size'])) {
                    $query->whereIn('size', $_GET['size']);
                }

                if (isset($_GET['cType'])) {
                    $query->whereIn('container', $_GET['cType']);
                }

                
                // if (isset($_GET['size'])) {
                //     $query->whereIn('variation_id', $packSizeFilterKeys)->where('sub_variation_id',$_GET['size']);
                // }else{
                    
                // }

            });

            if (isset($_GET['stid'])) {
                $filteredInventoryProducts = $filteredInventoryProducts->whereIn('user_id',$_GET['stid']);
            }

            // if (isset($_GET['brand'])) {

            //     $filteredInventoryProducts = $filteredInventoryProducts->whereIn('product.brand',$_GET['brand']);
            // }
            // if (isset($_GET['min_price'])) {
            //     $filteredInventoryProducts = $filteredInventoryProducts->where([['retail_price','>' , $_GET['min_price']],['retail_price','<=' , $_GET['max_price']]]);
            // }

            $filteredInventoryProducts = $filteredInventoryProducts->get();
            // dd($filteredInventoryProducts);
        }else{
            $filteredInventoryProducts = $category->categoryInventoryProducts;
        }

        if (isset($_GET['min_price']) && isset($_GET['max_price'])) {
            $filteredInventoryProducts = collect($filteredInventoryProducts)->whereBetween('retail_price',[$_GET['min_price'],$_GET['max_price']])->all();
        }

        // dd($category->categoryInventoryProducts);
        // dd();
        

        if (@$_GET['sort'] == 'alphaAZ') {

            $categoryInventoryProducts = collect($filteredInventoryProducts)->sortBy('product.product_name')->unique('product_id')->all();

        }elseif (@$_GET['sort'] == 'alphaZA') {

            $categoryInventoryProducts = collect($filteredInventoryProducts)->sortByDesc('product.product_name')->unique('product_id')->all();

        }else{

            $categoryInventoryProducts = collect($filteredInventoryProducts)->unique('product_id')->all();

        }
        

        // $products = ProductPaginator::paginate_products($productsCollection, $currentPage, $perPage);
        

        foreach ($categoryInventoryProducts as $key => $catInvProd) {

            $minPriceCatInvProd = collect($filteredInventoryProducts)->where('product_id',$catInvProd->product_id)->sortBy('retail_price')->first();

            $categoryInventoryProducts[$key] = $minPriceCatInvProd;
            // echo $minPriceCatInvProd->retail_price;
        }

        if (@$_GET['sort'] == 'priceHL') {

            $categoryInventoryProducts = collect($categoryInventoryProducts)->sortByDesc('retail_price')->all();

        }elseif (@$_GET['sort'] == 'priceLH') {

            $categoryInventoryProducts = collect($categoryInventoryProducts)->sortBy('retail_price')->all();

        }

        // dd($categoryInventoryProducts);

        return view('category_products', compact('category', 'products', 'categoryInventoryProducts',
                'unique_product_packs', 'unique_product_sizes', 'unique_product_containers', 'unique_vendors', 'unique_product_brands', 'parent_category'));
    }

    public function parent_category_products($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $child_categories = Category::with('categoryInventoryProducts.product_variation')->where('parent_id',$category->id)->get();
        // dd($child_categories);
        $all_inventory_products = collect($child_categories)->pluck('categoryInventoryProducts')->flatten()->all();
        
        // ============================== <<<<< FILTER OPTIONS >>>>>============================================
        $unique_product_packs = collect($all_inventory_products)->pluck('product_variation')->unique('pack')->sortByDesc('pack')->all();

        $unique_product_sizes = collect($all_inventory_products)->pluck('product_variation')->unique('size')->sortByDesc('size')->all();

        $unique_product_containers = collect($all_inventory_products)->pluck('product_variation')->unique('container')->sortByDesc('container')->all();

        $unique_vendors = collect($all_inventory_products)->pluck('store')->unique('id')->all();
        
        $unique_product_brands = collect($all_inventory_products)->pluck('product')->unique('brand')->all();

        // dd($unique_product_brands);
        // ============================== <<<<< FILTER OPTION ENDS >>>>>========================================

        $pageDetails = ProductPaginator::get_current_page();
        $currentPage = $pageDetails['currentPage'];
        $perPage = $pageDetails['perPage'];


        if (isset($_GET['ps']) || isset($_GET['size']) || isset($_GET['cType']) || isset($_GET['stid']) || isset($_GET['brand'])) {

            $filteredInventoryProducts =  collect($all_inventory_products);

            if (isset($_GET['brand'])) {

                $filteredInventoryProducts =  $filteredInventoryProducts->whereIn('product.brand',$_GET['brand']);
            }


            if (isset($_GET['ps'])) {
                $filteredInventoryProducts =  $filteredInventoryProducts->whereIn('product_variation.pack',$_GET['ps']);
            }

            if (isset($_GET['size'])) {
                $filteredInventoryProducts =  $filteredInventoryProducts->whereIn('product_variation.size',$_GET['size']);
            }

            if (isset($_GET['cType'])) {
                $filteredInventoryProducts =  $filteredInventoryProducts->whereIn('product_variation.container',$_GET['cType']);
            }



            // dd($filteredInventoryProducts->all());

            if (isset($_GET['stid'])) {
                $filteredInventoryProducts = $filteredInventoryProducts->whereIn('user_id',$_GET['stid']);
            }

            $filteredInventoryProducts = $filteredInventoryProducts->all();
            // dd($filteredInventoryProducts);
            
        }else{
            $filteredInventoryProducts = $all_inventory_products;
            
        }

        if (isset($_GET['min_price']) && isset($_GET['max_price'])) {

            $filteredInventoryProducts = collect($filteredInventoryProducts)->whereBetween('retail_price',[$_GET['min_price'],$_GET['max_price']])->all();
        }

        

        if (@$_GET['sort'] == 'alphaAZ') {

            $categoryInventoryProducts = collect($filteredInventoryProducts)->sortBy('product.product_name')->unique('product_id')->all();

        }elseif (@$_GET['sort'] == 'alphaZA') {

            $categoryInventoryProducts = collect($filteredInventoryProducts)->sortByDesc('product.product_name')->unique('product_id')->all();

        }else{

            $categoryInventoryProducts = collect($filteredInventoryProducts)->unique('product_id')->all();

        }
        

        // $products = ProductPaginator::paginate_products($productsCollection, $currentPage, $perPage);
        

        foreach ($categoryInventoryProducts as $key => $catInvProd) {

            $minPriceCatInvProd = collect($filteredInventoryProducts)->where('product_id',$catInvProd->product_id)->sortBy('retail_price')->first();

            $categoryInventoryProducts[$key] = $minPriceCatInvProd;
            // echo $minPriceCatInvProd->retail_price;
        }

        if (@$_GET['sort'] == 'priceHL') {

            $categoryInventoryProducts = collect($categoryInventoryProducts)->sortByDesc('retail_price')->all();

        }elseif (@$_GET['sort'] == 'priceLH') {

            $categoryInventoryProducts = collect($categoryInventoryProducts)->sortBy('retail_price')->all();

        }

        return view('category_products', compact('category', 'categoryInventoryProducts',
                'unique_product_packs', 'unique_product_sizes', 'unique_product_containers', 'unique_vendors', 'unique_product_brands', 'child_categories'));
        // dd($unique_product_brands);
    }

    public function product_details($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $inventory_product_variations = $product->inventory_products()->where('stock','<>',0)->get();
        $inventory_product_variations = collect($inventory_product_variations)->unique('product_variation_id');



        // dd($inventory_product_variations);
        // foreach($inventory_product_variations as $invProdVar){
        //     dd($invProdVar->product_variation);
        // }

        $product_variations = $product->product_variations;
        return view('product_details',compact('product','product_variations','inventory_product_variations'));

    }

    public function get_related_vendors(Request $request)
    {
        $cart = (array)session()->get('cart');
        $cartByVendor = (array)collect($cart)->groupBy('vendor_id')->all();

        $product_variation_id = $request->product_variation_id;

        if($product_variation_id){

            $related_vendors = InventoryProduct::where([['product_variation_id', $product_variation_id],['stock','!=',0]])->orderBy('retail_price')->get();
            $responseText = '';            
            // $responseText ='<select class="custom-select"  name="product_variation_id" required>';

            foreach ($related_vendors as $key => $invVendor) {

                $productImage = asset('storage/products/'.$invVendor->product->slug.'/thumbs/small_'.$invVendor->product_variation->image);

                

                if($invVendor->tax_type == 1){
                    
                    $tax_rate = $invVendor->store->vendor_details->tax_rate_1;
                    
                }elseif($invVendor->tax_type == 2){
                    
                    $tax_rate = $invVendor->store->vendor_details->tax_rate_2;
                    
                }elseif($invVendor->tax_type == 3){
                    
                    $tax_rate = $invVendor->store->vendor_details->tax_rate_3;
                    
                }else{
                    
                    $tax_rate = 0;
                }

                $responseText.= '<div class="part-getnow">
                                <div class="product-label form-group">
                                    <div class="product_page_price price">
                                        <span class="price-new" itemprop="price">$'.$invVendor->retail_price.'</span>';

                                        if ($tax_rate != 0) {
                                            $responseText .= '<p class="pull-right">( +'.$tax_rate .'% tax)</p>';
                                        }
                                        
                                    $responseText .= '</div>
                                    <div class="stock">
                                        <strong>
                                            <span class="instock"><a href="'.route('store_details',['vendor_slug' => $invVendor->store->vendor_details->slug]).'">'.$invVendor->store->vendor_details->store_name.'</a></span>

                                        </strong>
                                        <i style="margin: 0px;">('.date('g:i A',strtotime($invVendor->store->vendor_details->opening_time)).' - '.date('g:i A',strtotime($invVendor->store->vendor_details->closing_time)).')</i>
                                        <p style="margin: 0px;">Minimum Order: <strong>$'.$invVendor->store->vendor_details->minimum_order.'</strong></p>
                                        <p style="margin: 0px;">Delivery Charge: <strong>$'.$invVendor->store->vendor_details->delivery_fee.'</strong></p>';
                                        if (array_key_exists($invVendor->store->id, $cartByVendor)) {
                                            
                                            $cartVendorSubTotal = $cartByVendor[$invVendor->store->id]->sum('cart_subTotal');

                                            $responseText .= '<p style="margin: 0px; color: blue;">You have added '.count($cartByVendor[$invVendor->store->id]) .' items from this store in your cart.</p>';

                                            if ($cartVendorSubTotal < $invVendor->store->vendor_details->minimum_order) {
                                                $remainingTotal = $invVendor->store->vendor_details->minimum_order - $cartVendorSubTotal;
                                                $responseText .= '<p style="margin: 0px; color: red;">Please add $'. $remainingTotal .'  more from this store to meet minimum order.</p>';
                                            }else{
                                                $responseText .= '<p style="margin: 0px; color: green;">Your order qualifies for delivery.</p>';
                                            }
                                        }
                                        
                                    $responseText .= '</div>
                                </div>
                                <div class="product">
                                    <div class="form-group box-info-product">
                                        <div class="option quantity">
                                            <div class="input-group quantity-control" unselectable="off" style="-webkit-user-select: none;">
                                                
                                                <input class="form-control ordered_qty'.$key.'" type="number" name="quantity" value="1" min="1" max="'.$invVendor->stock.'">
                                            </div>
                                        </div>
                                        <div class="info-product-right">
                                            <div class="cart">
                                                <input type="button" data-toggle="tooltip" title="Add to Cart" value="Add to Cart" data-inventory-id="'.$invVendor->id.'" data-product-id="'.$invVendor->product_id.'" data-product-image="'.$productImage.'" data-product-name="'.$invVendor->product->product_name.'" data-product-slug="'.$invVendor->product->slug.'" data-product-variation-id="'.$invVendor->product_variation_id.'" data-vendor-id="'.$invVendor->user_id.'" data-price="'.$invVendor->retail_price.'" data-stock-qty="'.$invVendor->stock.'" data-loading-text="Loading..." data-order-qty-field="ordered_qty'.$key.'" id="button-cart" class="btn btn-mega btn-lg btn-add-to-cart" data-original-title="Add to Cart">
                                            </div>
                                        </div>


                                    </div>

                                </div>
                            </div>';
            }

        }

        return $responseText;
    }

    public function get_states(Request $request)
    {
        $country_id = $request->country_id;
        $state_id = $request->state_id;
        $responseText = "<option value='' disabled selected>Select State/Region</option>";
        
        $states = DB::table('states')->where('country_id', $country_id)->get();
        
        foreach ($states as $stat) {

            if ($stat->id == $state_id) {

                $selectFlag = 'selected';
            }else{
                $selectFlag = '';
            }

            $responseText .= "<option ".$selectFlag." value='".$stat->id."' >".$stat->name."</option>";
        }

        return $responseText;
    }
}
