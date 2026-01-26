<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKilosFieldsToProcessOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('process_orders', function (Blueprint $table) {
            $table->decimal('kilos_sent', 10, 2)->nullable()->after('quantity'); // Kilos enviados
            $table->decimal('kilos_produced', 10, 2)->nullable()->after('kilos_sent'); // Kilos producidos
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('process_orders', function (Blueprint $table) {
            $table->dropColumn(['kilos_sent', 'kilos_produced']);
        });
    }
}
