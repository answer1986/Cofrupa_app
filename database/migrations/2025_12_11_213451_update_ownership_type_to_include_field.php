<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateOwnershipTypeToIncludeField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Modificar el enum para incluir 'field' y cambiar el default
        DB::statement("ALTER TABLE bins MODIFY COLUMN ownership_type ENUM('supplier', 'internal', 'field') DEFAULT 'field'");
        
        // Actualizar los registros existentes que tengan 'internal' a 'field' si es necesario
        // (opcional, dependiendo de la lógica de negocio)
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revertir al enum original
        DB::statement("ALTER TABLE bins MODIFY COLUMN ownership_type ENUM('supplier', 'internal') DEFAULT 'internal'");
    }
}
