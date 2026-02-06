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
        Schema::create('supply_purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supply_purchase_id')->constrained('supply_purchases')->onDelete('cascade');
            $table->string('name')->comment('Nombre del insumo');
            $table->decimal('quantity', 12, 2)->comment('Cantidad');
            $table->string('unit', 50)->default('unidad')->comment('Unidad de medida');
            $table->decimal('unit_price', 12, 2)->nullable()->comment('Precio unitario');
            $table->decimal('total', 12, 2)->nullable()->comment('Total (cantidad × precio unitario)');
            $table->text('notes')->nullable()->comment('Notas del insumo');
            $table->timestamps();
            
            $table->index('supply_purchase_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supply_purchase_items');
    }
};
