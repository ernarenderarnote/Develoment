<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductGlobalpricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_globalprices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('template_id')->unsigned()->nullable();
            $table->foreign('template_id')->references('id')->on('product_model_templates')->onDelete('cascade');
            $table->string('side')->nullable();  
            $table->string('color')->nullable();
            $table->string('type')->nullable();
            $table->string('size')->nullable();
            $table->decimal('price', 10, 2)->nullabe();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_globalprices');
    }
}
