<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('residuos_manejos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visita_ambiental_id');
            $table->boolean('capacitacion_personal')->nullable();
            $table->boolean('conoce_clasificacion_residuos')->nullable();
            $table->boolean('certificados_respel')->nullable();
            $table->boolean('manifiesto_transporte_respel')->nullable();
            $table->boolean('puntos_ecologicos')->nullable();
            $table->boolean('entrega_residuos_transportador_autorizado')->nullable();
            $table->boolean('disposicion_final_empresa_autorizada')->nullable();
            $table->boolean('acciones_minimizacion_impactos')->nullable();
            $table->boolean('certificado_disposicion_final')->nullable();
            $table->boolean('aprovechables_gestionados')->nullable();
            $table->boolean('pesa_registra_cantidades')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('visita_ambiental_id')->references('id')->on('visitas_ambientales')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('residuos_manejos');
    }
};
