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
    Schema::create('datos_personales_sociales', function (Blueprint $table) {
        $table->id();
        $table->foreignId('visita_social_id')
            ->constrained('visitas_sociales')
            ->onDelete('cascade');

        $table->foreignId('proveedor_id')
            ->constrained('proveedores')
            ->onDelete('cascade');

        $table->string('telefono')->nullable();
        $table->enum('sexo', ['Hombre', 'Mujer', 'No se identifica'])->nullable();
        $table->enum('rnp', ['SI', 'NO'])->nullable();
        $table->enum('fedepalma', ['SI', 'NO'])->nullable();
        $table->enum('alfabetizado', ['SI', 'NO'])->nullable();
        $table->enum('nivel_estudio', ['Primaria','Bachillerato','Ninguno','Tecnico','Tecnologo','Profesional','Maestria'])->nullable();
        $table->enum('otras_lineas', ['SI','NO'])->nullable();
        $table->date('fecha_nacimiento')->nullable();
        $table->enum('grupo_poblacional', ['Indigena','Afrodescendiente','Campesino','Ninguno'])->nullable();
        $table->enum('reside_predio', ['SI','NO'])->nullable();
        $table->enum('administra_cultivo', ['SI','NO','Tercero'])->nullable();
        $table->enum('supervisa_cultivo', ['SI','NO','Tercero'])->nullable();
        $table->enum('realiza_cultivo', ['SI','NO','Tercero'])->nullable();
        $table->integer('anios_palmicultura')->nullable();
        $table->enum('internet', ['SI','NO'])->nullable();
        $table->enum('tipo_persona', ['Natural','Juridica'])->nullable();
        $table->enum('red_social', ['Whatsapp','Facebook','Instagram','TikTok','Todas','Ninguna'])->nullable();
        $table->enum('regimen_salud', ['Contributivo','Subsidiado','Especial','Ninguno'])->nullable();

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datos_personales_socials');
    }
};
