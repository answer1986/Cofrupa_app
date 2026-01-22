<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->onDelete('cascade');
            $table->string('shipment_number')->unique();
            $table->unsignedBigInteger('shipping_line_id')->nullable();
            $table->date('scheduled_date');
            $table->date('actual_date')->nullable();
            $table->enum('status', ['scheduled', 'in_transit', 'at_customs', 'loaded', 'shipped', 'completed', 'cancelled'])->default('scheduled');
            
            // Asignaciones
            $table->string('plant_pickup_company')->nullable(); // SPS, DUS
            $table->string('customs_loading_company')->nullable(); // SPS, DUS
            $table->string('transport_company')->nullable();
            $table->string('transport_contact')->nullable();
            $table->string('transport_phone')->nullable();
            
            // Información de transporte
            $table->string('transport_request_number')->nullable();
            $table->text('transport_notes')->nullable();
            
            // Control de tiempos
            $table->datetime('plant_pickup_scheduled')->nullable();
            $table->datetime('plant_pickup_actual')->nullable();
            $table->datetime('customs_loading_scheduled')->nullable();
            $table->datetime('customs_loading_actual')->nullable();
            $table->datetime('transport_departure_scheduled')->nullable();
            $table->datetime('transport_departure_actual')->nullable();
            $table->datetime('port_arrival_scheduled')->nullable();
            $table->datetime('port_arrival_actual')->nullable();
            
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('shipments');
    }
}
