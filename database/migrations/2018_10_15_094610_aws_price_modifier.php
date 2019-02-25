<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AwsPriceModifier extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aws_price_modifier', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('template_id')->unsigned()->nullable();
           
            $table->foreign('template_id')->references('id')->on('product_model_templates')->onDelete('cascade');
           
            $table->integer('size_id')->unsigned()->nullable();  
            
            $table->decimal('customer_price', 10, 2)->nullabe();

            $table->decimal('customer_print_price', 10, 2)->nullabe();

            $table->decimal('brand_price', 10, 2)->nullabe();

            $table->decimal('brand_print_price', 10, 2)->nullabe();

            $table->string('is_white_color')->nullable();

            $table->string('side')->nullable();
            
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
        //
    }
}
