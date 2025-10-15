<?php

namespace App\Imports;

use App\Models\Sanidad;
use App\Models\Visita;
use App\Models\SanidadEnfermedad;
use App\Models\SanidadPlaga;
use App\Models\TrampaPalmarum;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class SanidadesImport implements ToModel, WithHeadingRow
{
    // Mantener registro de las sanidades ya creadas por visita
    private $processedVisitas = [];

    public function model(array $row)
    {
        if (empty($row['visita_id'])) {
            return null;
        }

        $visita = Visita::find($row['visita_id']);
        if (!$visita) {
            return null;
        }

        // Si ya procesamos esta visita, solo agregamos las relaciones
        if (isset($this->processedVisitas[$visita->id])) {
            $sanidad = $this->processedVisitas[$visita->id];
        } else {
            // Crear nueva sanidad
            $sanidad = Sanidad::create([
                'visita_id' => $visita->id,
                'local_id' => $row['local_id'] ?? null,
                'otros' => $row['otros'] ?? null,
                'observaciones' => $row['observaciones'] ?? null,
                'censo_enfermedades' => $this->parseBoolean($row['censo_enfermedades'] ?? null),
                'ciclos_lectura_enfermedades' => $row['ciclos_lectura_enfermedades'] ?? null,
                'ciclos_lectura_plagas' => $row['ciclos_lectura_plagas'] ?? null,
            ]);
            
            $this->processedVisitas[$visita->id] = $sanidad;
        }

        // Crear enfermedad si existe
        if (!empty($row['enfermedad'])) {
            SanidadEnfermedad::create([
                'sanidad_id' => $sanidad->id,
                'nombre_enfermedad' => $row['enfermedad'],
                'estado' => $row['estado_enfermedad'] ?? null,
            ]);
        }

        // Crear plaga si existe
        if (!empty($row['plaga'])) {
            SanidadPlaga::create([
                'sanidad_id' => $sanidad->id,
                'nombre_plaga' => $row['plaga'],
                'estado' => $row['estado_plaga'] ?? null,
            ]);
        }

        // Crear trampa si existe
        if (!empty($row['ciclos_trampas']) || !empty($row['machos_trampas']) || !empty($row['hembras_trampas'])) {
            TrampaPalmarum::create([
                'sanidad_id' => $sanidad->id,
                'ciclos' => $row['ciclos_trampas'] ?? null,
                'machos_capturados' => $row['machos_trampas'] ?? null,
                'hembras_capturadas' => $row['hembras_trampas'] ?? null,
            ]);
        }

        return null;
    }

    private function parseBoolean($value)
    {
        if (is_bool($value)) return $value;
        if (is_numeric($value)) return (bool)$value;
        
        $value = strtolower(trim($value ?? ''));
        return in_array($value, ['si', 'sí', 'yes', 'true', '1', 'verdadero']);
    }
}