<?php

namespace App\Http\Controllers;

use App\Models\PlantacionAVC;
use App\Models\VisitaAmbiental;
use Illuminate\Http\Request;

class PlantacionAVCController extends Controller
{
    public function index($visitaId)
    {
        $visita = VisitaAmbiental::findOrFail($visitaId);
        $registros = PlantacionAVC::where('visita_ambiental_id', $visitaId)->get();

        return view('plantacion_avc.index', compact('visita', 'registros'));
    }


    public function create($visitaId)
{
    $visita = VisitaAmbiental::with([
        'aguaCaptacionLegal',
        'aguaUsoEficiente',
        'sueloConservacion',
        'energiaManejo',
        'gobernanzaHidrica',
        'emisionesGei',
        'residuosManejo',
        'sustanciasQuimicasBiologicas',
        'vertimientoManejo',  // o 'vertimientosManejo' dependiendo de cuál uses
        'hmpManejo'  // AÑADIR ESTA RELACIÓN
    ])->findOrFail($visitaId);
    
    // Obtener área total usando el Service
    $areaTotalFinca = \App\Services\AreaService::obtenerAreaTotalFinca($visita);
    
    return view('plantacion_avc.create', [
        'visita' => $visita,
        'areaTotalFinca' => $areaTotalFinca
    ]);
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'visita_ambiental_id' => 'required|exists:visita_ambientals,id',
            'registros_avistamientos' => 'required|in:si,no',
            'identifica_avc_arc' => 'required|in:si,no',
            'especies_identificadas' => 'nullable|string|required_if:identifica_avc_arc,si',
            'fecha_identificacion' => 'nullable|date|required_if:identifica_avc_arc,si',
            'ubicacion_identificacion' => 'nullable|string|max:255',
            'tipo_identificacion' => 'nullable|array',
            'tipo_identificacion.*' => 'in:avistamiento_directo,rastros_huellas,monitoreo_camaras',
            'implementa_medidas_manejo' => 'required|in:si,no',
            'observaciones' => 'nullable|string'
        ]);
        
        // Si no identifica AVC/ARC, limpiar campos relacionados
        if ($request->identifica_avc_arc === 'no') {
            $validated['especies_identificadas'] = null;
            $validated['fecha_identificacion'] = null;
            $validated['ubicacion_identificacion'] = null;
            $validated['tipo_identificacion'] = null;
        }
        
        PlantacionAvc::create($validated);
        
        return redirect()
            ->route('pno_reemplazo.create', $request->visita_ambiental_id)
            ->with('success', 'Información de AVC guardada correctamente');
    }

    public function edit($id)
    {
        $registro = PlantacionAVC::findOrFail($id);
        return view('plantacion_avc.edit', compact('registro'));
    }

    public function update(Request $request, $id)
    {
        $registro = PlantacionAVC::findOrFail($id);
        $registro->update($request->all());

        return redirect()->route('visitasAmbientales.show', $registro->visita_ambiental_id)
                         ->with('success', 'Registro actualizado correctamente');
    }

    public function destroy($id)
    {
        $registro = PlantacionAVC::findOrFail($id);
        $visitaID = $registro->visita_ambiental_id;

        $registro->delete();

        return redirect()->route('visitasAmbientales.show', $visitaID)
                         ->with('success', 'Registro eliminado correctamente');
    }
}
