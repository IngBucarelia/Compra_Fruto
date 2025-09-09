<?php

// app/Imports/VisitasMultiSheetImport.php
namespace App\Imports;

use App\Models\Visita;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class VisitasMultiSheetImport implements WithMultipleSheets
{
    private $visita;
    
    public function __construct(Visita $visita)
    {
        $this->visita = $visita;
    }

    public function sheets(): array
    {
        return [
            // The main importer has already been executed.
            // Here are the other importers that depend on the Visita object.
            'Areas' => new AreaImport($this->visita),
            'Fertilizaciones' => new FertilizacionImport($this->visita),
            'Polinizaciones' => new PolinizacionImport($this->visita),
            'Sanidades' => new SanidadImport($this->visita),
            'Suelos' => new SueloImport($this->visita),
            'LaboresCultivo' => new LaboresCultivoImport($this->visita),
            'EvaluacionCosechaCampo' => new EvaluacionCosechaCampoImport($this->visita),
            'CierreVisita' => new CierreVisitaImport($this->visita),
        ];
    }
}
