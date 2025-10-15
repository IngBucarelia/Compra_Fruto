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
        // ✅ CAMBIO CLAVE: Cargar las relaciones como colecciones donde corresponda
        $visita = Visita::with([
            'proveedor',
            'plantacion',
            'areas', // ✅ CAMBIO: 'areas' en plural para hasMany
            'fertilizaciones.detalles', // ✅ CAMBIO: 'detalles' en lugar de 'fertilizantes'
            'polinizaciones',
            'sanidad',
            'suelo',
            'laboresCultivo', // ✅ Ahora es hasMany
            'evaluacionCosechaCampo', // ✅ Ahora es hasMany
            'cierreVisita',
            'tecnico' // Asegúrate de cargar esta relación si la usas en la vista de Excel
        ])->findOrFail($this->id);

        return view('exports.visita_excel', compact('visita'));
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A1:Z1' => [
                'font' => ['bold' => true, 'size' => 14],
                'fill' => ['fillType' => 'solid', 'color' => ['rgb' => 'DFF0D8']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            ],
            'A2:Z1000' => [
                'alignment' => ['vertical' => 'top'],
            ],
        ];
    }
}
