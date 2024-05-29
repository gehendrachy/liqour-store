<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_name');
            $table->integer('category_id');
            $table->string('slug')->unique();
            $table->string('image')->nullable(); // Default Image
            $table->string('sku')->nullable();
            $table->integer('order_item');
            $table->tinyInteger('featured')->default(0);
            $table->tinyInteger('display')->default(1);
            $table->text('short_content')->nullable();
            $table->longText('long_content')->nullable();
            $table->string('brand')->nullable();
            $table->string('region')->nullable();
            $table->string('abv')->nullable();
            $table->string('tasting_notes')->nullable();
            $table->string('food_parings')->nullable();
            $table->string('suggested_glassware')->nullable();
            $table->string('size')->nullable();
            $table->string('case_quantity')->nullable();
            $table->float('cost_price')->nullable();
            $table->float('retail_price')->nullable();
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
        Schema::dropIfExists('products');
    }
}
