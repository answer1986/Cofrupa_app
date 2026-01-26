<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBillingFieldsToClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('tax_id')->nullable()->after('customs_agency');
            $table->string('bank_name')->nullable()->after('tax_id');
            $table->string('bank_account_type')->nullable()->after('bank_name');
            $table->string('bank_account_number')->nullable()->after('bank_account_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['tax_id', 'bank_name', 'bank_account_type', 'bank_account_number']);
        });
    }
}
