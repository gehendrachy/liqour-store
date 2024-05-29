<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');                     // Store Id
            $table->string('store_name');
            $table->string('slug')->unique();
            $table->string('image')->nullable();            // Default Image
            $table->tinyInteger('featured')->default(0);
            $table->tinyInteger('display')->default(1);
            $table->string('address_1')->nullable();
            $table->string('address_2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('opening_time')->nullable();
            $table->string('closing_time')->nullable();
            $table->string('delivery_fee')->nullable();
            $table->string('minimum_order')->nullable();
            $table->float('tax_rate_1')->nullable()->default(0);
            $table->float('tax_rate_2')->nullable()->default(0);
            $table->float('tax_rate_3')->nullable()->default(0);
            $table->float('bottle_deposit_1_rate')->nullable()->default(0);
            $table->float('bottle_deposit_2_rate')->nullable()->default(0);
            $table->float('commission_percentage')->default(10);
            $table->text('description')->nullable();
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
        Schema::dropIfExists('vendors');
    }
}
