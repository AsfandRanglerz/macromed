<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('sales_agent_id')->unsigned();
            $table->foreign('sales_agent_id')->references('id')->on('sales_agents')->onDelete('cascade');
            $table->decimal('product_commission')->nullable();
            $table->decimal('total')->nullable();
            $table->string('address')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('card_number')->nullable();
            $table->string('card_date')->nullable();
            $table->string('cvc')->nullable();
            $table->string('status');
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
        Schema::dropIfExists('orders');
    }
}
