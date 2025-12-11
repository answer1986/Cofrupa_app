<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBinMaintenanceFieldsToBinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bins', function (Blueprint $table) {
            $table->string('photo_path')->nullable()->after('status'); // Ruta de la foto del bin
            $table->date('delivery_date')->nullable()->after('photo_path'); // Fecha de entrega al proveedor
            $table->date('return_date')->nullable()->after('delivery_date'); // Fecha de devolución del proveedor
            $table->text('damage_description')->nullable()->after('return_date'); // Descripción de daños
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bins', function (Blueprint $table) {
            $table->dropColumn(['photo_path', 'delivery_date', 'return_date', 'damage_description']);
        });
    }
}
