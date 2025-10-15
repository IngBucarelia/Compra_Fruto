<?php

namespace App\Imports;

use App\Models\Polinizacion;
use App\Models\Visita;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PolinizacionesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $visita = Visita::where('id', $row['visita_id'])->first();
        
        if (!$visita) {
            return null;
        }

        return new Polinizacion([
            'visita_id' => $visita->id,
            'n_pases' => $row['n_pases'] ?? null,
            'ciclos_ronda' => $row['ciclos_ronda'] ?? null,
            'ana' => $row['ana'] ?? null,
            'tipo_ana' => $row['tipo_ana'] ?? null,
            'talco' => $row['talco'] ?? null,
            'fecha' => $row['fecha'] ?? null,
        ]);
    }
}