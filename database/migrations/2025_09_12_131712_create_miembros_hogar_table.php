<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('miembros_hogar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visita_social_id'); // Relación con la visita social
            $table->string('nombre');
            $table->string('documento');
            $table->enum('sexo', ['Mujer', 'Hombre', 'No se identifica']);
            $table->enum('parentezco', ['Hijo/a', 'Hijastro/a', 'Mamá', 'Papá', 'Hermano/a']);
            $table->boolean('reside_predio');
            $table->boolean('sabe_leer');
            $table->enum('nivel_estudio', ['Ninguno', 'Técnico', 'Tecnólogo', 'Profesional', 'Maestría']);
            $table->boolean('participa_labores');
            $table->timestamps();

            $table->foreign('visita_social_id')->references('id')->on('visitas_sociales')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('miembros_hogar');
    }
};
