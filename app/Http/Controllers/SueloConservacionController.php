<?php

namespace App\Http\Controllers;

use App\Models\SueloConservacion;
use App\Models\VisitaAmbiental;
use Illuminate\Http\Request;

class SueloConservacionController extends Controller
{
    public function create($visitaAmbientalId)
    {
        $visita = VisitaAmbiental::findOrFail($visitaAmbientalId);
        return view('suelo_conservacion.create', compact('visita'));
    }

    public function store(Request $request)
    {
        // DEPURACIÓN: Ver qué datos llegan
        // \Log::info('Datos recibidos en SueloConservacion:', $request->all());
        
        // Validar los datos
        $validated = $request->validate([
            'visita_ambiental_id' => 'required|exists:visita_ambientals,id',
            'usa_fuego_preparacion' => 'nullable|in:0,1',
            'control_coberturas_invasoras' => 'nullable|in:0,1',
            'siembra_coberturas' => 'nullable|in:0,1',
            'sigue_recomendaciones_comerciales' => 'nullable|in:0,1',
            'area_cobertura' => 'nullable|numeric|min:0',
            'tipo_cobertura' => 'nullable|string|max:100',
            'otro_tipo_cobertura' => 'nullable|string|max:255',
            'porcentaje_cobertura' => 'nullable|numeric|min:0|max:100',
            'observaciones' => 'nullable|string'
        ]);
        
        // Preparar los datos booleanos correctamente
        $data = [
            'visita_ambiental_id' => $validated['visita_ambiental_id'],
            'usa_fuego_preparacion' => $request->input('usa_fuego_preparacion') === '1',
            'control_coberturas_invasoras' => $request->input('control_coberturas_invasoras') === '1',
            'siembra_coberturas' => $request->input('siembra_coberturas') === '1',
            'sigue_recomendaciones_comerciales' => $request->input('sigue_recomendaciones_comerciales') === '1',
            'area_cobertura' => $request->input('area_cobertura'),
            'tipo_cobertura' => $request->input('tipo_cobertura'),
            'otro_tipo_cobertura' => $request->input('otro_tipo_cobertura'),
            'porcentaje_cobertura' => $request->input('porcentaje_cobertura'),
            'observaciones' => $request->input('observaciones'),
        ];
        
        // Limpiar campos si no siembra coberturas
        if ($request->input('siembra_coberturas') !== '1') {
            $data['area_cobertura'] = null;
            $data['tipo_cobertura'] = null;
            $data['otro_tipo_cobertura'] = null;
            $data['porcentaje_cobertura'] = null;
        }
        
        // Crear el registro
        SueloConservacion::create($data);
        
        // 🔥 CORRECCIÓN: Redirigir usando el parámetro CORRECTO 'visitaId'
        return redirect()->route('energia.create', [
            'visitaId' => $validated['visita_ambiental_id'] // Cambiado de 'visita_ambiental_id' a 'visitaId'
        ])->with('success', '✅ Componente de suelo y conservación guardado correctamente.');
    }

    public function edit($id)
    {
        $componente = SueloConservacion::findOrFail($id);
        return view('suelo_conservacion.edit', compact('componente'));
    }

    public function update(Request $request, $id)
    {
        $componente = SueloConservacion::findOrFail($id);

        $componente->update([
            'usa_fuego_preparacion' => $request->has('usa_fuego_preparacion'),
            'control_coberturas_invasoras' => $request->has('control_coberturas_invasoras'),
            'siembra_coberturas' => $request->has('siembra_coberturas'),
            'sigue_recomendaciones_comerciales' => $request->has('sigue_recomendaciones_comerciales'),
        ]);

        return redirect()->back()->with('success', 'Componente actualizado correctamente.');
    }
}
