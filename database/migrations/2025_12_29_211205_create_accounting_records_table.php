<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountingRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounting_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
            $table->foreignId('contract_id')->nullable()->constrained('contracts')->onDelete('set null');
            $table->string('transaction_type'); // 'purchase', 'sale', 'payment', 'advance'
            $table->date('transaction_date');
            $table->date('closing_date')->nullable(); // Fecha del cierre del negocio
            $table->string('product_description')->nullable();
            $table->string('size_range')->nullable(); // Ej: 70-80
            $table->decimal('price_per_kg', 10, 2)->nullable(); // Ej: 1.5 por kilo
            $table->decimal('quantity_kg', 10, 2)->nullable();
            $table->decimal('total_amount', 12, 2);
            $table->enum('currency', ['USD', 'CLP'])->default('CLP');
            $table->decimal('exchange_rate', 10, 4)->nullable(); // Valor por dólar del día
            $table->decimal('advance_payment', 12, 2)->nullable(); // Abono/cheque
            $table->decimal('remaining_amount', 12, 2)->nullable(); // Restante
            $table->string('payment_method')->nullable(); // cheque, transferencia, etc.
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->date('payment_due_date')->nullable(); // Pago a x día
            $table->date('actual_payment_date')->nullable();
            $table->enum('payment_status', ['pending', 'partial', 'paid'])->default('pending');
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
        Schema::dropIfExists('accounting_records');
    }
}
