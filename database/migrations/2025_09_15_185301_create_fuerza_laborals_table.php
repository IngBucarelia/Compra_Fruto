<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fuerza_laborals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visita_social_id');

            // Preguntas
            $table->json('forma_contratacion')->nullable(); // varias opciones
            $table->integer('num_trabajadores')->nullable();
            $table->integer('num_hombres')->nullable();
            $table->integer('num_mujeres')->nullable();
            $table->enum('contrato_formal', ['SI', 'NO'])->nullable();
            $table->enum('seguridad_social', ['SI', 'NO', 'En proceso'])->nullable();
            $table->enum('tipo_contrato', ['Termino fijo', 'Termino indefinido', 'Por obra labor', 'Contrato tiempo parcial'])->nullable();
            $table->enum('contrato_firmado', ['SI', 'NO'])->nullable();
            $table->enum('sg_sst', ['SI', 'NO', 'En proceso'])->nullable();
            $table->enum('examenes_medicos', ['SI', 'NO'])->nullable();
            $table->enum('trabajadores_migrantes', ['SI', 'NO'])->nullable();
            $table->enum('comprobantes_pago', ['SI', 'NO'])->nullable();
            $table->enum('dotacion', ['SI', 'NO'])->nullable();

            $table->timestamps();

            $table->foreign('visita_social_id')->references('id')->on('visitas_socials')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fuerza_laborals');
    }
};
