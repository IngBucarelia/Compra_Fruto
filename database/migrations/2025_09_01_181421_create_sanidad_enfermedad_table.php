<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sanidad_enfermedad', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sanidad_id');
            $table->string('nombre_enfermedad');
            $table->timestamps();

            $table->foreign('sanidad_id')->references('id')->on('sanidades')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sanidad_enfermedad');
    }
};
