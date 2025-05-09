<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVaraintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_varaints', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('s_k_u')->nullable();
            $table->string('packing')->nullable();
            $table->string('unit')->nullable();
            $table->string('quantity')->nullable();
            $table->string('price_per_unit')->nullable();
            $table->string('selling_price_per_unit')->nullable();
            $table->string('actual_weight')->nullable();
            $table->string('shipping_weight')->nullable();
            $table->string('shipping_chargeable_weight')->nullable();
            $table->longText('description')->nullable();
            $table->enum('stock_status', [0, 1])->default(0);
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
        Schema::dropIfExists('product_varaints');
    }
}
