<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('plantacion_avc', function (Blueprint $table) {
            $table->text('especies_identificadas')->nullable()->after('identifica_avc_arc');
            $table->date('fecha_identificacion')->nullable()->after('especies_identificadas');
            $table->string('ubicacion_identificacion', 255)->nullable()->after('fecha_identificacion');
            $table->json('tipo_identificacion')->nullable()->after('ubicacion_identificacion');
        });
    }

    public function down()
    {
        Schema::table('plantacion_avc', function (Blueprint $table) {
            $table->dropColumn(['especies_identificadas', 'fecha_identificacion', 'ubicacion_identificacion', 'tipo_identificacion']);
        });
    }
};
