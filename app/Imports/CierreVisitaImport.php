<?php

namespace App\Imports;

use App\Models\CierreVisita;
use App\Models\Visita;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CierreVisitaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $visita = Visita::where('id', $row['visita_id'])->first();
        
        if (!$visita) {
            return null;
        }

        return new CierreVisita([
            'visita_id' => $visita->id,
            'fecha_cierre' => $row['fecha_cierre'] ?? null,
            'estado_visita' => $row['estado_visita'] ?? null,
            'observaciones_finales' => $row['observaciones_finales'] ?? null,
            'recomendaciones' => $row['recomendaciones'] ?? null,
            'firma_responsable' => $row['firma_responsable'] ?? null,
            'firma_recibe' => $row['firma_recibe'] ?? null,
            'firma_testigo' => $row['firma_testigo'] ?? null,
            'imagenes' => $row['imagenes'] ?? null,
            'finalizada_en' => $row['finalizada_en'] ?? null,
        ]);
    }
}