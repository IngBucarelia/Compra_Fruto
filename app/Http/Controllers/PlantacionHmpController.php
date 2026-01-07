<?php

namespace App\Http\Controllers;

use App\Models\PlantacionHmp;
use App\Models\VisitaAmbiental;
use Illuminate\Http\Request;

class PlantacionHmpController extends Controller
{
    public function create($visitaId)
    {
         $visita = VisitaAmbiental::with([
            'aguaCaptacionLegal',
            'aguaUsoEficiente',
            'sueloConservacion'
        ])->findOrFail($visitaId);
        
        // Obtener área total usando el Service
        $areaTotalFinca = \App\Services\AreaService::obtenerAreaTotalFinca($visita);
        
        return view('plantacion_hmp.create', [
        'visita' => $visita,
        'areaTotalFinca' => $areaTotalFinca
    ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'visita_ambiental_id' => 'required|exists:visita_ambientals,id',
            'implementa_hmp' => 'required|in:si,no',
            'incluye_hmp_disenio' => 'required|in:si,no',
            'hectareas_hmp' => 'nullable|numeric|min:0',
            'porcentaje_hmp' => 'nullable|numeric|min:0|max:100',
            'observaciones' => 'nullable|string'
        ]);
        
        // Si no implementa HMP, limpiar campos
        if ($request->implementa_hmp === 'no') {
            $validated['hectareas_hmp'] = null;
            $validated['porcentaje_hmp'] = null;
        }
        
        PlantacionHMP::create($validated);
        
        return redirect()
            ->route('plantacion_avc.create', $request->visita_ambiental_id)
            ->with('success', 'Información de HMP guardada correctamente');
    }


    public function edit($id)
    {
        $registro = PlantacionHmp::findOrFail($id);
        return view('plantacion_hmp.edit', compact('registro'));
    }


    public function update(Request $request, $id)
    {
        $registro = PlantacionHmp::findOrFail($id);
        $registro->update($request->all());

        return redirect()->route('visitasAmbientales.show', $registro->visita_ambiental_id)
                         ->with('success', 'Registro actualizado');
    }

    public function show($id)
    {
        $registro = PlantacionHmp::findOrFail($id);
        return view('plantacion_hmp.show', compact('registro'));
    }

    public function destroy($id)
    {
        $registro = PlantacionHmp::findOrFail($id);
        $visitaId = $registro->visita_ambiental_id;
        $registro->delete();

        return redirect()->route('visitasAmbientales.show', $visitaId)
                         ->with('success', 'Registro eliminado');
    }
}
