<?php

namespace App\Http\Controllers;

use App\Models\LaboresCultivo;
use App\Models\Visita;
use Illuminate\Http\Request;

class LaboresCultivoController extends Controller
{
   public function create(Request $request)
        {
            $visita = Visita::with([
                'area',
                'fertilizaciones.fertilizantes',
                'polinizaciones',
                'sanidad',
                'suelo'
            ])->findOrFail($request->query('visita_id'));

            return view('labores_cultivo.create', compact('visita'));
        }

        public function store(Request $request)
        {
            $data = $request->validate([
                'visita_id' => 'required|exists:visitas,id',
                'polinizacion' => 'nullable|integer|min:0|max:100',
                'limpieza_calle' => 'nullable|integer|min:0|max:100',
                'limpieza_plato' => 'nullable|integer|min:0|max:100',
                'poda' => 'nullable|integer|min:0|max:100',
                'fertilizacion' => 'nullable|integer|min:0|max:100',
                'enmiendas' => 'nullable|integer|min:0|max:100',
                'ubicacion_tusa_fibra' => 'nullable|integer|min:0|max:100',
                'ubicacion_hoja' => 'nullable|integer|min:0|max:100',
                'lugar_ubicacion_hoja' => 'nullable|integer|min:0|max:100',
                'plantas_nectariferas' => 'nullable|integer|min:0|max:100',
                'cobertura' => 'nullable|integer|min:0|max:100',
                'labor_cosecha' => 'nullable|integer|min:0|max:100',
                'calidad_fruta' => 'nullable|integer|min:0|max:100',
                'recoleccion_fruta' => 'nullable|integer|min:0|max:100',
                'drenajes' => 'nullable|integer|min:0|max:100',
            ]);

            LaboresCultivo::create($data);

            return redirect()->route('visitas.show', $data['visita_id'])
                ->with('success', '✅ Registro de labores de cultivo guardado correctamente.');
        }

}
