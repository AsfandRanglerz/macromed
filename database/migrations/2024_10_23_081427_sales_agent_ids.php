<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SalesAgentIds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agent_wallets', function (Blueprint $table) {
            $table->bigInteger('sales_agent_id')->unsigned();
            $table->foreign('sales_agent_id')->references('id')->on('sales_agents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agent_wallets', function (Blueprint $table) {
            Schema::dropIfExists('agent_wallets');
        });
    }
}
