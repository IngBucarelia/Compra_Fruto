<?php

namespace App\Imports;

use App\Models\DatosPersonalesSocial;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class DatosPersonalesSocialImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        try {
            if (empty($row[0])) return null;

            return new DatosPersonalesSocial([
                'visita_social_id' => $row[0],
                'proveedor_id' => $row[1],
                'telefono' => $row[2],
                'sexo' => $row[3],
                'rnp' => $row[4],
                'fedepalma' => $row[5],
                'alfabetizado' => $row[6],
                'nivel_estudio' => $row[7],
                'otras_lineas' => $row[8],
                'fecha_nacimiento' => $row[9],
                'grupo_poblacional' => $row[10],
                'reside_predio' => $row[11],
                'administra_cultivo' => $row[12],
                'supervisa_cultivo' => $row[13],
                'realiza_cultivo' => $row[14],
                'anios_palmicultura' => $row[15],
                'internet' => $row[16],
                'tipo_persona' => $row[17],
                'red_social' => $row[18],
                'regimen_salud' => $row[19],
            ]);
        } catch (\Throwable $e) {
            Log::error('Error al importar DatosPersonalesSocial: ' . $e->getMessage());
            return null;
        }
    }
}
