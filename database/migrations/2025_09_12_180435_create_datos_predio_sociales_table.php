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
        Schema::create('datos_predio_sociales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plantacion_id')
                ->constrained('plantaciones') // 👈 aquí el nombre real de la tabla
                ->onDelete('cascade');

            $table->foreignId('visita_social_id')
                ->constrained('visita_socials') // asegúrate que esta tabla existe con ese nombre
                ->onDelete('cascade');

            $table->string('nombre_finca');
            $table->json('forma_tenencia')->nullable();
            $table->string('municipio')->nullable();
            $table->string('vereda')->nullable();
            $table->enum('registrado_ica', ['SI', 'NO', 'EN_GESTION'])->nullable();
            $table->string('vive_predio')->nullable();
            $table->json('infraestructura_vial')->nullable();
            $table->enum('infraestructura_predio', ['SI', 'NO'])->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datos_predio_sociales');
    }
};
