<?php

namespace App\Imports;

use App\Models\VisitaSocial;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class VisitasSocialImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // ✅ Omitir filas completamente vacías o con encabezados
        if (
            empty($row['fecha']) &&
            empty($row['proveedor_id']) &&
            empty($row['plantacion_id'])
        ) {
            Log::info('Fila vacía o no válida detectada, se omite.');
            return null;
        }

        // ✅ Convertir fecha si es numérica o tipo texto
        $fecha = null;
        if (!empty($row['fecha'])) {
            try {
                if (is_numeric($row['fecha'])) {
                    $fecha = Date::excelToDateTimeObject($row['fecha'])->format('Y-m-d');
                } else {
                    $fecha = date('Y-m-d', strtotime(str_replace('/', '-', $row['fecha'])));
                }
            } catch (\Exception $e) {
                Log::warning('Fecha no válida: ' . $row['fecha']);
                $fecha = null;
            }
        }

        // ✅ Crear registro solo si hay fecha válida
        if ($fecha === null) {
            Log::info('Fila omitida porque no tiene fecha válida.');
            return null;
        }

        $data = [
            'fecha'            => $fecha,
            'proveedor_id'     => $row['proveedor_id'] ?? null,
            'plantacion_id'    => $row['plantacion_id'] ?? null,
            'ubicacion'        => $row['ubicacion'] ?? null,
            'tecnico_campo'    => $row['tecnico_campo'] ?? null,
            'tipo_visita'      => $row['tipo_visita'] ?? null,
            'recibio_visita'   => $row['recibio_visita'] ?? null,
            'planificacion_id' => $row['planificacion_id'] ?? null,
            'estado'           => $row['estado'] ?? 'finalizada',
        ];

        Log::info('VISITA SOCIAL IMPORT FINAL:', $data);

        return new VisitaSocial($data);
    }
}
