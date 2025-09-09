<?php

namespace App\Imports;

use App\Models\Visita;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VisitasImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Visita([
            'fecha'         => $row['fecha'], // formato YYYY-MM-DD
            'proveedor_id'  => $row['proveedor_id'], 
            'plantacion_id' => $row['plantacion_id'],
            'ubicacion'     => $row['ubicacion'],
            'tecnico_campo' => $row['tecnico_campo'],
            'tipo_visita'   => $row['tipo_visita'],
            'recibio_visita'=> $row['recibio_visita'],
            'planificacion_id' => $row['planificacion_id'],
            'estado'        => $row['estado'],
        ]);
    }
}
