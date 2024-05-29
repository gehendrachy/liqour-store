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
        $latProducts = Product::where('display',1)->orderBy('created_at','asc')->get();
        $featProducts = Product::where([['display',1],['featured',1]])->inRandomOrder()->get();
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

    public function category_products($slug)
    {
        // if (isset($_GET['ps'])) {
        //     dd($_GET);
        // }
        
        // $productVariation = ProductVariation::where('id', 2)->first();
        // $product = $productVariation->product;
        // dd($product);


        // $variationInventoryProducts = $variation->variationInventoryProducts;
        // dd($variationInventoryProducts->unique('product_id')->flatten(1)->sort()->all());


        $category = Category::where('slug', $slug)->firstOrFail();


        // echo "test";
        // dd($filteredInventoryProducts);
        $unique_product_variations = collect($category->categoryInventoryProducts)->pluck('product_variation.variation')->unique('id')->all();

        $unique_product_brands = $category->categoryInventoryProducts->unique('product.brand_id')->all();
        // dd($unique_product_brands[13]->product->product_brand);

        $unique_product_variations_ids = collect($unique_product_variations)->pluck('id')->all();

        $unique_product_sub_variations = collect($category->categoryInventoryProducts)->pluck('product_variation.sub_variation')->unique()->all();
        // dd($unique_product_sub_variations);

        // dd($filteredInventoryProducts);
        $unique_vendors = collect($category->categoryInventoryProducts)->unique('user_id')->all();

        $unique_vendors_ids = collect($category->categoryInventoryProducts)->unique('user_id')->pluck('user_id')->all();

        // dd($unique_vendors_ids);


        if (isset($_GET['stid'])) {
            $storeFilterKeys = $_GET['stid'];
        }else{
            $storeFilterKeys = $unique_vendors_ids;
        }

        if (isset($_GET['ps'])) {
            $packSizeFilterKeys = $_GET['ps'];
        }else{
            $packSizeFilterKeys = $unique_product_variations_ids;
        }
        

        if (isset($_GET['ps']) || isset($_GET['size']) || isset($_GET['container']) || isset($_GET['stid'])) {
            // $variation = Variation::whereIn('id', $_GET['ps'])
            //                         ->whereHas('sub_variation', function(Builder $query){
            //                             $query->where('id',1);
            //                         })
            //                         ->with('variationInventoryProducts')->get();

            $testInventoryProducts = InventoryProduct::with('product')->whereIn('user_id', $storeFilterKeys)
                                            ->whereHas('product_variation', function(Builder $query) use($packSizeFilterKeys){


                                                if (isset($_GET['size'])) {
                                                    $query->whereIn('variation_id', $packSizeFilterKeys)->where('sub_variation_id',$_GET['size']);
                                                }else{
                                                    $query->whereIn('variation_id', $packSizeFilterKeys);
                                                }

                                            })->get();
            // dd($testInventoryProducts);
            $filteredInventoryProducts = collect($testInventoryProducts)->where('product.category_id',$category->id)->unique('product_id')->all();
           
            // dd($testInventoryProducts);
            // $variation = Variation::whereIn('id', $_GET['ps'])
            //                         ->with('variationInventoryProducts')->get();
            // // dd($variation);
            // $filteredInventoryProducts =  collect($variation)->pluck('variationInventoryProducts')->flatten()->where('product.category_id',  $category->id)->unique('product_id')->all();
        }else{
            
            $filteredInventoryProducts = $category->categoryInventoryProducts;
        }


        $categoryInventoryProducts = collect($filteredInventoryProducts)->unique('product_id')->all();

        // $categoryInvProd_uProdVarIds = collect($filteredInventoryProducts)->unique('product_variation_id')->all();
        // dd($categoryInventoryProducts);
        // $subcategories = Category::where('parent_id',$category->parent_id)->orderBy('order_item')->get();

        $pageDetails = ProductPaginator::get_current_page();
        $currentPage = $pageDetails['currentPage'];
        $perPage = $pageDetails['perPage'];


        $productsCollection = array();
        $productsCollection = $category->product()->has('inventory_products')->get();


        if (@$_GET['sort'] == 'alphaAZ') {

            $productsCollection = $category->product()->has('inventory_products')->orderBy('product_name')->get();

        }elseif (@$_GET['sort'] == 'alphaZA') {

            $productsCollection = $category->product()->has('inventory_products')->orderBy('product_name','desc')->get();

        }elseif (@$_GET['sort'] == 'priceLH') {

            $productsCollection = $category->product()->has('inventory_products')->get();

        }elseif (@$_GET['sort'] == 'priceHL') {

            $productsCollection = $category->product()->has('inventory_products')->get();

        }else{

            $productsCollection = $category->product()->has('inventory_products')->where('display',1)->get();

        }

        // dd($productsCollection);

        // $unique_product_variation_ids  = array();
        // $product_vendors = array();

        // foreach ($productsCollection as $key => $product) {
            
        //     $inventory_product_variations = collect($product->inventory_products)->unique('product_variation_id');

        //     // $inventory_vendors = collect($product->inventory_products)->unique('user_id');

        //     $single_product_variation_ids = $inventory_product_variations->pluck('product_variation_id')
        //                                     ->flatten(1)->sort()->all();

        //     // $single_product_vendor_ids = $inventory_vendors->flatten()->all();

        //     array_push($unique_product_variation_ids, $single_product_variation_ids);

        //     array_push($product_vendors, $product->inventory_products);
        // }  
        
        // $unique_product_variation_ids = collect($unique_product_variation_ids)->flatten()->all();    

        // dd($unique_product_variation_ids);
        
        // $unique_product_variations = ProductVariation::with('variation')
        //                                 ->whereIn('id',$unique_product_variation_ids)->get()
        //                                 ->pluck('variation')->sortBy('order_item')
        //                                 ->flatten()->unique('id')->all();

        // dd($unique_product_variations);

        // $unique_product_sub_variations = ProductVariation::with('sub_variation')
        //                                 ->whereIn('id',$unique_product_variation_ids)->get()
        //                                 ->pluck('sub_variation')->flatten()->unique('title')->all();
        // dd($unique_product_sub_variations);

        // $unique_vendors = collect($product_vendors)->flatten()->values()->unique('user_id')->all();

        $products = ProductPaginator::paginate_products($productsCollection, $currentPage, $perPage);
        // dd($products);

        return view('category_products', compact('category', 'products', 'subcategories','unique_product_variations','unique_product_sub_variations','unique_vendors','categoryInventoryProducts','unique_product_brands'));
    }

    public function product_details($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $inventory_product_variations = $product->inventory_products;
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
        $product_variation_id = $request->product_variation_id;

        if($product_variation_id){

            $related_vendors = InventoryProduct::where('product_variation_id', $product_variation_id)->orderBy('retail_price')->get();
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
                                    <div class="product_page_price price" itemprop="offerDetails" itemscope="" itemtype="http://data-vocabulary.org/Offer">
                                        <span class="price-new" itemprop="price">$'.$invVendor->retail_price.'</span>';

                                        if ($tax_rate != 0) {
                                            $responseText .= '<p>( +'.$tax_rate .'% tax)</p>';
                                        }
                                    $responseText .= '</div>
                                    <div class="stock">
                                        <strong><span class="instock"><a href="'.route('store_details',['vendor_slug' => $invVendor->store->vendor_details->slug]).'">'.$invVendor->store->vendor_details->store_name.'</a></span></strong>
                                    </div>
                                </div>
                                <div class="product">
                                    <div class="form-group box-info-product">
                                        <div class="option quantity">
                                            <div class="input-group quantity-control" unselectable="off" style="-webkit-user-select: none;">
                                                
                                                <input class="form-control ordered_qty'.$key.'" type="number" name="quantity" value="1" min="1">
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
}
