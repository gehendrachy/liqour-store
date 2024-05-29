<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
// 	return view('welcome');
// });

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('stores/stores-name','HomeController@stores')->name('stores');
Route::get('products/products-name','HomeController@products')->name('products');


Route::get('store/{vendor_slug}','HomeController@store_details')->name('store_details');

Route::get('user/login','HomeController@login')->name('user-login');
Route::get('user/register','HomeController@register')->name('user-register');
Route::post('check-user-availability','HomeController@check_user_availability')->name('users.availability');
Route::post('check-user-email-availability','HomeController@check_user_email_availability')->name('users.email.availability');

Route::get('contact', 'HomeController@contact')->name('contact');
Route::get('categories/{slug}', 'HomeController@category_products')->name('category_products');
Route::get('category/p/{slug}', 'HomeController@parent_category_products')->name('parent_category_products');
Route::get('product/{slug}', 'HomeController@product_details')->name('product_details');
Route::post('get-related-vendors','HomeController@get_related_vendors')->name('get-related-vendors');

Route::post('addToCart','CartController@addToCart')->name('addToCart');
Route::post('update-cart', 'CartController@update_cart')->name('update-cart');
Route::post('delete_cart_item', 'CartController@delete_cart_item')->name('delete_cart_item');

Route::get('shopping-cart','CartController@cart')->name('cart');
Route::post('update-checked-cart-products','CartController@update_checked_cart_products')->name('update-checked-cart-products');
Route::get('checkout','CartController@checkout')->name('checkout');

Route::post('place-order','OrderController@place_order')->name('place-order');

Route::post('get-states','HomeController@get_states')->name('get-states');

Route::name('customer.')->middleware('role:Vendor|Super Admin|Customer')->prefix('user/p')->group(function () {
    Route::get('account','CustomerController@my_account')->name('my-account');
    Route::get('account-settings','CustomerController@account_settings')->name('account-settings');
    Route::post('create-update-information','CustomerController@create_update_information')->name('create-update-information');

    Route::get('orders','CustomerController@orders')->name('orders');
    Route::get('view/order/{order_no}','CustomerController@view_order')->name('view-order');

    Route::post('add-to-wishlist','CustomerController@add_to_wishlist')->name('add-to-wishlist');

    Route::get('wishlist','CustomerController@wishlist')->name('wishlist');
});

Route::get('artisan/{command}', function($command){
    $com = explode('-', $command);
    if (isset($com[0]) && isset($com[1])) {
        \Artisan::call($com[0].':'.$com[1]);
        return redirect()->back()->with('success_status', "Artisan Command executed Successfully!");
    }else{
        return redirect()->back()->with('error', "Invalid Artisan Command, Please check it Properly!");
    }
});

Route::get('/admin/login', function () {

	return view('admin/login');
})->middleware('guest')->name('admin.login');

Route::name('vendor.')->middleware('role:Vendor|Super Admin')->prefix('vendor/{username}')->group(function () {

	Route::get('/dashboard', 'Admin\DashboardController@vendor_dashboard')->name('dashboard');

	// Products
    Route::name('products.')->prefix('products')->group(function () {
        Route::get('/', 'Vendor\ProductController@index')->name('list');
        Route::post('/create', 'Vendor\ProductController@createproduct')->name('create');
        Route::get('/edit/{id}', 'Vendor\ProductController@editproduct');
        Route::post('/update', 'Vendor\ProductController@updateproduct')->name('update');
        Route::get('/delete/{id}', 'Vendor\ProductController@delete')->name('delete');
        Route::post('/set_order', 'Vendor\ProductController@set_order')->name('order_products');

        Route::get('delete-image/{albumName}/{photoName}','Vendor\ProductController@delete_product_gallery_image');

        Route::post('/add_extra_variation_fields','Vendor\ProductController@add_extra_variation_fields')->name('add_extra_variation_fields');
        Route::post('/get_variation_price_fields','Vendor\ProductController@get_variation_price_fields')->name('get_variation_price_fields');
        Route::post('/get_multiple_child_variation','Vendor\ProductController@get_multiple_child_variation')->name('get_multiple_child_variation');
        Route::get('/delete-variation/{id}/flag/{flag}','Vendor\ProductController@delete_variation')->name('delete_variation');
    });


    // Route::get('/add-products','Vendor\ProductController@add_products')->name('add-products');
    // Route::post('/store-products', 'Vendor\ProductController@createproduct')->name('add-products.store');
    // Products
    Route::prefix('add-products')->group(function () {
        Route::get('/', 'Vendor\ProductController@add_products')->name('add-products');
        Route::post('/store', 'Vendor\ProductController@createproduct')->name('add-products.store');
        Route::get('/add-variations/{cIndex}','Vendor\ProductController@add_variations')->name('add-products.add-variations');

        Route::post('/show-sub-variations','Vendor\ProductController@show_sub_variations')->name('add-products.show-sub-variations');

    });

    Route::prefix('add-variations')->group(function () {
        Route::get('/', 'Vendor\VariationController@add_variations')->name('add-variations');
        Route::post('/store', 'Vendor\VariationController@create')->name('add-variations.store');
        Route::get('/addSubVariations/{cIndex}','Vendor\VariationController@add_sub_variations');
    });

    Route::name('vendor-settings.')->prefix('vendor-settings')->group(function () {
        Route::get('/', 'Vendor\VendorSettingController@index')->name('list');
        Route::post('/update', 'Vendor\VendorSettingController@update_vendor_settings')->name('update');
        
    });

    Route::name('vendor-orders.')->prefix('vendor-orders')->group(function () {
        Route::get('/', 'Vendor\VendorOrderController@index')->name('list');
        Route::get('/view/{vendor_order_id}','Vendor\VendorOrderController@view_order')->name('view-order');

        Route::post('/change-status','Vendor\VendorOrderController@change_ordered_product_status')->name('change-ordered-product-status');
        Route::get('/change-vendor-orders-status/order/{vendor_order_id}/status/{status}','Vendor\VendorOrderController@change_vendor_orders_status')->name('change-vendor-orders-status');
        // Route::post('/update', 'Vendor\VendorOrderController@update_vendor_orders')->name('update');
        
    });

    Route::name('inventory-products.')->prefix('inventory-products')->group(function () {
        Route::get('/', 'Vendor\InventoryProductController@index')->name('list');
        Route::post('/create', 'Vendor\InventoryProductController@createproduct')->name('create');
        Route::get('/edit/{id}', 'Vendor\InventoryProductController@editproduct');
        Route::post('/update', 'Vendor\InventoryProductController@updateproduct')->name('update');
        Route::post('/createupdate', 'Vendor\InventoryProductController@create_update_product')->name('create_update');
        Route::get('/delete/{id}', 'Vendor\InventoryProductController@deleteproduct')->name('delete');

        Route::get('/delete-all/{id}', 'Vendor\InventoryProductController@delete_all_inventory_products')->name('delete_all');
        
        Route::post('/set_order', 'Vendor\InventoryProductController@set_order')->name('order_products');

        Route::get('/bulk-edit/{product_id}', 'Vendor\InventoryProductController@editbulkproduct');

        // Route::get('delete-image/{albumName}/{photoName}','Vendor\InventoryProductController@delete_product_gallery_image');

        Route::post('/get_product_variations','Vendor\InventoryProductController@get_product_variations')->name('get_product_variations');
    });
});


Route::name('admin.')->middleware('role:Super Admin')->prefix('admin')->namespace('Admin')->group(function () {

    Route::get('/get-packs','DashboardController@get_packs')->name('get-packs');
	//site setting
	Route::get('/', 'DashboardController@index')->name('dashboard')->middleware('auth');
	Route::get('/vendors', 'DashboardController@vendors')->name('vendors')->middleware('auth');

	Route::get('/setting', 'SettingController@index')->name('setting');
	Route::post('/setting/update', 'SettingController@update')->name('setting.update');

	// Sliders
    Route::name('sliders.')->prefix('sliders')->group(function () {
       Route::get('/', 'SliderController@index')->name('list');
        Route::post('/create', 'SliderController@createslide')->name('create');
        Route::get('/edit/{id}', 'SliderController@editslide');
        Route::post('/update', 'SliderController@updateslide')->name('update');
        Route::get('/delete/{id}', 'SliderController@delete')->name('delete');
        Route::post('/set_order', 'SliderController@set_order')->name('order_sliders');
    });

    // Pages
   	Route::get('/&{slug}', 'PagesController@single');
    Route::name('pages.')->prefix('pages')->group(function () {
        Route::get('/', 'PagesController@index')->name('list');
        Route::post('/create', 'PagesController@create')->name('create');
        Route::get('/edit/{id}', 'PagesController@edit');
        Route::post('/update', 'PagesController@update')->name('update');
        Route::get('/delete/{id}', 'PagesController@delete')->name('delete');
        Route::post('/set_order', 'PagesController@set_order')->name('order_pages');
    });

	// Categories
    Route::name('categories.')->prefix('categories')->group(function () {
        Route::get('/', 'CategoryController@index')->name('list');
        Route::post('/create', 'CategoryController@create')->name('create');
        Route::get('/edit/{id}', 'CategoryController@edit');
        Route::post('/update', 'CategoryController@update')->name('update');
        Route::get('/delete/{id}', 'CategoryController@delete')->name('delete');
        Route::post('/set_order', 'CategoryController@set_order')->name('order_categories');
    });

    // Brands
    Route::name('brands.')->prefix('brands')->group(function () {
        Route::get('/', 'BrandController@index')->name('list');
        Route::post('/create', 'BrandController@create')->name('create');
        Route::get('/edit/{id}', 'BrandController@edit');
        Route::post('/update', 'BrandController@update')->name('update');
        Route::get('/delete/{id}', 'BrandController@delete')->name('delete');
        Route::post('/set_order', 'BrandController@set_order')->name('order_brands');
    });


	Route::name('users.')->prefix('users')->group(function () {
		Route::get('/', 'UsersController@index')->name('list');
		Route::post('/create', 'UsersController@create')->name('create');
		Route::get('/edit/{id}', 'UsersController@edit')->name('edit');
		Route::post('/update', 'UsersController@update')->name('update');
		Route::get('/delete/{id}', 'UsersController@delete')->name('delete');
		Route::post('/check-user-availability','UsersController@check_user_availability')->name('availability');
        Route::post('/check-user-email-availability','UsersController@check_user_email_availability')->name('email.availability');
        Route::get('get_states/{cName}', 'UsersController@get_states')->name('get-states');
	});

    // Variation Routes
    Route::prefix('variations')->group(function () {
        Route::get('/', 'VariationController@index')->name('variations');
        Route::post('/create', 'VariationController@create')->name('variations.create');

        Route::get('/edit/{id}', 'VariationController@edit');
        Route::post('/update', 'VariationController@update')->name('variations.update');

        Route::get('/delete/{id}', 'VariationController@delete')->name('variations.delete');

        Route::get('/addSubVariations/{cIndex}','VariationController@add_sub_variations');
        Route::get('sub_variations/delete/{id}', 'VariationController@delete_sub_variation');

        Route::post('/set_order', 'VariationController@set_order')->name('order_variations');
    });

    // Products
    Route::name('products.')->prefix('products')->group(function () {
        Route::get('/', 'ProductController@index')->name('list');
        Route::post('/create', 'ProductController@createproduct')->name('create');
        Route::get('/edit/{id}', 'ProductController@editproduct');
        Route::post('/update', 'ProductController@updateproduct')->name('update');
        Route::get('/delete/{id}', 'ProductController@delete')->name('delete');
        Route::post('/set_order', 'ProductController@set_order')->name('order_products');

        Route::get('delete-image/{albumName}/{photoName}','ProductController@delete_product_gallery_image');

        Route::get('/add-variations/{cIndex}','ProductController@add_variations')->name('add-variations');

        Route::get('variations/delete/{product_variation_id}', 'ProductController@delete_product_variation');

        Route::post('/show-sub-variations','ProductController@show_sub_variations')->name('show-sub-variations');
    });








    // Sales Reports
    Route::name('sales-reports.')->prefix('sales-reports')->group(function () {
        Route::get('/', 'SalesReportController@index')->name('list');
        Route::post('/create', 'SalesReportController@create')->name('create');
        Route::get('/edit/{id}', 'SalesReportController@edit')->name('edit');
        Route::post('/update', 'SalesReportController@update')->name('update');
        Route::get('/delete/{id}', 'SalesReportController@delete')->name('delete');
        Route::post('/set_order', 'SalesReportController@set_order')->name('order_sales_reports');

        Route::post('/change-status','SalesReportController@change_sales_report_status')->name('change-sales-report-status');
        Route::get('update-vendor-order-calculation','SalesReportController@update_vendor_order_calculation')->name('update-vendor-order-calculation');
        
        Route::post('pay-amount','SalesReportController@pay_amount')->name('pay-amount');
        // Route::get('update-ordered-products-calculation','SalesReportController@update_ordered_products_calculation')->name('update-ordered-products-calculation');
        
    });

    // Payment Reports
    Route::name('payment-reports.')->prefix('payment-reports')->group(function () {
        Route::get('/', 'PaymentReportController@index')->name('list');
        Route::post('/create', 'PaymentReportController@create')->name('create');
        Route::get('/edit/{id}', 'PaymentReportController@edit')->name('edit');
        Route::post('/update', 'PaymentReportController@update')->name('update');
        Route::get('/delete/{id}', 'PaymentReportController@delete')->name('delete');
        Route::post('/set_order', 'PaymentReportController@set_order')->name('order_payment_reports');

        Route::post('/change-status','PaymentReportController@change_payment_report_status')->name('change-payment-report-status');

        Route::post('/get-last-vendor-payment-report','PaymentReportController@get_last_vendor_payment_report')->name('get-last-vendor-payment-report');
    });





	Route::name('roles.')->prefix('roles')->group(function () {
		Route::get('/', 'RoleController@index')->name('index');
		Route::get('/create', 'RoleController@create')->name('create');
		Route::post('/', 'RoleController@store')->name('store');
		Route::get('/{id}/show', 'RoleController@show')->name('show');
		Route::get('/{id}/edit', 'RoleController@edit')->name('edit');
		Route::post('/{id}', 'RoleController@update')->name('update');
		Route::delete('/{id}', 'RoleController@destroy')->name('destroy');
		Route::post('/set-order', 'RoleController@setOrder')->name('setOrder');
	});

});
