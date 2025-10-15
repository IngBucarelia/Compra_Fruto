<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('visita_socials', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->unsignedBigInteger('tecnico_campo'); // usuario (rol social)
            $table->unsignedBigInteger('proveedor_id');
            $table->unsignedBigInteger('plantacion_id');
            $table->string('ubicacion');
            $table->string('tipo_visita');
            $table->string('recibio_visita');
            $table->enum('estado', ['pendiente', 'en_ejecucion', 'finalizado'])->default('pendiente');
            $table->timestamps();

            // 🔗 Relaciones
            $table->foreign('tecnico_campo')->references('id')->on('users');
            $table->foreign('proveedor_id')->references('id')->on('proveedores');
            $table->foreign('plantacion_id')->references('id')->on('plantaciones');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visita_socials');
    }
};
