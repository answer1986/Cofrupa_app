<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDamagePercentageToProcessedBinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('processed_bins', function (Blueprint $table) {
            $table->decimal('damage_percentage', 5, 2)->nullable()->after('humidity')->comment('Porcentaje de daño de la fruta');
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
            $table->dropColumn('damage_percentage');
        });
    }
}
