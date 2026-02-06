<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('process_orders', function (Blueprint $table) {
            $table->unsignedTinyInteger('completion_week')->nullable()->after('expected_completion_date')->comment('Semana del año (1-56)');
            $table->unsignedSmallInteger('completion_year')->nullable()->after('completion_week')->comment('Año');
        });
    }

    public function down(): void
    {
        Schema::table('process_orders', function (Blueprint $table) {
            $table->dropColumn(['completion_week', 'completion_year']);
        });
    }
};
