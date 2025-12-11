<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeBinIdNullableInPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // For SQLite, we need to recreate the table since it doesn't support
        // dropping foreign keys easily. We'll use a raw approach.
        Schema::table('purchases', function (Blueprint $table) {
            // First drop the foreign key constraint
            $table->dropForeign(['bin_id']);
            // Then make the column nullable
            $table->unsignedBigInteger('bin_id')->nullable()->change();
            // Add the foreign key back as nullable
            $table->foreign('bin_id')->references('id')->on('bins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign(['bin_id']);
            $table->unsignedBigInteger('bin_id')->nullable(false)->change();
            $table->foreign('bin_id')->references('id')->on('bins')->onDelete('cascade');
        });
    }
}
