<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->integer('order_item');
            $table->text('subtitles')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('display')->default(1);
            $table->tinyInteger('featured')->default(0);
            $table->tinyInteger('parent_id')->default(0);
            $table->tinyInteger('child')->default(0);
            $table->longText('excerpt')->nullable();
            $table->longText('content')->nullable();
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
        Schema::dropIfExists('pages');
    }
}
