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
        Schema::create('supply_purchases', function (Blueprint $table) {
            $table->id();
            $table->date('purchase_date')->comment('Fecha de compra');
            $table->string('supplier_name')->comment('Nombre del proveedor de insumos');
            $table->string('invoice_number')->nullable()->comment('N° Factura u orden de compra');
            $table->enum('buyer', ['LG', 'Cofrupa', 'Comercializadora'])->comment('Comprador');
            $table->decimal('total_amount', 12, 2)->default(0)->comment('Total de la compra');
            $table->decimal('amount_paid', 12, 2)->default(0)->comment('Monto pagado');
            $table->decimal('amount_owed', 12, 2)->default(0)->comment('Monto adeudado');
            $table->enum('payment_status', ['pending', 'partial', 'paid'])->default('pending')->comment('Estado de pago');
            $table->date('payment_due_date')->nullable()->comment('Fecha de vencimiento');
            $table->text('notes')->nullable()->comment('Notas generales');
            $table->timestamps();
            
            $table->index('purchase_date');
            $table->index('supplier_name');
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supply_purchases');
    }
};
