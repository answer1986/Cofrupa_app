<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('finance_sales', function (Blueprint $table) {
            $table->id();
            $table->enum('company', ['cofrupa', 'luis_gonzalez', 'comercializadora'])->comment('Empresa');
            $table->date('sale_date')->comment('Fecha');
            $table->string('invoice_number')->nullable()->comment('N° Factura');
            $table->string('contract_number')->nullable()->comment('N° Contrato');
            $table->string('client_name')->comment('Cliente');
            $table->string('caliber')->nullable()->comment('Calibre');
            $table->string('type')->nullable()->comment('Tipo');
            $table->decimal('kilos', 12, 2)->comment('Kilos');
            $table->string('destination_port')->nullable()->comment('Puerto destino');
            $table->string('destination_country')->nullable()->comment('País destino');
            $table->string('destination')->nullable()->comment('Destino general');
            $table->decimal('exchange_rate', 12, 4)->nullable()->comment('T/C (tipo de cambio)');
            $table->decimal('unit_price_clp', 12, 2)->nullable()->comment('Precio unitario (CLP)');
            $table->decimal('unit_price_usd', 12, 2)->nullable()->comment('Precio unitario (USD)');
            $table->decimal('net_price_clp', 12, 2)->nullable()->comment('Precio neto (CLP)');
            $table->decimal('net_price_usd', 12, 2)->nullable()->comment('Precio neto (USD)');
            $table->decimal('total_sale_clp', 12, 2)->nullable()->comment('Total venta CLP');
            $table->decimal('total_sale_usd', 12, 2)->nullable()->comment('Total venta USD');
            $table->decimal('iva_clp', 12, 2)->default(0)->comment('IVA CLP');
            $table->decimal('gross_total', 12, 2)->nullable()->comment('Bruto');
            $table->decimal('payment_usd', 12, 2)->default(0)->comment('Abono USD');
            $table->decimal('balance_usd', 12, 2)->nullable()->comment('Saldo USD');
            $table->boolean('paid')->default(false)->comment('Pago (Si/No)');
            $table->integer('payment_term_days')->nullable()->comment('Plazo (días)');
            $table->date('payment_date')->nullable()->comment('Fecha pago');
            $table->enum('status', ['pending', 'paid', 'partial', 'cancelled'])->default('pending')->comment('Estado');
            $table->string('bank')->nullable()->comment('Banco');
            $table->boolean('with_iva')->default(true)->comment('Con IVA');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['company', 'sale_date']);
            $table->index('status');
            $table->index('paid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_sales');
    }
};
