<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hmp_manejos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visita_ambiental_id');
            $table->boolean('implementa_hmp')->nullable();
            $table->boolean('incluye_hmp_en_diseno')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('visita_ambiental_id')->references('id')->on('visitas_ambientales')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hmp_manejos');
    }
};
