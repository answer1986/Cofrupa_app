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
        Schema::create('finance_purchases', function (Blueprint $table) {
            $table->id();
            $table->enum('company', ['cofrupa', 'luis_gonzalez', 'comercializadora'])->comment('Empresa');
            $table->date('purchase_date')->comment('Fecha');
            $table->string('invoice_number')->nullable()->comment('N° Factura');
            $table->string('supplier_name')->comment('Proveedor/Productor');
            $table->string('product_caliber')->nullable()->comment('Producto/Calibre');
            $table->string('type')->nullable()->comment('Tipo');
            $table->decimal('kilos', 12, 2)->comment('Kilos');
            $table->decimal('unit_price_clp', 12, 2)->nullable()->comment('Precio unitario neto (CLP)');
            $table->decimal('unit_price_usd', 12, 2)->nullable()->comment('Precio unitario neto (USD)');
            $table->decimal('total_net_clp', 12, 2)->nullable()->comment('Total neto CLP');
            $table->decimal('total_net_usd', 12, 2)->nullable()->comment('Total neto USD');
            $table->decimal('iva', 12, 2)->default(0)->comment('IVA');
            $table->decimal('total_clp', 12, 2)->nullable()->comment('Total CLP');
            $table->decimal('total_usd', 12, 2)->nullable()->comment('Total USD');
            $table->decimal('commission_per_kilo', 12, 2)->default(0)->comment('Comisión $/Kilo');
            $table->decimal('total_commission', 12, 2)->default(0)->comment('Total comisión');
            $table->decimal('freight_per_kilo', 12, 2)->default(0)->comment('Flete $/Kilo');
            $table->decimal('total_freight', 12, 2)->default(0)->comment('Total flete');
            $table->decimal('other_costs', 12, 2)->default(0)->comment('Otros costos');
            $table->decimal('final_total', 12, 2)->nullable()->comment('Total final');
            $table->decimal('average_per_kilo', 12, 2)->nullable()->comment('Promedio por kilo');
            $table->enum('status', ['pending', 'paid', 'partial', 'cancelled'])->default('pending')->comment('Estado');
            $table->boolean('with_iva')->default(true)->comment('Con IVA');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['company', 'purchase_date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_purchases');
    }
};
