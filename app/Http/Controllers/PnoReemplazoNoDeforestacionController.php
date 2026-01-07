<?php

namespace App\Http\Controllers;

use App\Models\PnoReemplazoNodeforestacion;
use App\Models\VisitaAmbiental;
use Illuminate\Http\Request;

class PnoReemplazoNoDeforestacionController extends Controller
{
    public function create($visitaId)
    {
        $visita = VisitaAmbiental::findOrFail($visitaId);
        return view('pno_reemplazo.create', compact('visita'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'visita_ambiental_id' => 'required|exists:visita_ambientals,id',
            'cuenta_estudios_avc_arc' => 'required|in:1,0',
            'evidencias_no_reemplazo_bosques' => 'required|in:1,0',
            'permiso_aprovechamiento_forestal' => 'required|in:1,0',
            'restauracion_compensacion' => 'required|in:1,0',
            'hectareas_restauracion' => 'nullable|numeric|min:0|required_if:restauracion_compensacion,1',
            'fecha_restauracion' => 'nullable|date|required_if:restauracion_compensacion,1',
            'tipo_restauracion' => 'nullable|string|max:50|required_if:restauracion_compensacion,1',
            'otro_tipo_restauracion' => 'nullable|string|max:100|required_if:tipo_restauracion,otro',
            'ubicacion_restauracion' => 'nullable|string|max:255',
            'porcentaje_restauracion' => 'nullable|numeric|min:0|max:100',
            'dentro_frontera_agricola' => 'required|in:1,0',
            'observaciones' => 'nullable|string'
        ]);
        
        // Si no realizó restauración, limpiar campos relacionados
        if ($request->restauracion_compensacion == '0') {
            $validated['hectareas_restauracion'] = null;
            $validated['fecha_restauracion'] = null;
            $validated['tipo_restauracion'] = null;
            $validated['otro_tipo_restauracion'] = null;
            $validated['ubicacion_restauracion'] = null;
            $validated['porcentaje_restauracion'] = null;
        }
        
        PnoReemplazoNodeforestacion::create($validated);
        
        return redirect()
            ->route('cierre-visitas-ambiental.create', $request->visita_ambiental_id)
            ->with('success', 'Información de No Reemplazo y No Deforestación guardada correctamente');
    }

    public function edit($id)
    {
        $registro = PnoReemplazoNodeforestacion::findOrFail($id);
        return view('pno_reemplazo.edit', compact('registro'));
    }

    public function update(Request $request, $id)
    {
        $registro = PnoReemplazoNodeforestacion::findOrFail($id);
        $registro->update($request->all());

        return redirect()->route('visitasAmbientales.show', $registro->visita_ambiental_id)
            ->with('success', 'Registro actualizado');
    }

    public function destroy($id)
    {
        $registro = PnoReemplazoNodeforestacion::findOrFail($id);
        $visitaId = $registro->visita_ambiental_id;
        $registro->delete();

        return redirect()->route('visitasAmbientales.show', $visitaId)
            ->with('success', 'Registro eliminado');
    }
}
