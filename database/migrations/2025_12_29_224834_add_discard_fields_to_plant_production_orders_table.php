<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plant_production_orders', function (Blueprint $table) {
            $table->decimal('discard_kg', 10, 2)->default(0)->after('produced_kilos');
            $table->text('discard_reason')->nullable()->after('discard_kg');
            $table->enum('discard_status', ['pending', 'recovered', 'disposed'])->default('pending')->after('discard_reason');
            $table->date('discard_recovery_date')->nullable()->after('discard_status');
            $table->text('discard_notes')->nullable()->after('discard_recovery_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plant_production_orders', function (Blueprint $table) {
            $table->dropColumn([
                'discard_kg', 
                'discard_reason', 
                'discard_status', 
                'discard_recovery_date',
                'discard_notes'
            ]);
        });
    }
};
