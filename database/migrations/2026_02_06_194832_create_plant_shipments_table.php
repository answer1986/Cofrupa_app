<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlantShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plant_shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plant_id')->constrained('plants')->onDelete('cascade');
            $table->foreignId('process_order_id')->nullable()->constrained('process_orders')->onDelete('set null');
            $table->string('driver_name');
            $table->string('vehicle_plate');
            $table->string('guide_number')->unique();
            $table->date('shipment_date');
            $table->string('destination');
            $table->decimal('total_kilos', 10, 2);
            $table->string('bin_type')->nullable();
            $table->decimal('shipment_cost', 10, 2)->nullable();
            $table->enum('payment_status', ['paid', 'unpaid'])->default('unpaid');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Tabla intermedia para bins incluidos en el despacho
        Schema::create('plant_shipment_bins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plant_shipment_id')->constrained('plant_shipments')->onDelete('cascade');
            $table->foreignId('processed_bin_id')->constrained('processed_bins')->onDelete('cascade');
            $table->decimal('kilos_sent', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plant_shipment_bins');
        Schema::dropIfExists('plant_shipments');
    }
}
