<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBinAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bin_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bin_id');
            $table->unsignedBigInteger('supplier_id');
            $table->date('delivery_date');
            $table->date('return_date')->nullable();
            $table->decimal('weight_delivered', 8, 2)->default(0); // Peso entregado al proveedor
            $table->decimal('weight_returned', 8, 2)->default(0); // Peso devuelto por el proveedor
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('bin_id')->references('id')->on('bins')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');

            $table->index(['bin_id', 'supplier_id']);
            $table->index('delivery_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bin_assignments');
    }
}
