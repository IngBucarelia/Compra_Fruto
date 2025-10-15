<?php

namespace App\Imports;

use App\Models\Visita;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class VisitasImport implements ToModel, WithHeadingRow
{
   public function model(array $row)
{
    $fechaConvertida = null;

    if (!empty($row['fecha'])) {
        if (is_numeric($row['fecha'])) {
            $fechaConvertida = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['fecha'])->format('Y-m-d');
        } else {
            try {
                $fechaConvertida = \Carbon\Carbon::parse($row['fecha'])->format('Y-m-d');
            } catch (\Exception $e) {
                Log::error("Error al parsear fecha: {$row['fecha']}");
            }
        }
    }

    $data = [
        'fecha'          => $fechaConvertida,
        'proveedor_id'   => $row['proveedor_id'] ?? null,
        'plantacion_id'  => $row['plantacion_id'] ?? null,
        'ubicacion'      => $row['ubicacion'] ?? null,
        'tecnico_campo'  => $row['tecnico_campo'] ?? null,
        'tipo_visita'    => $row['tipo_visita'] ?? null,
        'recibio_visita' => $row['recibio_visita'] ?? null,
        'planificacion_id' => $row['planificacion_id'] ?? null,
        'estado'         => $row['estado'] ?? null,
    ];

    // 👀 Si toda la fila está vacía, no guardamos nada
    if (!array_filter($data)) {
        Log::info("Fila vacía detectada, se omite.");
        return null;
    }

    Log::info('VISITA IMPORT DATA: ', $data);

    return new Visita($data);
}

}
