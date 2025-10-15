<?php

namespace App\Imports;

use App\Models\FuerzaLaboral;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class FuerzaLaboralImport implements ToModel, WithHeadingRow, WithStartRow
{
    /**
     * Indica que la lectura empieza desde la segunda fila (después del encabezado)
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * Procesa cada fila del Excel
     */
    public function model(array $row)
    {
        // Log de depuración
        Log::info('Fila leída desde Excel (fuerza laboral):', $row);

        // ✅ Convertir posibles arrays (campos multiselección)
        $forma_contratacion = $this->parseArrayField($row['forma_contratacion'] ?? null);

        // ✅ Construcción del registro
        $data = [
            'visita_social_id'     => $row['visita_social_id'] ?? null,
            'forma_contratacion'   => $forma_contratacion,
            'num_trabajadores'     => $row['num_trabajadores'] ?? null,
            'num_hombres'          => $row['num_hombres'] ?? null,
            'num_mujeres'          => $row['num_mujeres'] ?? null,
            'contrato_formal'      => $row['contrato_formal'] ?? null,
            'seguridad_social'     => $row['seguridad_social'] ?? null,
            'tipo_contrato'        => $row['tipo_contrato'] ?? null,
            'contrato_firmado'     => $row['contrato_firmado'] ?? null,
            'sg_sst'               => $row['sg_sst'] ?? null,
            'examenes_medicos'     => $row['examenes_medicos'] ?? null,
            'trabajadores_migrantes'=> $row['trabajadores_migrantes'] ?? null,
            'comprobantes_pago'    => $row['comprobantes_pago'] ?? null,
            'dotacion'             => $row['dotacion'] ?? null,
        ];

        // Evita guardar filas vacías
        if (!array_filter($data)) {
            Log::info('Fila vacía detectada en fuerza laboral, se omite.');
            return null;
        }

        // Log final del registro procesado
        Log::info('Fuerza laboral import final:', $data);

        return new FuerzaLaboral($data);
    }

    /**
     * Convierte una cadena separada por comas en un array JSON válido
     */
    private function parseArrayField($value)
    {
        if (empty($value)) return null;

        // Si ya es un array, devolverlo directamente
        if (is_array($value)) {
            return $value;
        }

        // Si viene como texto separado por comas, convertirlo
        if (is_string($value)) {
            return array_map('trim', explode(',', $value));
        }

        return [$value];
    }
}
