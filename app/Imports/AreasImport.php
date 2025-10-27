<?php

namespace App\Imports;

use App\Models\Area;
use App\Models\Visita;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date; // 👈 importante

class AreasImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Buscar la visita por algún identificador único
        $visita = Visita::where('id', $row['visita_id'])->first();
        if (!$visita) {
            return null;
        }

        // Convertir fecha si existe y no es nula
        $anioSiembra = null;
        if (!empty($row['anio_siembra'])) {
            if (is_numeric($row['anio_siembra'])) {
                // Excel date number -> PHP DateTime
                $anioSiembra = Date::excelToDateTimeObject($row['anio_siembra'])->format('Y-m-d');
            } else {
                // Si viene como texto tipo "2024-10-01"
                $anioSiembra = date('Y-m-d', strtotime($row['anio_siembra']));
            }
        }

        return new Area([
            'visita_id' => $visita->id,
            'variedad' => $row['variedad'] ?? null,
            'material' => $row['material'] ?? null,
            'estado' => $row['estado'] ?? null,
            'anio_siembra' => $anioSiembra,
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
