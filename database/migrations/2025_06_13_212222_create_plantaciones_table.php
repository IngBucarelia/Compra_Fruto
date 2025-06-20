<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // Migración
        public function up()
        {
            Schema::create('plantaciones', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_proveedor')->constrained('proveedores');
                $table->string('nombre');
                $table->string('vereda');
                $table->string('municipio');
                $table->string('corregimiento');
                $table->string('departamento');
                $table->string('geolocalizacion');
                $table->date('dia_creado');
                $table->timestamps();
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plantaciones');
    }
};
