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
        Schema::create('visitas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('ubicacion');
            $table->unsignedBigInteger('tecnico_campo');
            $table->string('tipo_visita');
            $table->unsignedBigInteger('proveedor_id');
            $table->string('recibio_visita');
            $table->timestamps();

            $table->foreign('tecnico_campo')->references('id')->on('users');
            $table->foreign('proveedor_id')->references('id')->on('proveedores');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitas');
    }
};
