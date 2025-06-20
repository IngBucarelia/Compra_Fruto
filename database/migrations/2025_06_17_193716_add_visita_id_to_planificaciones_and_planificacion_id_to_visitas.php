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
        Schema::table('planificaciones', function (Blueprint $table) {
        $table->unsignedBigInteger('visita_id')->nullable()->after('estado');
        $table->foreign('visita_id')->references('id')->on('visitas')->onDelete('set null');
    });

    Schema::table('visitas', function (Blueprint $table) {
        $table->unsignedBigInteger('planificacion_id')->nullable()->after('estado');
        $table->foreign('planificacion_id')->references('id')->on('planificaciones')->onDelete('set null');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitas', function (Blueprint $table) {
            //
        });
    }
};
