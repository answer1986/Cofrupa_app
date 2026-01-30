<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Deuda/capital por banco (registro aparte, para comprar y vender).
     */
    public function up(): void
    {
        Schema::create('finance_bank_debts', function (Blueprint $table) {
            $table->id();
            $table->enum('company', ['cofrupa', 'luis_gonzalez', 'comercializadora'])->comment('Empresa');
            $table->string('bank')->comment('Banco');
            $table->decimal('amount_usd', 14, 2)->comment('Monto (US$)');
            $table->date('due_date')->nullable()->comment('Vencimiento');
            $table->enum('type', ['compra', 'venta', 'general'])->default('general')->comment('Uso: compra, venta o general');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['company', 'bank']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_bank_debts');
    }
};
