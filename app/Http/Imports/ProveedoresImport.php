<?php

namespace App\Imports;

use App\Models\Proveedor;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProveedoresImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Proveedor([
            'proveedor_nombre' => $row['proveedor_nombre'],
            'nit' => $row['nit'],
            'dia_creado' => \Carbon\Carbon::parse($row['dia_creado']),
        ]);
    }
}