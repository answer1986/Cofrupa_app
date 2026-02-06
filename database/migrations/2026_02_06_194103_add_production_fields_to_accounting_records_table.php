<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductionFieldsToAccountingRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounting_records', function (Blueprint $table) {
            $table->foreignId('plant_id')->nullable()->after('contract_id')->constrained('plants')->onDelete('set null');
            $table->foreignId('process_order_id')->nullable()->after('plant_id')->constrained('process_orders')->onDelete('set null');
            $table->foreignId('plant_production_order_id')->nullable()->after('process_order_id')->constrained('plant_production_orders')->onDelete('set null');
            $table->string('process_type')->nullable()->after('plant_production_order_id');
            $table->decimal('kilos_sent', 10, 2)->nullable()->after('quantity_kg');
            $table->enum('payment_method_type', ['cheque', 'transferencia'])->nullable()->after('payment_method');
            $table->string('payment_method_detail', 500)->nullable()->after('payment_method_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounting_records', function (Blueprint $table) {
            $table->dropForeign(['plant_id']);
            $table->dropForeign(['process_order_id']);
            $table->dropForeign(['plant_production_order_id']);
            $table->dropColumn([
                'plant_id', 
                'process_order_id', 
                'plant_production_order_id',
                'process_type',
                'kilos_sent',
                'payment_method_type',
                'payment_method_detail'
            ]);
        });
    }
}
