<?php

namespace App\Http\Controllers;

use App\Models\ManejoVertimientos;
use Illuminate\Http\Request;

class ManejoVertimientosController extends Controller
{
    public function create($visitaId)
    {
        return view('vertimientos.create', compact('visitaId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'visita_ambiental_id' => 'required|exists:visita_ambientals,id'
        ]);

        ManejoVertimientos::create($request->all());

        return redirect()->route('visitasAmbientales.show', $request->visita_ambiental_id)
                         ->with('success', 'Registro guardado correctamente.');
    }

    public function edit($id)
    {
        $registro = ManejoVertimientos::findOrFail($id);
        $visitaId = $registro->visita_ambiental_id;
        return view('ambiental.vertimientos.edit', compact('registro', 'visitaId'));
    }

    public function update(Request $request, $id)
    {
        $registro = ManejoVertimientos::findOrFail($id);
        $registro->update($request->all());

        return redirect()->route('visitasAmbientales.show', $registro->visita_ambiental_id)
                         ->with('success', 'Registro actualizado correctamente.');
    }

    public function destroy($id)
    {
        $registro = ManejoVertimientos::findOrFail($id);
        $visitaId = $registro->visita_ambiental_id;

        $registro->delete();

        return redirect()->route('visitasAmbientales.show', $visitaId)
                         ->with('success', 'Registro eliminado correctamente.');
    }
}
