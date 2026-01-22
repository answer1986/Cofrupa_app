<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStockFieldsToProcessedBinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('processed_bins', function (Blueprint $table) {
            $table->enum('stock_status', ['available', 'assigned', 'in_process', 'completed'])->default('available')->after('net_fruit_weight');
            $table->decimal('available_kg', 10, 2)->nullable()->after('stock_status'); // Kg disponibles
            $table->decimal('assigned_kg', 10, 2)->default(0)->after('available_kg'); // Kg asignados a órdenes
            $table->decimal('used_kg', 10, 2)->default(0)->after('assigned_kg'); // Kg ya procesados
            $table->string('location')->nullable()->after('used_kg'); // Ubicación física en bodega
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('processed_bins', function (Blueprint $table) {
            $table->dropColumn(['stock_status', 'available_kg', 'assigned_kg', 'used_kg', 'location']);
        });
    }
}
