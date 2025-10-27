<?php

namespace App\Imports;

use App\Models\MiembroHogar;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MiembroHogarImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        try {
            if (empty($row[0])) return null;

            return new MiembroHogar([
                'visita_social_id' => $row[0],
                'nombre' => $row[1],
                'documento' => $row[2],
                'sexo' => $row[3],
                'parentezco' => $row[4],
                'reside_predio' => $row[5],
                'sabe_leer' => $row[6],
                'nivel_estudio' => $row[7],
                'participa_labores' => $row[8],
            ]);
        } catch (\Throwable $e) {
            Log::error('Error al importar MiembroHogar: ' . $e->getMessage());
            return null;
        }
    }
}
