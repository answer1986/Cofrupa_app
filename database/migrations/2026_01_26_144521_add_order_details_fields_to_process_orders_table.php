<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderDetailsFieldsToProcessOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('process_orders', function (Blueprint $table) {
            $table->string('raw_material')->nullable()->after('product_description'); // Materia Prima
            $table->string('product')->nullable()->after('raw_material'); // Producto
            $table->string('type')->nullable()->after('product'); // Tipo (SIN CAROZO, etc.)
            $table->string('caliber')->nullable()->after('type'); // Calibre (EX 60/70, etc.)
            $table->string('quality')->nullable()->after('caliber'); // Calidad (GRADO 1 USDA A CAT-1, etc.)
            $table->string('labeling')->nullable()->after('quality'); // Etiquetado (ADJUNTA, etc.)
            $table->string('packaging')->nullable()->after('labeling'); // Envases (CAIAS 10 KLS COFRUPA, etc.)
            $table->string('potassium_sorbate')->nullable()->after('packaging'); // Sorbato de potasio (800 PPM MAX, etc.)
            $table->string('humidity')->nullable()->after('potassium_sorbate'); // Humedad (30% +-1 máximo, etc.)
            $table->string('stone_percentage')->nullable()->after('humidity'); // % de Carozo (0,5% Máx, etc.)
            $table->string('oil')->nullable()->after('stone_percentage'); // Aceite (SIN ACEITE, etc.)
            $table->string('damage')->nullable()->after('oil'); // Daños (5,0 % MAXIMO, etc.)
            $table->string('plant_print')->nullable()->after('damage'); // Impresión Planta (ADJUNTA, etc.)
            $table->string('destination')->nullable()->after('plant_print'); // Destino (Turquia, etc.)
            $table->string('loading_date')->nullable()->after('destination'); // Fecha de carga (Semana 18/19, etc.)
            $table->boolean('sag')->default(false)->after('loading_date'); // SAG (SI/NO)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('process_orders', function (Blueprint $table) {
            $table->dropColumn([
                'raw_material', 'product', 'type', 'caliber', 'quality', 'labeling',
                'packaging', 'potassium_sorbate', 'humidity', 'stone_percentage',
                'oil', 'damage', 'plant_print', 'destination', 'loading_date', 'sag'
            ]);
        });
    }
}
