<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProcessOrderIdToPlantProductionOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plant_production_orders', function (Blueprint $table) {
            $table->foreignId('process_order_id')->nullable()->after('contract_id')->constrained('process_orders')->onDelete('set null');
            $table->decimal('output_quantity_kg', 10, 2)->nullable()->after('produced_kilos'); // Kg de producto terminado
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plant_production_orders', function (Blueprint $table) {
            $table->dropForeign(['process_order_id']);
            $table->dropColumn(['process_order_id', 'output_quantity_kg']);
        });
    }
}
