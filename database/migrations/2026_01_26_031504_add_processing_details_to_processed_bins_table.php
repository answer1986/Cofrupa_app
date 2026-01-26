<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProcessingDetailsToProcessedBinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('processed_bins', function (Blueprint $table) {
            $table->date('processing_start_date')->nullable()->after('processing_date')->comment('Fecha de inicio del proceso');
            $table->date('processing_end_date')->nullable()->after('processing_start_date')->comment('Fecha de término del proceso');
            $table->json('bins_processed_per_day')->nullable()->after('processing_end_date')->comment('Bins procesados por día');
            $table->text('defect_notes')->nullable()->after('notes')->comment('Notas sobre desperfectos');
            $table->text('observations')->nullable()->after('defect_notes')->comment('Observaciones generales');
            $table->string('fruit_type')->nullable()->after('observations')->comment('Tipo de fruta');
            $table->string('csg_code')->nullable()->after('fruit_type')->comment('Número CSG');
            $table->integer('cofrupa_plastic_bins_count')->default(0)->after('plastic_bins_count')->comment('Bins plásticos marca Cofrupa');
            $table->boolean('external_service')->default(false)->after('cofrupa_plastic_bins_count')->comment('Servicio para cliente externo');
            $table->string('external_service_client')->nullable()->after('external_service')->comment('Cliente externo');
            $table->date('external_service_period_start')->nullable()->after('external_service_client')->comment('Inicio período servicio externo');
            $table->date('external_service_period_end')->nullable()->after('external_service_period_start')->comment('Fin período servicio externo');
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
                'processing_start_date',
                'processing_end_date',
                'bins_processed_per_day',
                'defect_notes',
                'observations',
                'fruit_type',
                'csg_code',
                'cofrupa_plastic_bins_count',
                'external_service',
                'external_service_client',
                'external_service_period_start',
                'external_service_period_end',
            ]);
        });
    }
}
