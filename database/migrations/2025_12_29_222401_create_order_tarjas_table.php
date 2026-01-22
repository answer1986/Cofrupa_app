<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTarjasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_tarjas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('process_order_id')->constrained('process_orders')->onDelete('cascade');
            $table->foreignId('processed_bin_id')->constrained('processed_bins')->onDelete('cascade');
            $table->decimal('quantity_kg', 10, 2); // Cantidad de kg de esta tarja usada en la orden
            $table->timestamps();
            
            // Evitar duplicados
            $table->unique(['process_order_id', 'processed_bin_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_tarjas');
    }
}
