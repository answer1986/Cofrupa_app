<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Añade campos bank y exchange_rate a finance_purchases si no existen
     */
    public function up(): void
    {
        Schema::table('finance_purchases', function (Blueprint $table) {
            if (!Schema::hasColumn('finance_purchases', 'bank')) {
                $table->string('bank')->nullable()->after('notes')->comment('Banco');
            }
            if (!Schema::hasColumn('finance_purchases', 'exchange_rate')) {
                $table->decimal('exchange_rate', 12, 2)->nullable()->after('bank')->comment('Tipo de cambio');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('finance_purchases', function (Blueprint $table) {
            $table->dropColumn(['bank', 'exchange_rate']);
        });
    }
};
