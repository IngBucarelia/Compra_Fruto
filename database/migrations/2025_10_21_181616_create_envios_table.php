<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnviosTable extends Migration
{
    public function up()
    {
        Schema::create('envios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')->constrained('proveedores')->onDelete('cascade');
            $table->foreignId('plantacion_id')->constrained('plantacion')->onDelete('cascade');
            $table->foreignId('tecnico_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('fecha_envio');
            $table->text('descripcion_envio');
            $table->enum('estado', ['planificado','en_ejecucion','completado'])->default('planificado');
            $table->text('comentarios_finales')->nullable();
            $table->string('firma_envio')->nullable(); // ruta de la firma
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('envios');
    }
}
