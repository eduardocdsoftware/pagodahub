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
        Schema::table('products', function (Blueprint $table) {
            $table->string('id_brand')->default(null)->nullable();
            $table->string('id_category')->default(null)->nullable();
            $table->longText('base64_img')->default('')->nullable(); // Imagen en Base64
            $table->string('presentacion')->default('')->nullable(); // Presentacion del Producto
            $table->string('peso_volumen')->default('')->nullable(); // Peso/Volumen
            $table->string('codigo_barra')->default('')->nullable(); // Codigo de Barra
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};