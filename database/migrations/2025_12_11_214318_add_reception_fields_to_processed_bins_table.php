<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReceptionFieldsToProcessedBinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('processed_bins', function (Blueprint $table) {
            $table->string('vehicle_plate')->nullable()->after('entry_date');
            $table->enum('bin_type', ['wood', 'plastic'])->nullable()->after('original_bin_number');
            $table->enum('trash_level', ['alto', 'mediano', 'bajo', 'limpio'])->nullable()->after('bin_type');
            $table->decimal('reception_total_weight', 10, 2)->nullable()->after('trash_level');
            $table->decimal('reception_weight_per_truck', 10, 2)->nullable()->after('reception_total_weight');
            $table->integer('reception_bins_count')->nullable()->after('reception_weight_per_truck');
            $table->string('reception_batch_id')->nullable()->after('reception_bins_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('processed_bins', function (Blueprint $table) {
            $table->dropColumn([
                'vehicle_plate',
                'bin_type',
                'trash_level',
                'reception_total_weight',
                'reception_weight_per_truck',
                'reception_bins_count',
                'reception_batch_id'
            ]);
        });
    }
}
