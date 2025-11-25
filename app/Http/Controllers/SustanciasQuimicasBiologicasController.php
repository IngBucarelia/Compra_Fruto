<?php

namespace App\Http\Controllers;

use App\Models\SustanciasQuimicasBiologicas;
use App\Models\VisitaAmbiental;
use Illuminate\Http\Request;

class SustanciasQuimicasBiologicasController extends Controller
{
    public function create($visitaId)
    {
        return view('sustancias.create', compact('visitaId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'visita_ambiental_id' => 'required|exists:visita_ambientals,id'
        ]);

        SustanciasQuimicasBiologicas::create($request->all());

        return redirect()->route('visitasAmbientales.show', $request->visita_ambiental_id)
                         ->with('success', 'Registro guardado correctamente.');
    }

    public function edit($id)
    {
        $registro = SustanciasQuimicasBiologicas::findOrFail($id);
        $visitaId = $registro->visita_ambiental_id;

        return view('ambiental.sustancias.edit', compact('registro', 'visitaId'));
    }

    public function update(Request $request, $id)
    {
        $registro = SustanciasQuimicasBiologicas::findOrFail($id);

        $registro->update($request->all());

        return redirect()->route('visitasAmbientales.show', $registro->visita_ambiental_id)
                         ->with('success', 'Registro actualizado correctamente.');
    }

    public function destroy($id)
    {
        $registro = SustanciasQuimicasBiologicas::findOrFail($id);
        $visitaId = $registro->visita_ambiental_id;
        $registro->delete();

        return redirect()->route('visitasAmbientales.show', $visitaId)
                         ->with('success', 'Registro eliminado correctamente.');
    }
}
