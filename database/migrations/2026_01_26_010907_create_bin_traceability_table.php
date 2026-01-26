<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBinTraceabilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bin_traceability', function (Blueprint $table) {
            $table->id();
            
            // Relación con bin fuente (puede ser null si es el origen desde compra)
            $table->foreignId('source_bin_id')->nullable()->constrained('processed_bins')->onDelete('cascade');
            
            // Relación con bin destino (puede ser null si se envía a procesar)
            $table->foreignId('target_bin_id')->nullable()->constrained('processed_bins')->onDelete('cascade');
            
            // Relación con orden de procesamiento (cuando se envía a procesar)
            $table->foreignId('process_order_id')->nullable()->constrained('process_orders')->onDelete('cascade');
            
            // Relación con compra (origen inicial)
            $table->foreignId('purchase_id')->nullable()->constrained('purchases')->onDelete('set null');
            
            // Tipo de operación: 'mixing' (mezcla), 'processing' (envío a procesar), 'initial' (recepción inicial)
            $table->string('operation_type', 50);
            
            // Peso involucrado en esta operación
            $table->decimal('weight_kg', 10, 2)->nullable();
            
            // Fecha de la operación
            $table->dateTime('operation_date');
            
            // Usuario que realizó la operación
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Notas adicionales
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Índices para búsquedas rápidas
            $table->index('source_bin_id');
            $table->index('target_bin_id');
            $table->index('process_order_id');
            $table->index('operation_type');
            $table->index('operation_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bin_traceability');
    }
}
