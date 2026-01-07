<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixVertimientosDataTypes extends Migration
{
    public function up()
    {
        Schema::table('vertimientos_manejos', function (Blueprint $table) {
            // Cambiar varchar a boolean/tinyint
            $table->boolean('permiso_vertimiento')->nullable()->change();
            $table->boolean('sistemas_tratamiento_domestico')->nullable()->change();
            $table->boolean('sistemas_tratamiento_agroquimicos')->nullable()->change();
            $table->boolean('cumple_obligacion_permiso')->nullable()->change();
            $table->boolean('gestion_permiso_vertimiento')->nullable()->change();
            $table->boolean('realiza_triplelavado')->nullable()->change();
            
            // Asegurar que los campos numéricos sean nullable
            $table->integer('numero_vertimientos_permitidos')->nullable()->change();
            $table->integer('numero_vertimientos_totales')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('vertimientos_manejos', function (Blueprint $table) {
            $table->string('permiso_vertimiento', 10)->nullable()->change();
            $table->string('sistemas_tratamiento_domestico', 10)->nullable()->change();
            $table->string('sistemas_tratamiento_agroquimicos', 10)->nullable()->change();
            $table->string('cumple_obligacion_permiso', 10)->nullable()->change();
            $table->string('gestion_permiso_vertimiento', 10)->nullable()->change();
            $table->string('realiza_triplelavado', 10)->nullable()->change();
        });
    }
}