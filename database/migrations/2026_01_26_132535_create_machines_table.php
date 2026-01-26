<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plant_id');
            $table->string('name');
            $table->string('code')->unique();
            $table->string('type')->nullable(); // Tipo de máquina
            $table->string('brand')->nullable(); // Marca
            $table->string('model')->nullable(); // Modelo
            $table->string('serial_number')->nullable(); // Número de serie
            $table->enum('status', ['active', 'inactive', 'maintenance', 'retired'])->default('active');
            $table->date('purchase_date')->nullable(); // Fecha de compra
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('plant_id')->references('id')->on('plants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('machines');
    }
}
