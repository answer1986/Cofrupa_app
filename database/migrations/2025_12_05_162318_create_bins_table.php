<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bins', function (Blueprint $table) {
            $table->id();
            $table->string('bin_number')->unique(); // Número del bin
            $table->enum('type', ['wood', 'plastic']); // Tipo: madera o plástico
            $table->decimal('weight_capacity', 8, 2); // Capacidad en kg (60 para madera, 45 para plástico)
            $table->decimal('current_weight', 8, 2)->default(0); // Peso actual
            $table->unsignedBigInteger('supplier_id')->nullable(); // Proveedor asignado
            $table->enum('status', ['available', 'in_use', 'maintenance', 'damaged'])->default('available'); // Estado del bin
            $table->string('photo_path')->nullable(); // Ruta de la foto del bin
            $table->date('delivery_date')->nullable(); // Fecha de entrega al proveedor
            $table->date('return_date')->nullable(); // Fecha de devolución del proveedor
            $table->text('damage_description')->nullable(); // Descripción de daños
            $table->text('notes')->nullable(); // Notas adicionales
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bins');
    }
}
