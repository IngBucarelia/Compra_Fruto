<?php

namespace App\Imports;

use App\Models\CierreVisitaSocial;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CierreVisitaSocialImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        try {
            if (empty($row[0])) return null;

            return new CierreVisitaSocial([
                'visita_social_id' => $row[0],
                'fecha_cierre' => $row[1],
                'estado_visita' => $row[2],
                'observaciones_finales' => $row[3],
                'recomendaciones' => $row[4],
                'firma_responsable' => $row[5],
                'firma_recibe' => $row[6],
                'firma_testigo' => $row[7],
                'imagenes' => json_decode($row[8] ?? '[]', true),
                'finalizada_en' => $row[9],
            ]);
        } catch (\Throwable $e) {
            Log::error('Error al importar CierreVisitaSocial: ' . $e->getMessage());
            return null;
        }
    }
}
