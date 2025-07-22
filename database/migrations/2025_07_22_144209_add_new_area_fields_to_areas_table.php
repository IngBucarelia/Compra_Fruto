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
        Schema::table('areas', function (Blueprint $table) {
            // PASO 1: Verificar y añadir 'estado_oren_plantis' si no existe.
            // Esto es CRUCIAL para resolver el error "Column not found".
            // Asumimos que 'orden_plantis_numero' existe como punto de referencia seguro.
            if (!Schema::hasColumn('areas', 'estado_oren_plantis')) {
                $table->string('estado_oren_plantis', 255)->nullable()->after('orden_plantis_numero');
            }

            // PASO 2: Añadir 'aplica_orden_plantis'. Ahora 'estado_oren_plantis' está garantizado.
            $table->boolean('aplica_orden_plantis')->default(false)->after('estado_oren_plantis');

            // PASO 3: Añadir los demás campos nuevos.
            $table->decimal('area_total_finca_hectareas', 8, 2)->nullable()->after('area');
            $table->integer('numero_palmas_total_finca')->nullable()->after('area_total_finca_hectareas');
            $table->decimal('area_palmas_desarrollo_hectareas', 8, 2)->nullable()->after('numero_palmas_total_finca');
            $table->integer('numero_palmas_desarrollo')->nullable()->after('area_palmas_desarrollo_hectareas');
            $table->decimal('area_palmas_produccion_hectareas', 8, 2)->nullable()->after('numero_palmas_desarrollo');
            $table->integer('numero_palmas_produccion')->nullable()->after('area_palmas_produccion_hectareas');
            $table->integer('ciclos_cosecha')->nullable()->after('numero_palmas_produccion');
            $table->decimal('produccion_toneladas_por_mes', 8, 2)->nullable()->after('ciclos_cosecha');

            // PASO 4: Modificar columnas existentes para que sean nullable.
            // Verificar si existen antes de intentar cambiarlas.
            if (Schema::hasColumn('areas', 'orden_plantis_numero')) {
                $table->integer('orden_plantis_numero')->nullable()->change();
            }
            // 'estado_oren_plantis' ya fue manejado en el Paso 1. Si existía y no era nullable,
            // esta línea lo hará nullable. Si fue añadido en el Paso 1, ya es nullable.
            if (Schema::hasColumn('areas', 'estado_oren_plantis')) {
                 $table->string('estado_oren_plantis')->nullable()->change();
            }

            // PASO 5: Añadir el nuevo campo 'numero_plantas_orden_plantis'.
            $table->integer('numero_plantas_orden_plantis')->nullable()->after('orden_plantis_numero');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('areas', function (Blueprint $table) {
            // Eliminar todas las columnas añadidas en el método 'up'.
            $columnsToDrop = [
                'area_total_finca_hectareas',
                'numero_palmas_total_finca',
                'area_palmas_desarrollo_hectareas',
                'numero_palmas_desarrollo',
                'area_palmas_produccion_hectareas',
                'numero_palmas_produccion',
                'ciclos_cosecha',
                'produccion_toneladas_por_mes',
                'aplica_orden_plantis',
                'numero_plantas_orden_plantis'
            ];

            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('areas', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Revertir los cambios de 'nullable' si es necesario.
            // Esta parte es compleja ya que no sabemos el estado original de 'nullable'.
            // Si originalmente NO eran nullable, deberías descomentar y usar:
            // if (Schema::hasColumn('areas', 'orden_plantis_numero')) {
            //     $table->integer('orden_plantis_numero')->nullable(false)->change();
            // }
            // if (Schema::hasColumn('areas', 'estado_oren_plantis')) {
            //     $table->string('estado_oren_plantis')->nullable(false)->change();
            // }
            // Si no estás seguro, es más seguro dejar estos cambios de 'nullable' para manejo manual
            // o en una migración separada si es crítico.
        });
    }
};
