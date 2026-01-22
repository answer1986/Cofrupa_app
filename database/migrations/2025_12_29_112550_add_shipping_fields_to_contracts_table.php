<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShippingFieldsToContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->string('contract_number')->nullable()->after('id');
            $table->string('product_type')->nullable()->comment('Producto o tipo de tarifa (ej: EX50-60)');
            $table->string('booking_number')->nullable()->comment('Número de Booking');
            $table->string('vessel_name')->nullable()->comment('Nombre del buque');
            $table->date('etd_date')->nullable()->comment('ETD - Fecha de salida estimada');
            $table->integer('etd_week')->nullable()->comment('Semana ETD');
            $table->date('eta_date')->nullable()->comment('ETA - Fecha de llegada estimada');
            $table->integer('eta_week')->nullable()->comment('Semana ETA');
            $table->string('container_number')->nullable()->comment('Número de contenedor o referencia');
            $table->integer('transit_weeks')->nullable()->comment('Número de semanas de tránsito');
            $table->decimal('freight_amount', 15, 2)->nullable()->comment('Peso, tarifa o monto');
            $table->enum('payment_status', ['pending', 'paid', 'partial'])->default('pending')->comment('Estado del pago');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn([
                'contract_number',
                'product_type',
                'booking_number',
                'vessel_name',
                'etd_date',
                'etd_week',
                'eta_date',
                'eta_week',
                'container_number',
                'transit_weeks',
                'freight_amount',
                'payment_status',
            ]);
        });
    }
}
