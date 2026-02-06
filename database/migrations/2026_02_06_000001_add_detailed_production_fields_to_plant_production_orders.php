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
        Schema::table('plant_production_orders', function (Blueprint $table) {
            // Descartes separados por tipo
            $table->decimal('discard_humid_kg', 10, 2)->default(0)->after('discard_kg')->comment('Descarte húmedo (kg)');
            $table->decimal('discard_stone_kg', 10, 2)->default(0)->after('discard_humid_kg')->comment('Descarte con carozo (kg)');
            $table->decimal('discard_no_sorbate_kg', 10, 2)->default(0)->after('discard_stone_kg')->comment('Descarte sin sorbato (kg)');
            $table->decimal('discard_other_kg', 10, 2)->default(0)->after('discard_no_sorbate_kg')->comment('Otro descarte (kg)');
            
            // Control de despacho
            $table->decimal('dispatched_kg', 10, 2)->nullable()->after('output_quantity_kg')->comment('Kilos despachados al destino');
            $table->date('dispatch_date')->nullable()->after('dispatched_kg')->comment('Fecha de despacho');
            
            // Inventario en planta (cajas)
            $table->integer('boxes_in_plant')->default(0)->after('dispatch_date')->comment('Número de cajas disponibles en planta');
            $table->decimal('boxes_weight_kg', 10, 2)->nullable()->after('boxes_in_plant')->comment('Peso total de cajas en planta (kg)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plant_production_orders', function (Blueprint $table) {
            $table->dropColumn([
                'discard_humid_kg',
                'discard_stone_kg', 
                'discard_no_sorbate_kg',
                'discard_other_kg',
                'dispatched_kg',
                'dispatch_date',
                'boxes_in_plant',
                'boxes_weight_kg'
            ]);
        });
    }
};
