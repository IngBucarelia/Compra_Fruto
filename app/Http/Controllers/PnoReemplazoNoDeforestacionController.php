<?php

namespace App\Http\Controllers;

use App\Models\PnoReemplazoNoDeforestacion;
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
        PnoReemplazoNoDeforestacion::create($request->all());
        return redirect()->route('visitasAmbientales.show', $request->visita_ambiental_id)
            ->with('success', 'Registro creado correctamente');
    }

    public function edit($id)
    {
        $registro = PnoReemplazoNoDeforestacion::findOrFail($id);
        return view('pno_reemplazo.edit', compact('registro'));
    }

    public function update(Request $request, $id)
    {
        $registro = PnoReemplazoNoDeforestacion::findOrFail($id);
        $registro->update($request->all());

        return redirect()->route('visitasAmbientales.show', $registro->visita_ambiental_id)
            ->with('success', 'Registro actualizado');
    }

    public function destroy($id)
    {
        $registro = PnoReemplazoNoDeforestacion::findOrFail($id);
        $visitaId = $registro->visita_ambiental_id;
        $registro->delete();

        return redirect()->route('visitasAmbientales.show', $visitaId)
            ->with('success', 'Registro eliminado');
    }
}
