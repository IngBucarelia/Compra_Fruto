<?php

namespace App\Http\Controllers;

use App\Models\EnergiaManejo;
use App\Models\VisitaAmbiental;
use Illuminate\Http\Request;

class EnergiaManejoController extends Controller
{
    // En EnergiaController
    public function create($visitaId)
    {
        // Carga la visita con las relaciones necesarias
        $visita = VisitaAmbiental::with([
            'aguaCaptacionLegal',
            'aguaUsoEficiente',
            'sueloConservacion'
        ])->findOrFail($visitaId);
        
        return view('energia.create', [
            'visita' => $visita,  // Pasar $visita, no $visitaId
            'visitaId' => $visita->id
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'visita_ambiental_id' => 'required|exists:visita_ambientals,id',
        ]);

        EnergiaManejo::create($request->all());

        return redirect()
            ->route('gobernanza.create', $request->visita_ambiental_id)
            ->with('success', 'Registro de energía guardado correctamente');
    }
}
