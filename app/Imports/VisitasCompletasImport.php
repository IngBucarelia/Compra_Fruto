<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class VisitasCompletasImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'areas' => new AreasImport(),
            'fertilizaciones' => new FertilizacionesImport(),
            'fertilizantes_fertilizacion' => new FertilizantesFertilizacionImport(),
            'polinizaciones' => new PolinizacionesImport(),
            'sanidades' => new SanidadesImport(),
            'suelos' => new SuelosImport(),
            'labores_cultivo' => new LaboresCultivoImport(),
            'evaluacion_cosecha_campo' => new EvaluacionCosechaCampoImport(),
            'cierre_visita' => new CierreVisitaImport(),
        ];
    }
}