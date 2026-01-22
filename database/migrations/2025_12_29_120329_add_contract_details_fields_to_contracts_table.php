<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContractDetailsFieldsToContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            // Fecha del contrato
            $table->date('contract_date')->nullable()->after('contract_number');
            
            // Consignatario (Consignee)
            $table->string('consignee_name')->nullable()->after('destination_port');
            $table->text('consignee_address')->nullable();
            $table->text('consignee_chinese_address')->nullable();
            $table->string('consignee_tax_id')->nullable()->comment('TAX ID / USCI');
            $table->string('consignee_phone')->nullable();
            
            // Dirección de Notificación (Notify Address)
            $table->string('notify_name')->nullable();
            $table->text('notify_address')->nullable();
            $table->text('notify_chinese_address')->nullable();
            $table->string('notify_tax_id')->nullable()->comment('TAX ID / USCI');
            $table->string('notify_phone')->nullable();
            
            // Personas de Contacto
            $table->string('contact_person_1_name')->nullable();
            $table->string('contact_person_1_phone')->nullable();
            $table->string('contact_person_2_name')->nullable();
            $table->string('contact_person_2_phone')->nullable();
            $table->string('contact_email')->nullable();
            
            // Vendedor (Seller) - puede ser fijo o configurable
            $table->string('seller_name')->nullable()->default('COFRUPA Export SPA');
            $table->text('seller_address')->nullable();
            $table->string('seller_phone')->nullable();
            
            // Información del Producto
            $table->text('product_description')->nullable()->comment('Product: Natural Condition Chilean prunes size 120/140 & 140+');
            $table->text('quality_specification')->nullable()->comment('Quality: As per attached spec / Chilean protocol');
            $table->string('crop_year')->nullable()->comment('Crop: 2025');
            $table->string('packing')->nullable()->comment('Packing: 25 kg bags');
            $table->string('label_info')->nullable()->comment('Label: To be provided by buyer');
            
            // Términos Comerciales
            $table->string('incoterm')->nullable()->comment('Incoterm: CFR Main Chinese port');
            $table->text('payment_terms')->nullable()->comment('Payment: 20% advance payment 2 weeks before ETD, 80% balance...');
            $table->text('required_documents')->nullable()->comment('Documents: invoice, 3/3 OBL, pack list, cert of origin., phytosanitary');
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
                'contract_date',
                'consignee_name',
                'consignee_address',
                'consignee_chinese_address',
                'consignee_tax_id',
                'consignee_phone',
                'notify_name',
                'notify_address',
                'notify_chinese_address',
                'notify_tax_id',
                'notify_phone',
                'contact_person_1_name',
                'contact_person_1_phone',
                'contact_person_2_name',
                'contact_person_2_phone',
                'contact_email',
                'seller_name',
                'seller_address',
                'seller_phone',
                'product_description',
                'quality_specification',
                'crop_year',
                'packing',
                'label_info',
                'incoterm',
                'payment_terms',
                'required_documents',
            ]);
        });
    }
}
