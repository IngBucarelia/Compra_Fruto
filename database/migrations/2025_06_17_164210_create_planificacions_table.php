<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('planificaciones', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->unsignedBigInteger('tecnico_campo');
            $table->unsignedBigInteger('proveedor_id');
            $table->unsignedBigInteger('plantacion_id');
            $table->string('tipo_visita');
            $table->enum('estado', ['pendiente', 'realizada', 'cancelada'])->default('pendiente');
            $table->timestamps();

            $table->foreign('tecnico_campo')->references('id')->on('users');
            $table->foreign('proveedor_id')->references('id')->on('proveedores');
            $table->foreign('plantacion_id')->references('id')->on('plantaciones');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planificaciones');
    }
};
