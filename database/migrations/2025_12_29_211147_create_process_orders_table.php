<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plant_id')->constrained('plants')->onDelete('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
            $table->string('order_number')->unique();
            $table->string('csg_code')->nullable(); // Código de productor de SAG
            $table->integer('production_days')->nullable(); // Tiempo de producción en días
            $table->date('order_date');
            $table->date('expected_completion_date')->nullable();
            $table->date('actual_completion_date')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->integer('progress_percentage')->default(0); // Progreso de avance
            $table->text('product_description')->nullable();
            $table->decimal('quantity', 10, 2)->nullable();
            $table->string('unit')->default('kg');
            $table->text('notes')->nullable();
            $table->boolean('alert_sent')->default(false); // Si se envió alerta
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
        Schema::dropIfExists('process_orders');
    }
}
