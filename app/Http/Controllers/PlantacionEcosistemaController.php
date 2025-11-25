<?php

namespace App\Http\Controllers;

use App\Models\PlantacionEcosistema;
use App\Models\VisitaAmbiental;
use Illuminate\Http\Request;

class PlantacionEcosistemaController extends Controller
{
    public function index($visitaId)
    {
        $visita = VisitaAmbiental::findOrFail($visitaId);
        $registros = PlantacionEcosistema::where('visita_ambiental_id', $visitaId)->get();

        return view('plantacion_ecosistemas.index', compact('visita', 'registros'));
    }

    public function create($visitaId)
    {
        $visita = VisitaAmbiental::findOrFail($visitaId);
        return view('plantacion_ecosistemas.create', compact('visita'));
    }

    public function store(Request $request)
    {
        PlantacionEcosistema::create($request->all());

        return redirect()->route('visitasAmbientales.show', $request->visita_ambiental_id)
                         ->with('success', 'Registro guardado correctamente');
    }

    public function edit($id)
    {
        $registro = PlantacionEcosistema::findOrFail($id);
        return view('plantacion_ecosistemas.edit', compact('registro'));
    }

    public function update(Request $request, $id)
    {
        $registro = PlantacionEcosistema::findOrFail($id);
        $registro->update($request->all());

        return redirect()->route('visitasAmbientales.show', $registro->visita_ambiental_id)
                         ->with('success', 'Registro actualizado correctamente');
    }

    public function destroy($id)
    {
        $registro = PlantacionEcosistema::findOrFail($id);
        $visitaID = $registro->visita_ambiental_id;

        $registro->delete();

        return redirect()->route('visitasAmbientales.show', $visitaID)
                         ->with('success', 'Registro eliminado correctamente');
    }
}
