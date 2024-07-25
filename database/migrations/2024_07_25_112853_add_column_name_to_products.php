<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnNameToProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('product_code')->nullable();
            $table->string('buyer_type')->nullable();
            $table->string('product_class')->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('supplier_id')->nullable();
            $table->string('supplier_delivery_time')->nullable();
            $table->string('delivery_period')->nullable();
            $table->string('self_life')->nullable();
            $table->string('provincial_tax')->nullable();
            $table->string('federal_tax')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
}
