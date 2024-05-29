<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('payment_id')->unique();
            $table->integer('sales_report_id');
            $table->string('vendor_id');
            $table->string('vendor_name');
            $table->string('vendor_code')->nullable();
            $table->float('total_amount');
            $table->float('return_refund');
            $table->float('adjustment');
            $table->float('commission');
            $table->float('net_amount');
            $table->float('paid_amount');
            $table->float('due_amount');
            $table->tinyInteger('status')->default(0);
            $table->string('paid_by')->nullable();
            $table->string('created_by');
            $table->string('updated_by');
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
        Schema::dropIfExists('payment_reports');
    }
}
