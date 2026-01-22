<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBillingFieldsToShippingLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipping_lines', function (Blueprint $table) {
            $table->string('tax_id')->nullable(); // RUT
            $table->string('bank_name')->nullable(); // Banco
            $table->string('bank_account_type')->nullable(); // Tipo de cuenta
            $table->string('bank_account_number')->nullable(); // Número de cuenta
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipping_lines', function (Blueprint $table) {
            $table->dropColumn(['tax_id', 'bank_name', 'bank_account_type', 'bank_account_number']);
        });
    }
}
