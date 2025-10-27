<?php

namespace App\Imports;

use App\Models\OrganizacionSocial;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class OrganizacionSocialImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        try {
            if (empty($row[0])) return null;

            return new OrganizacionSocial([
                'visita_id' => $row[0],
                'pertenece_jac' => $row[1],
                'pertenece_asociacion' => $row[2],
                'nombre_asociacion' => $row[3],
            ]);
        } catch (\Throwable $e) {
            Log::error('Error al importar OrganizacionSocial: ' . $e->getMessage());
            return null;
        }
    }
}
