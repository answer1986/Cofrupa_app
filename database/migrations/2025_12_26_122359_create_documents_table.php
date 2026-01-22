<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained()->onDelete('cascade');
            $table->string('document_number')->unique();
            $table->enum('document_type', [
                'export_guide_plant',      // Guía de exportación para planta (DUS, SPS)
                'export_guide_transport',   // Guía de exportación para transporte (DUS, SPS)
                'customs_loading',          // Documentos de carga para aduana (SPS, DUS)
                'dvl_matrix',               // Matriz DVL para embarque
                'master_document'           // Documento maestro único
            ]);
            $table->enum('recipient', ['plant', 'customs', 'transport', 'embarkation']);
            $table->string('recipient_company')->nullable(); // SPS, DUS, etc.
            $table->text('content')->nullable(); // JSON o texto con el contenido del documento
            $table->string('file_path')->nullable();
            $table->enum('status', ['draft', 'generated', 'sent', 'confirmed'])->default('draft');
            $table->datetime('generated_at')->nullable();
            $table->datetime('sent_at')->nullable();
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
        Schema::dropIfExists('documents');
    }
}
