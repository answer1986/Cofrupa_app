<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlantProductionOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plant_production_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->nullable()->constrained('contracts')->onDelete('set null');
            $table->foreignId('plant_id')->constrained('plants')->onDelete('cascade');
            $table->string('order_number')->unique(); // N° ORDEN
            $table->string('product')->nullable(); // PRODUCTO
            $table->string('output_caliber')->nullable(); // CALIBRE SALIDA / CALIBRE TIPO
            $table->decimal('order_quantity_kg', 10, 2); // KG ORDEN / CANTIDAD (kilos)
            $table->string('booking_number')->nullable(); // NUMERO DE RESERVA
            $table->string('vessel')->nullable(); // MOTONAVE
            $table->date('entry_date')->nullable(); // Fecha de ingreso a planta
            $table->time('entry_time')->nullable(); // Hora de ingreso
            $table->date('completion_date')->nullable(); // FECHA TERMINO
            $table->time('completion_time')->nullable(); // HORA TERMINO
            $table->string('production_program')->nullable(); // PROGRAMA DE PRODUCCION
            $table->decimal('sorbate_solution', 5, 2)->nullable(); // SOLUCION SORBATO
            $table->decimal('delay_hours', 5, 2)->nullable(); // ATRASO (en horas)
            $table->text('delay_reason')->nullable(); // RAZON DEL ATRASO
            $table->decimal('produced_kilos', 10, 2)->nullable(); // KILOS PRODUCIDOS
            $table->decimal('nominal_kg_per_hour', 8, 2)->nullable(); // KG/HORA NOMINAL
            $table->decimal('estimated_hours', 5, 2)->nullable(); // HORAS ESTIMADAS
            $table->decimal('actual_hours', 5, 2)->nullable(); // HORAS REALES
            $table->string('day_of_week')->nullable(); // DIA (lunes, martes, etc.)
            $table->enum('status', ['pending', 'in_progress', 'completed', 'delayed', 'cancelled'])->default('pending');
            $table->boolean('has_delay')->default(false); // Si hay retraso
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
        Schema::dropIfExists('plant_production_orders');
    }
}
