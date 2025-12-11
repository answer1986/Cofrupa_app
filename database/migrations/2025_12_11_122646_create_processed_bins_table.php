<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessedBinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processed_bins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_id')->nullable();
            $table->unsignedBigInteger('supplier_id');
            $table->date('entry_date'); // Fecha de ingreso/recepción
            $table->date('processing_date')->nullable(); // Fecha de procesamiento/calibración
            $table->date('exit_date')->nullable(); // Fecha de salida
            $table->string('destination')->nullable(); // Destino
            $table->string('guide_number')->nullable(); // N° guía o proceso
            $table->string('original_bin_number'); // Número del bin original recibido
            $table->string('current_bin_number'); // Número del bin actual (puede cambiar en procesamiento)
            $table->decimal('original_weight', 8, 2)->nullable(); // Peso original al recibir
            $table->decimal('processed_weight', 8, 2)->nullable(); // Peso después de procesamiento
            $table->enum('original_calibre', [
                '80-90', '120-x', '90-100', '70-90',
                'Grande 50-60', 'Mediana 40-50', 'Pequeña 30-40'
            ])->nullable(); // Calibre original
            $table->enum('processed_calibre', [
                '80-90', '120-x', '90-100', '70-90',
                'Grande 50-60', 'Mediana 40-50', 'Pequeña 30-40'
            ])->nullable(); // Calibre después de procesamiento
            $table->text('qr_code')->nullable(); // Código QR encriptado (contiene historial)
            $table->timestamp('qr_generated_at')->nullable();
            $table->timestamp('qr_updated_at')->nullable(); // Última actualización del QR
            $table->enum('status', ['received', 'processed', 'shipped', 'delivered'])->default('received');
            $table->timestamp('received_at');
            $table->timestamp('processed_at')->nullable();
            $table->json('processing_history')->nullable(); // Historial de procesamientos
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('set null');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');

            $table->index(['supplier_id', 'status']);
            $table->index('current_bin_number');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('processed_bins');
    }
}
