<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaintenancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('machine_id');
            $table->enum('maintenance_type', ['preventive', 'corrective', 'predictive', 'emergency']); // Tipo de mantención
            $table->enum('periodicity', ['daily', 'weekly', 'monthly', 'quarterly', 'biannual', 'annual', 'as_needed']); // Periodicidad
            $table->date('maintenance_date'); // Fecha de la mantención realizada
            $table->date('next_maintenance_date')->nullable(); // Próxima fecha de mantención
            $table->text('description')->nullable(); // Descripción del trabajo realizado
            $table->text('observations')->nullable(); // Observaciones
            $table->decimal('cost', 10, 2)->nullable(); // Costo de la mantención
            $table->string('technician')->nullable(); // Técnico que realizó la mantención
            $table->unsignedBigInteger('user_id')->nullable(); // Usuario que registró la mantención
            $table->timestamps();

            $table->foreign('machine_id')->references('id')->on('machines')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('maintenances');
    }
}
