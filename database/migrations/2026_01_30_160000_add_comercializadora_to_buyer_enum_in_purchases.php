<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Añade 'Comercializadora' al ENUM buyer en purchases (el formulario ya lo usa).
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE purchases MODIFY COLUMN buyer ENUM('LG', 'Cofrupa', 'Comercializadora') NOT NULL DEFAULT 'Cofrupa'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No se puede revertir sin perder datos si hay filas con Comercializadora
        DB::statement("ALTER TABLE purchases MODIFY COLUMN buyer ENUM('LG', 'Cofrupa') NOT NULL DEFAULT 'Cofrupa'");
    }
};
