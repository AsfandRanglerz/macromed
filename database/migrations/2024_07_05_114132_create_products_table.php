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
            $table->id();
            $table->string('thumbnail_image');
            $table->string('banner_image')->nullable();
            $table->string('short_name')->nullable();
            $table->string('product_name')->nullable();
            $table->string('slug')->nullable();
            $table->string('country')->nullable();
            $table->string('company')->nullable();
            $table->string('models')->nullable();
            $table->string('video_link')->nullable();
            $table->string('product_commission')->nullable();
            $table->string('product_status')->default('New Product');
            $table->string('short_description')->nullable();
            $table->longText('long_description')->nullable();
            $table->enum('status', [0, 1])->default(0);
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
