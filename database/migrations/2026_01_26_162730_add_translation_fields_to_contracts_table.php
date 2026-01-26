<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTranslationFieldsToContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            // Campos faltantes del contrato
            $table->date('seller_date')->nullable()->after('contract_date')->comment('Seller Date');
            $table->string('contract_ref')->nullable()->after('contract_number')->comment('Contract Reference');
            $table->decimal('payment_per_container', 15, 2)->nullable()->after('total_amount')->comment('Payment per container');
            $table->string('humidity')->nullable()->after('quality_specification')->comment('HUMIDITY >15% < 21%');
            $table->string('total_defects')->nullable()->after('humidity')->comment('Total Defects Max 5%');
            
            // Información del beneficiario
            $table->string('beneficiary')->nullable()->after('seller_bank_address')->comment('Beneficiary');
            $table->string('beneficiary_bank_account')->nullable()->after('beneficiary')->comment('Bank Account');
            $table->string('beneficiary_account_number_swift')->nullable()->after('beneficiary_bank_account')->comment('Account Number - Swift');
            $table->text('commercial_details')->nullable()->after('beneficiary_account_number_swift')->comment('Commercial Details');
            
            // Campos de traducción al inglés (para auto-traducción)
            $table->text('product_description_english')->nullable()->after('product_description');
            $table->text('quality_specification_english')->nullable()->after('quality_specification');
            $table->string('packing_english')->nullable()->after('packing');
            $table->text('seller_address_english')->nullable()->after('seller_address');
            $table->text('consignee_address_english')->nullable()->after('consignee_address');
            $table->text('notify_address_english')->nullable()->after('notify_address');
            $table->text('payment_terms_english')->nullable()->after('payment_terms');
            $table->text('required_documents_english')->nullable()->after('required_documents');
            $table->text('transportation_details_english')->nullable()->after('transportation_details');
            $table->text('shipment_schedule_english')->nullable()->after('shipment_schedule');
            $table->text('contract_clause_english')->nullable()->after('contract_clause');
            $table->text('commercial_details_english')->nullable()->after('commercial_details');
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
                'seller_date',
                'contract_ref',
                'payment_per_container',
                'humidity',
                'total_defects',
                'beneficiary',
                'beneficiary_bank_account',
                'beneficiary_account_number_swift',
                'commercial_details',
                'product_description_english',
                'quality_specification_english',
                'packing_english',
                'seller_address_english',
                'consignee_address_english',
                'notify_address_english',
                'payment_terms_english',
                'required_documents_english',
                'transportation_details_english',
                'shipment_schedule_english',
                'contract_clause_english',
                'commercial_details_english',
            ]);
        });
    }
}
