<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pnoremplazo_nodeforestacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visita_ambiental_id')->constrained('visita_ambientals')->onDelete('cascade');
            
            // Campos principales
            $table->boolean('cuenta_estudios_avc_arc')->nullable();
            $table->boolean('evidencias_no_reemplazo_bosques')->nullable();
            $table->boolean('permiso_aprovechamiento_forestal')->nullable();
            $table->boolean('restauracion_compensacion')->nullable();
            $table->boolean('dentro_frontera_agricola')->nullable();
            
            // Campos de restauración (condicionales)
            $table->decimal('hectareas_restauracion', 10, 2)->nullable();
            $table->date('fecha_restauracion')->nullable();
            $table->string('tipo_restauracion', 50)->nullable();
            $table->string('otro_tipo_restauracion', 100)->nullable();
            $table->string('ubicacion_restauracion', 255)->nullable();
            $table->decimal('porcentaje_restauracion', 5, 2)->nullable();
            
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pnoremplazo_nodeforestacion');
    }
};