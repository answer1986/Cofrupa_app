<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('process_order_supplies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('process_order_id')->constrained('process_orders')->onDelete('cascade');
            $table->foreignId('supply_purchase_item_id')->nullable()->constrained('supply_purchase_items')->onDelete('set null');
            $table->string('name')->comment('Nombre del insumo a enviar');
            $table->decimal('quantity', 12, 2)->comment('Cantidad a enviar');
            $table->string('unit', 50)->default('unidad')->comment('Unidad de medida');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('process_order_supplies');
    }
};
