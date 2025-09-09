<?php

namespace App\Imports;

use App\Models\Fertilizacion;
use App\Models\Visita;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FertilizacionesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $visita = Visita::where('id', $row['visita_id'])->first();
        
        if (!$visita) {
            return null;
        }

        return new Fertilizacion([
            'visita_id' => $visita->id,
            'local_id' => $row['local_id'] ?? null,
            'fecha_fertilizacion' => $row['fecha_fertilizacion'] ?? null,
        ]);
    }
}