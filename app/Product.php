<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_name', 'category_id', 'slug', 'image', 'sku', 'order_item', 'featured', 'display', 'short_content', 'long_content', 'brand', 'region', 'abv', 'tasting_notes', 'food_parings', 'suggested_glassware', 'size', 'case_quantity', 'cost_price', 'retail_price', 'is_tax_1', 'is_tax_2', 'is_tax_3', 'is_bottle_deposit_1', 'is_bottle_deposit_2', 'is_foodstamp', 'on_hand', 'sold', 'created_by', 'updated_by'
    ];

    protected $hidden = [
        'created_by', 'updated_by', 'created_at', 'updated_at'
    ];

    public function category()
    {
    	return $this->belongsTo('App\Category');
    }


    public function product_variations()
    {
        return $this->hasMany('App\ProductVariation');
    }

    public function inventory_products()
    {
    	return $this->hasMany('App\InventoryProduct','product_id');
    }

    public function wishlists()
    {
        return $this->hasMany('App\Wishlist','product_id');
    }

    // public function minimum_inventory_product()
    // {
    //     return $this->hasMany('App\InventoryProduct','product_id')->min('retail_price');
    // }

    
}
