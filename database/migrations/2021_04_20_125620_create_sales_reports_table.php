<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('vendor_id');
            $table->date('report_date');
            $table->string('vendor_name');
            $table->string('vendor_code')->nullable();
            $table->float('total_sales');
            $table->float('return_refund');
            $table->float('total_net_sales');
            $table->float('commission');
            $table->float('commission_percentage')->default(10);
            $table->float('total_payment_to_vendor');
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('sales_reports');
    }
}
