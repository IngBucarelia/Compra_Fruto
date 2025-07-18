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
        Schema::create('fertilizaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visita_id')->constrained('visitas')->onDelete('cascade');
            $table->date('fecha_fertilizacion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fertilizacions');
    }
};
