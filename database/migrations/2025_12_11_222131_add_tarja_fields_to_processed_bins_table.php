<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTarjaFieldsToProcessedBinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('processed_bins', function (Blueprint $table) {
            $table->string('tarja_number')->unique()->nullable()->after('reception_batch_id');
            $table->string('lote')->nullable()->after('tarja_number');
            $table->decimal('unidades_per_pound_avg', 8, 2)->nullable()->after('lote');
            $table->decimal('humidity', 5, 2)->nullable()->after('unidades_per_pound_avg');
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
            $table->dropColumn(['tarja_number', 'lote', 'unidades_per_pound_avg', 'humidity']);
        });
    }
}
