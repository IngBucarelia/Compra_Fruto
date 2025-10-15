<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('planificacion_socials', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->foreignId('tecnico_campo')->constrained('users');
            $table->foreignId('proveedor_id')->constrained('proveedores');
            $table->foreignId('plantacion_id')->constrained('plantaciones');
            $table->string('tipo_visita');
            $table->string('estado')->default('pendiente');
            $table->foreignId('visita_social_id')->nullable()->constrained('visita_socials');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('planificacion_socials');
    }
};