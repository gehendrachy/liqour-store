<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');             // Store Id
            $table->integer('product_id');
            $table->integer('product_variation_id');    // ProductVariation Table id
            $table->tinyInteger('display')->default(1);
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->integer('stock')->nullable();
            $table->string('size')->nullable();
            $table->string('case_quantity')->nullable();
            $table->float('cost_price')->nullable();
            $table->float('retail_price')->nullable();
            $table->tinyInteger('tax_type');
            $table->tinyInteger('bottle_deposit_type');
            $table->tinyInteger('is_tax_1')->default(0);
            $table->tinyInteger('is_tax_2')->default(0);
            $table->tinyInteger('is_tax_3')->default(0);
            $table->tinyInteger('is_bottle_deposit_1')->default(0);
            $table->tinyInteger('is_bottle_deposit_2')->default(0);
            $table->tinyInteger('is_foodstamp')->default(0);
            $table->tinyInteger('on_hand')->default(0);
            $table->tinyInteger('sold')->default(0);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
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
        Schema::dropIfExists('inventory_products');
    }
}
