<?php

namespace App\Http\Controllers;

use App\Models\PlantacionHmp;
use App\Models\VisitaAmbiental;
use Illuminate\Http\Request;

class PlantacionHmpController extends Controller
{
    public function create($visitaId)
    {
        $visita = VisitaAmbiental::findOrFail($visitaId);
        return view('plantacion_hmp.create', compact('visita'));
    }

    public function store(Request $request)
    {
        PlantacionHmp::create($request->all());
        return redirect()->route('visitasAmbientales.show', $request->visita_ambiental_id)
                         ->with('success', 'Registro guardado');
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
