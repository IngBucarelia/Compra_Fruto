<?php

namespace App\Exports;

use App\Models\Visita;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Contracts\View\View;

class VisitaExport implements FromView, WithStyles
{
    protected $id;

    public function __construct($visita_id)
    {
        $this->id = $visita_id;
    }

    public function view(): View
    {
        $visita = Visita::with([
            'proveedor', 'plantacion', 'area',
            'fertilizaciones.fertilizantes',
            'polinizaciones',
            'sanidad',
            'suelo',
            'laboresCultivo',
            'evaluacionCosechaCampo',
            'cierreVisita'
        ])->findOrFail($this->id);

        return view('exports.visita_excel', compact('visita'));
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A1:Z1' => [
                'font' => ['bold' => true, 'size' => 14],
                'fill' => ['fillType' => 'solid', 'color' => ['rgb' => 'DFF0D8']],
                'alignment' => ['horizontal' => 'center'],
            ],
            'A2:Z100' => [
                'alignment' => ['vertical' => 'center'],
            ],
        ];
    }
}

