<?php

namespace App\Imports;

use App\Models\Area;
use App\Models\Visita;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AreasImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Buscar la visita por algún identificador único
        $visita = Visita::where('id', $row['visita_id'])->first();
        
        if (!$visita) {
            return null;
        }

        return new Area([
            'visita_id' => $visita->id,
            'variedad' => $row['variedad'] ?? null,
            'material' => $row['material'] ?? null,
            'estado' => $row['estado'] ?? null,
            'anio_siembra' => $row['anio_siembra'] ?? null,
            'area' => $row['area'] ?? null,
            'orden_plantis_numero' => $row['orden_plantis_numero'] ?? null,
            'estado_oren_plantis' => $row['estado_oren_plantis'] ?? null,
            'area_total_finca_hectareas' => $row['area_total_finca_hectareas'] ?? null,
            'numero_palmas_total_finca' => $row['numero_palmas_total_finca'] ?? null,
            'area_palmas_desarrollo_hectareas' => $row['area_palmas_desarrollo_hectareas'] ?? null,
            'numero_palmas_desarrollo' => $row['numero_palmas_desarrollo'] ?? null,
            'area_palmas_produccion_hectareas' => $row['area_palmas_produccion_hectareas'] ?? null,
            'numero_palmas_produccion' => $row['numero_palmas_produccion'] ?? null,
            'ciclos_cosecha' => $row['ciclos_cosecha'] ?? null,
            'produccion_toneladas_por_mes' => $row['produccion_toneladas_por_mes'] ?? null,
            'aplica_orden_plantis' => $row['aplica_orden_plantis'] ?? null,
            'numero_plantas_orden_plantis' => $row['numero_plantas_orden_plantis'] ?? null,
        ]);
    }
}