<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWeightFieldsToProcessedBinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('processed_bins', function (Blueprint $table) {
            $table->decimal('gross_weight', 10, 2)->nullable()->after('original_weight'); // Peso bruto del grupo (bins + fruta)
            $table->integer('bins_in_group')->default(1)->after('gross_weight'); // Cantidad de bins pesados juntos
            $table->integer('wood_bins_count')->default(0)->after('bins_in_group'); // Cantidad de bins de madera en el grupo
            $table->integer('plastic_bins_count')->default(0)->after('wood_bins_count'); // Cantidad de bins de plástico en el grupo
            $table->decimal('net_fruit_weight', 10, 2)->nullable()->after('plastic_bins_count'); // Peso neto de fruta (calculado)
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
                'gross_weight',
                'bins_in_group',
                'wood_bins_count',
                'plastic_bins_count',
                'net_fruit_weight'
            ]);
        });
    }
}
