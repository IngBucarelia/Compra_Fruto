<?php

namespace App\Imports;

use App\Models\DatoPredioSocial;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class DatoPredioSocialImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        try {
            if (empty($row[0])) return null;

            return new DatoPredioSocial([
                'visita_social_id' => $row[0],
                'plantacion_id' => $row[1],
                'nombre_finca' => $row[2],
                'forma_tenencia' => $row[3],
                'municipio' => $row[4],
                'vereda' => $row[5],
                'registrado_ica' => $row[6],
                'vive_predio' => $row[7],
                'infraestructura_vial' => json_decode($row[8] ?? '[]', true),
                'infraestructura_predio' => $row[9],
            ]);
        } catch (\Throwable $e) {
            Log::error('Error al importar DatoPredioSocial: ' . $e->getMessage());
            return null;
        }
    }
}
