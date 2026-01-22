<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingContractFieldsToContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            // Referencia del Cliente (PO#)
            $table->string('customer_reference')->nullable()->comment('PO# / Referencia del Cliente');
            
            // Puerto de Embarque (Port of Charge)
            $table->string('port_of_charge')->nullable()->comment('Puerto de Embarque (Port of Charge)');
            
            // Fecha de Vencimiento
            $table->date('maturity_date')->nullable()->comment('Fecha de Vencimiento del Contrato');
            
            // Detalles de Transporte
            $table->text('transportation_details')->nullable()->comment('Detalles del transporte: tipo de contenedor, cantidad, etc.');
            
            // Cronograma de Embarque
            $table->text('shipment_schedule')->nullable()->comment('Cronograma de embarque: "1 FCL AUGUST 2025 AND 1 FCL SEPTEMBER 2025"');
            
            // Información Bancaria del Vendedor
            $table->string('seller_tax_id')->nullable()->comment('RUT / Tax ID del Vendedor');
            $table->string('seller_bank_name')->nullable()->comment('Nombre del Banco del Vendedor');
            $table->string('seller_bank_account_number')->nullable()->comment('Número de Cuenta Corriente');
            $table->string('seller_bank_swift')->nullable()->comment('SWIFT Code del Banco');
            $table->text('seller_bank_address')->nullable()->comment('Dirección del Banco');
            $table->string('payment_type')->nullable()->comment('Tipo de Pago: OUR, SHA, BEN');
            
            // Cláusula del Contrato
            $table->text('contract_clause')->nullable()->comment('Cláusula de arbitraje y penalización');
            
            // Monto Total (aunque se calcula, puede ser útil tenerlo explícito)
            $table->decimal('total_amount', 15, 2)->nullable()->comment('Monto Total del Contrato');
            
            // Precio Unitario por kg (para claridad)
            $table->decimal('unit_price_per_kg', 15, 2)->nullable()->comment('Precio Unitario por kg');
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
                'customer_reference',
                'port_of_charge',
                'maturity_date',
                'transportation_details',
                'shipment_schedule',
                'seller_tax_id',
                'seller_bank_name',
                'seller_bank_account_number',
                'seller_bank_swift',
                'seller_bank_address',
                'payment_type',
                'contract_clause',
                'total_amount',
                'unit_price_per_kg',
            ]);
        });
    }
}
