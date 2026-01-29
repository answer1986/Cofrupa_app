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
        Schema::table('clients', function (Blueprint $table) {
            $table->boolean('is_incomplete')->default(false);
        });

        Schema::table('processed_bins', function (Blueprint $table) {
            $table->foreignId('external_service_client_id')->nullable()->after('external_service')->constrained('clients')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('processed_bins', function (Blueprint $table) {
            $table->dropForeign(['external_service_client_id']);
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('is_incomplete');
        });
    }
};
