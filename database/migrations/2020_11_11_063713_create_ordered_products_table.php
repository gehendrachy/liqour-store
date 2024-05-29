<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordered_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('vendor_id');
            $table->integer('vendor_order_id');
            $table->integer('product_id');
            $table->string('product_title');
            $table->integer('inventory_product_id'); //cart_id
            $table->integer('product_variation_id');
            $table->string('variation_name');
            $table->integer('pack');
            $table->integer('quantity');
            $table->float('sub_total');
            $table->float('tax_rate');
            $table->float('bottle_deposit_rate');
            $table->float('grand_total');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordered_products');
    }
}
