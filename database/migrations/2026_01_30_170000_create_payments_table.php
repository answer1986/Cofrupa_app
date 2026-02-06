<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabla de pagos detallados: mapea cada pago con método, número de cheque/transferencia, valor, OC, factura.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->enum('company', ['cofrupa', 'luis_gonzalez', 'comercializadora'])->comment('Empresa que realiza el pago');
            $table->enum('payment_method', ['cheque', 'transferencia', 'efectivo', 'tarjeta', 'otro'])->comment('Método de pago');
            $table->string('reference_number')->nullable()->comment('Nº de cheque o transferencia');
            $table->decimal('amount', 15, 2)->comment('Monto pagado');
            $table->enum('currency', ['CLP', 'USD'])->default('CLP');
            $table->date('payment_date')->comment('Fecha en que se realizó el pago');
            $table->string('invoice_number')->nullable()->comment('Nº de factura asociada');
            $table->string('purchase_order')->nullable()->comment('Orden de compra asociada');
            $table->string('payee_name')->nullable()->comment('Beneficiario / proveedor / cliente');
            $table->enum('payment_type', ['compra', 'venta', 'gasto', 'otro'])->default('compra')->comment('Tipo de pago');
            $table->morphs('payable'); // payable_id, payable_type (FinancePurchase, FinanceSale, Purchase, etc.)
            $table->enum('status', ['pendiente', 'completado', 'rechazado', 'anulado'])->default('completado');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['company', 'payment_date']);
            $table->index(['payment_method', 'status']);
            $table->index('payment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
