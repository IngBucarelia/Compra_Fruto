<?php

namespace App\Imports;

use App\Models\LaboresCultivo;
use App\Models\Visita;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LaboresCultivoImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $visita = Visita::where('id', $row['visita_id'])->first();
        
        if (!$visita) {
            return null;
        }

        return new LaboresCultivo([
            'visita_id' => $visita->id,
            'tipo_planta' => $row['tipo_planta'] ?? null,
            'polinizacion' => $row['polinizacion'] ?? null,
            'limpieza_calle' => $row['limpieza_calle'] ?? null,
            'limpieza_plato' => $row['limpieza_plato'] ?? null,
            'poda' => $row['poda'] ?? null,
            'fertilizacion' => $row['fertilizacion'] ?? null,
            'enmiendas' => $row['enmiendas'] ?? null,
            'ubicacion_tusa_fibra' => $row['ubicacion_tusa_fibra'] ?? null,
            'ubicacion_hoja' => $row['ubicacion_hoja'] ?? null,
            'lugar_ubicacion_hoja' => $row['lugar_ubicacion_hoja'] ?? null,
            'plantas_nectariferas' => $row['plantas_nectariferas'] ?? null,
            'cobertura' => $row['cobertura'] ?? null,
            'labor_cosecha' => $row['labor_cosecha'] ?? null,
            'calidad_fruta' => $row['calidad_fruta'] ?? null,
            'recoleccion_fruta' => $row['recoleccion_fruta'] ?? null,
            'drenajes' => $row['drenajes'] ?? null,
            'observaciones' => $row['observaciones'] ?? null,
        ]);
    }
}