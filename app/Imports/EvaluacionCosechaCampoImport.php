<?php

namespace App\Imports;

use App\Models\EvaluacionCosechaCampo;
use App\Models\Visita;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EvaluacionCosechaCampoImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $visita = Visita::where('id', $row['visita_id'])->first();
        
        if (!$visita) {
            return null;
        }

        return new EvaluacionCosechaCampo([
            'visita_id' => $visita->id,
            'indexeddb_id' => $row['indexeddb_id'] ?? null,
            'variedad_fruto' => $row['variedad_fruto'] ?? null,
            'cantidad_racimos' => $row['cantidad_racimos'] ?? null,
            'verde' => $row['verde'] ?? null,
            'maduro' => $row['maduro'] ?? null,
            'sobremaduro' => $row['sobremaduro'] ?? null,
            'pedunculo' => $row['pedunculo'] ?? null,
            'conformacion' => $row['conformacion'] ?? null,
            'observaciones' => $row['observaciones'] ?? null,
        ]);
    }
}