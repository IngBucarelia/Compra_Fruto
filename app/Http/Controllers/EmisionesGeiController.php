<?php

namespace App\Http\Controllers;

use App\Models\EmisionesGei;
use App\Models\VisitaAmbiental;
use Illuminate\Http\Request;

class EmisionesGeiController extends Controller
{
    public function create($visitaId)
    {
         $visita = VisitaAmbiental::with([
            'aguaCaptacionLegal',
            'aguaUsoEficiente',
            'sueloConservacion'
        ])->findOrFail($visitaId);
        
        return view('emisiones_gei.create', [
            'visita' => $visita,  // Pasar $visita, no $visitaId
            'visitaId' => $visita->id
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'visita_ambiental_id' => 'required|exists:visita_ambientals,id',
            'cuantifica_emisiones' => 'required',
            'implementa_acciones_reduccion' => 'required',
        ]);

        EmisionesGei::create($request->all());

        return redirect()
            ->route('visitasAmbientales.show', $request->visita_ambiental_id)
            ->with('success', 'Registro guardado correctamente');
    }
}
