<?php

namespace App\Http\Controllers;

use App\Models\SustanciasQuimicasBiologicas;
use App\Models\VisitaAmbiental;
use Illuminate\Http\Request;

class SustanciasQuimicasBiologicasController extends Controller
{
    public function create($visitaId)
    {
         $visita = VisitaAmbiental::with([
            'aguaCaptacionLegal',
            'aguaUsoEficiente',
            'sueloConservacion'
        ])->findOrFail($visitaId);
        
        return view('sustancias.create', [
            'visita' => $visita,  // Pasar $visita, no $visitaId
            'visitaId' => $visita->id
        ]);
        
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'visita_ambiental_id' => 'required|exists:visita_ambientals,id',
            'cuenta_poes' => 'required|boolean',
            'personal_capacitado' => 'required|boolean',
            'almacenamiento_adecuado' => 'required|boolean',
            'imagen_poes' => 'nullable|image|max:4096',
            'observaciones' => 'nullable|string',
        ]);

        if ($request->hasFile('imagen_poes')) {
            $data['imagen_poes'] = $request->file('imagen_poes')
                ->store('sustancias/poes', 'public');
        }

        SustanciasQuimicasBiologicas::create($data);

        return redirect()
            ->route('vertimientos.create', $request->visita_ambiental_id)
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
