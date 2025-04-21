<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        // Actualiza el campo 'column_to_update' solo si 'text_column' es NULL o vacío
        DB::table('products')
            ->whereNotNull('codigo_barra') // Condición: codigo_barra no es NULL
            ->where('codigo_barra', '!=', '') // Condición: codigo_barra no está vacío
            ->update([
                'product_search' => 'Y', // Valor que deseas asignar
            ]);
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