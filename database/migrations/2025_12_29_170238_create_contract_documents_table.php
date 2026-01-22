<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->onDelete('cascade');
            $table->string('document_type')->comment('transport, naviera, contrato, calidad, sac, envio, instructivo_embarque, instructivo_carga, post_despacho');
            $table->string('document_name')->comment('Nombre original del archivo');
            $table->string('file_path')->comment('Ruta del archivo en storage');
            $table->string('file_type')->nullable()->comment('Tipo MIME del archivo');
            $table->bigInteger('file_size')->nullable()->comment('Tamaño en bytes');
            $table->text('notes')->nullable()->comment('Notas adicionales');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Índices
            $table->index(['contract_id', 'document_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract_documents');
    }
}
