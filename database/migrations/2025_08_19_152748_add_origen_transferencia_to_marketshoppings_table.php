<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('marketshoppings', function (Blueprint $table) {
            $table->string('origen')->nullable()->default('');
            $table->string('transferencia')->nullable()->default('No');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('marketshoppings', function (Blueprint $table) {
            $table->dropColumn(['origen', 'transferencia']);
        });
    }
};
