<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDraftIsDarftMainAdminProductVariaints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_varaints', function (Blueprint $table) {
            $table->boolean('is_draft')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_varaints', function (Blueprint $table) {
            Schema::dropIfExists('product_varaints');
        });
    }
}
