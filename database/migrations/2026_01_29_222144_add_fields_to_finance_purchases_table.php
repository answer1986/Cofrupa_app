<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToFinancePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('finance_purchases', function (Blueprint $table) {
            $table->decimal('exchange_rate', 10, 2)->nullable()->after('kilos');
            $table->string('bank')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('finance_purchases', function (Blueprint $table) {
            $table->dropColumn(['exchange_rate', 'bank']);
        });
    }
}
