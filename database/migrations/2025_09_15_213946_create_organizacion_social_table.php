<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizacion_social', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visita_id'); 
            $table->enum('pertenece_jac', ['SI', 'NO'])->nullable();
            $table->enum('pertenece_asociacion', ['SI', 'NO'])->nullable();
            $table->string('nombre_asociacion')->nullable();
            $table->timestamps();

            $table->foreign('visita_id')->references('id')->on('visitas_sociales')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizacion_social');
    }
};
