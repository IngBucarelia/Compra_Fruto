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
        $visita = VisitaAmbiental::findOrFail($visitaId);
        return view('plantacion_avc.create', compact('visita'));
    }

    public function store(Request $request)
    {
        PlantacionAVC::create($request->all());
        return redirect()->route('visitasAmbientales.show', $request->visita_ambiental_id)
                         ->with('success', 'Registro guardado correctamente');
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
