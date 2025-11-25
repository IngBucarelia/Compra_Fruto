<?php

namespace App\Http\Controllers;

use App\Models\ResiduosManejo;
use Illuminate\Http\Request;

class ResiduosManejoController extends Controller
{
    public function create($visitaId)
    {
        return view('residuos.create', compact('visitaId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'visita_ambiental_id' => 'required|exists:visitas_ambientales,id',
        ]);

        ResiduosManejo::create($request->all());

        return redirect()->route('visitasAmbientales.show', $request->visita_ambiental_id)
            ->with('success', 'Registro guardado correctamente');
    }

    public function edit($id)
    {
        $registro = ResiduosManejo::findOrFail($id);
        return view('residuos.edit', compact('registro'));
    }

    public function update(Request $request, $id)
    {
        $registro = ResiduosManejo::findOrFail($id);

        $registro->update($request->all());

        return redirect()->route('visitasAmbientales.show', $registro->visita_ambiental_id)
            ->with('success', 'Registro actualizado correctamente');
    }

    public function destroy($id)
    {
        $registro = ResiduosManejo::findOrFail($id);
        $visita = $registro->visita_ambiental_id;

        $registro->delete();

        return redirect()->route('visitasAmbientales.show', $visita)
            ->with('success', 'Registro eliminado');
    }
}
