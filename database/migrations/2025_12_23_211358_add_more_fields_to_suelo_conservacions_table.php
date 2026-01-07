<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // En la migración
    public function up()
    {
        Schema::table('suelo_conservacions', function (Blueprint $table) {
            $table->decimal('area_cobertura', 10, 2)->nullable()->after('siembra_coberturas');
            $table->string('tipo_cobertura', 100)->nullable()->after('area_cobertura');
            $table->string('otro_tipo_cobertura', 255)->nullable()->after('tipo_cobertura');
            $table->decimal('porcentaje_cobertura', 5, 2)->nullable()->after('otro_tipo_cobertura');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suelo_conservacions', function (Blueprint $table) {
            //
        });
    }
};
