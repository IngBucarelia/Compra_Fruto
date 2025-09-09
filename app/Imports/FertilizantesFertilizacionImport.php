<?php

namespace App\Imports;

use App\Models\FertilizanteFertilizacion;
use App\Models\Fertilizacion;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FertilizantesFertilizacionImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $fertilizacion = Fertilizacion::where('id', $row['fertilizacion_id'])
                            ->orWhere('local_id', $row['fertilizacion_local_id'] ?? null)
                            ->first();
        
        if (!$fertilizacion) {
            return null;
        }

        return new FertilizanteFertilizacion([
            'fertilizacion_id' => $fertilizacion->id,
            'local_id' => $row['local_id'] ?? null,
            'fertilizante' => $row['fertilizante'] ?? null,
            'cantidad' => $row['cantidad'] ?? null,
            'fecha_aplicacion' => $row['fecha_aplicacion'] ?? null,
            'unidad_medida' => $row['unidad_medida'] ?? null,
        ]);
    }
}