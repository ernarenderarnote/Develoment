<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_models', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('template_id')->unsigned()->nullable();
            $table->string('inventory_status', 20)->nullable();
            $table->decimal('price_back', 10, 2)->nullabe();
            $table->decimal('price_both', 10, 2)->nullable();
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->integer('product_model_id')->unsigned()->nullable();
            $table->foreign('product_model_id')->references('id')->on('product_models')->onDelete('cascade');
        });
        
        Schema::create('product_model_option_relations', function (Blueprint $table) {
            $table->integer('option_id')->unsigned()->nullable();
            $table->foreign('option_id')->references('id')->on('catalog_attribute_options')->onDelete('cascade');
            
            $table->integer('product_model_id')->unsigned()->nullable();
            $table->foreign('product_model_id')->references('id')->on('product_models')->onDelete('cascade');
        });
        
        Schema::create('product_variant_option_relations', function (Blueprint $table) {
            $table->integer('option_id')->unsigned()->nullable();
            $table->foreign('option_id')->references('id')->on('catalog_attribute_options')->onDelete('cascade');
            
            $table->bigInteger('product_variant_id')->unsigned()->nullable();
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropForeign('product_variants_product_model_id_foreign');
            $table->dropColumn('product_model_id');
        });
        
        Schema::dropIfExists('product_model_option_relations');
        Schema::dropIfExists('product_variant_option_relations');
        Schema::dropIfExists('product_models');
    }
}
