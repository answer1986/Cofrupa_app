<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFreightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('freights', function (Blueprint $table) {
            $table->id();
            $table->enum('freight_type', ['reception', 'to_processing', 'to_port', 'supply_purchase', 'other'])->comment('Tipo de flete');
            $table->string('origin')->nullable();
            $table->string('destination')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('vehicle_plate')->nullable();
            $table->foreignId('logistics_company_id')->nullable()->constrained('logistics_companies')->onDelete('set null');
            $table->decimal('freight_cost', 10, 2);
            $table->enum('payment_status', ['pending', 'paid'])->default('pending');
            $table->date('freight_date');
            $table->decimal('kilos', 10, 2)->nullable();
            $table->string('guide_number')->nullable();
            // Referencias opcionales a diferentes entidades
            $table->foreignId('purchase_id')->nullable()->constrained('purchases')->onDelete('set null');
            $table->foreignId('process_order_id')->nullable()->constrained('process_orders')->onDelete('set null');
            $table->foreignId('shipment_id')->nullable()->constrained('shipments')->onDelete('set null');
            $table->foreignId('plant_shipment_id')->nullable()->constrained('plant_shipments')->onDelete('set null');
            $table->foreignId('supply_purchase_id')->nullable()->constrained('supply_purchases')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['freight_type', 'freight_date']);
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('freights');
    }
}
