<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('avc_controles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visita_ambiental_id');
            $table->boolean('registros_avistamientos')->nullable();
            $table->boolean('identifico_avc_arc')->nullable();
            $table->boolean('implementa_medidas_manejo')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('visita_ambiental_id')->references('id')->on('visitas_ambientales')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avc_controles');
    }
};
