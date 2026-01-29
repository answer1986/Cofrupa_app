<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('process_orders', function (Blueprint $table) {
            $table->string('vehicle_plate', 20)->nullable()->comment('Patente del camión que lleva la fruta');
            $table->date('shipment_date')->nullable()->comment('Fecha de envío a planta');
            $table->time('shipment_time')->nullable()->comment('Horario de envío a planta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('process_orders', function (Blueprint $table) {
            $table->dropColumn(['vehicle_plate', 'shipment_date', 'shipment_time']);
        });
    }
};
