<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductVariants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->string('status')->nullable();
            $table->string('moderation_status')->nullable();
            $table->text('moderation_status_comment')->nullable();

            $table->bigInteger('provider_variant_id')->nullable();
            $table->string('name')->nullable();
            $table->text('meta')->nullable();
            $table->integer('product_model_id')->unsigned()->nullable();
            $table->foreign('product_model_id')->references('id')->on('product_models')->onDelete('cascade');
            $table->string('print_side', 15)->nullable();
            
            $table->softDeletes();
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
        Schema::drop('product_variants');
    }
}
