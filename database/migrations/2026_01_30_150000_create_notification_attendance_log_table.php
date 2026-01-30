<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Bitácora de eventos de la campana: cuáles están atendidos y cuáles no.
     */
    public function up(): void
    {
        Schema::create('notification_attendance_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('event_type', 80)->comment('pending_purchases, incomplete_suppliers, etc.');
            $table->string('event_label', 255)->comment('Etiqueta para mostrar');
            $table->unsignedInteger('count_snapshot')->nullable()->comment('Cantidad al momento de marcar como atendido');
            $table->timestamp('attended_at');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'event_type']);
            $table->index('attended_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_attendance_log');
    }
};
