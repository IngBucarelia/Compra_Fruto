<?php

namespace App\Imports;

use App\Models\Suelo;
use App\Models\Visita;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SuelosImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $visita = Visita::where('id', $row['visita_id'])->first();
        
        if (!$visita) {
            return null;
        }

        return new Suelo([
            'visita_id' => $visita->id,
            'analisis_foliar' => $row['analisis_foliar'] ?? null,
            'analisis_suelo' => $row['analisis_suelo'] ?? null,
            'tipo_suelo' => $row['tipo_suelo'] ?? null,
        ]);
    }
}