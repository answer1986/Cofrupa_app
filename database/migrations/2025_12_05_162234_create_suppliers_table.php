<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre del proveedor
            $table->string('business_name')->nullable(); // Razón social
            $table->string('location'); // Ubicación
            $table->string('phone')->nullable(); // Número de teléfono
            $table->string('business_type')->nullable(); // Giro comercial
            $table->decimal('total_debt', 10, 2)->default(0); // Deuda total
            $table->decimal('total_paid', 10, 2)->default(0); // Total pagado
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
        Schema::dropIfExists('suppliers');
    }
}
