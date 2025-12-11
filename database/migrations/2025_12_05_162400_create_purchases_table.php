<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('bin_id');
            $table->date('purchase_date');
            $table->decimal('weight_purchased', 8, 2); // Kilos comprados
            $table->enum('calibre', [
                '80-90', '120-x', '90-100', '70-90',
                'Grande 50-60', 'Mediana 40-50', 'Pequeña 30-40'
            ]); // Calibres
            $table->integer('units_per_pound'); // Unidades x libra
            $table->decimal('unit_price', 8, 2)->nullable(); // Precio por unidad
            $table->decimal('total_amount', 10, 2)->nullable(); // Monto total
            $table->decimal('amount_paid', 10, 2)->default(0); // Monto pagado
            $table->decimal('amount_owed', 10, 2)->nullable(); // Monto pendiente
            $table->enum('payment_status', ['pending', 'partial', 'paid'])->default('pending');
            $table->date('payment_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('bin_id')->references('id')->on('bins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
