<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('broker_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('stock_committed', 15, 2);
            $table->decimal('price', 15, 2);
            $table->decimal('broker_commission_percentage', 5, 2)->nullable();
            $table->string('destination_bank')->nullable();
            $table->string('destination_port')->nullable();
            $table->text('contract_variations')->nullable();
            $table->enum('status', ['draft', 'active', 'completed', 'cancelled'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contracts');
    }
}
