<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithDrawRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('with_draw_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sales_agent_id')->unsigned();
            $table->foreign('sales_agent_id')->references('id')->on('sales_agents')->onDelete('cascade');
            $table->string('amount')->nullable();
            $table->string('status')->default('requested');
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
        Schema::dropIfExists('with_draw_requests');
    }
}
