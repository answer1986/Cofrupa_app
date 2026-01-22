<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('process_order_id')->constrained('process_orders')->onDelete('cascade');
            $table->string('invoice_number')->unique();
            $table->string('order_number'); // Orden de proceso
            $table->decimal('amount', 12, 2);
            $table->enum('currency', ['USD', 'CLP'])->default('CLP');
            $table->decimal('exchange_rate', 10, 4)->nullable(); // Tipo de cambio del día
            $table->boolean('is_paid')->default(false);
            $table->date('payment_date')->nullable();
            $table->date('invoice_date');
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
        Schema::dropIfExists('process_invoices');
    }
}
