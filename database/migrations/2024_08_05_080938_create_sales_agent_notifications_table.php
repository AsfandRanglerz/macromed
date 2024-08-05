<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesAgentNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_agent_notifications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sales_agent_id')->unsigned();
            $table->foreign('sales_agent_id')->references('id')->on('sales_agents')->onDelete('cascade')->nullable();
            $table->string('message');
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
        Schema::dropIfExists('sales_agent_notifications');
    }
}
