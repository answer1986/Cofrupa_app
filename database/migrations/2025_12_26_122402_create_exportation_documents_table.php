<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportationDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exportation_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exportation_id')->constrained()->onDelete('cascade');
            $table->enum('document_type', [
                'v1',                    // Declaración de exportación
                'commercial_invoice',    // Factura comercial
                'origin_certificate',    // Certificado de origen
                'phytosanitary',         // Fitosanitario
                'quality_certificate',  // Certificado de calidad
                'packing_list',          // Packing List
                'eur1',                  // EUR1 (si aplica)
                'contract_specific'      // Documentos específicos del contrato
            ]);
            $table->string('document_number')->nullable();
            $table->string('file_path');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->integer('file_size')->nullable(); // en bytes
            $table->enum('status', ['pending', 'uploaded', 'validated', 'approved'])->default('pending');
            $table->datetime('uploaded_at')->nullable();
            $table->datetime('validated_at')->nullable();
            $table->text('validation_notes')->nullable();
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
        Schema::dropIfExists('exportation_documents');
    }
}
