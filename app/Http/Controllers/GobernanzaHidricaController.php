<?php

namespace App\Http\Controllers;

use App\Models\GobernanzaHidrica;
use App\Models\VisitaAmbiental;
use Illuminate\Http\Request;

class GobernanzaHidricaController extends Controller
{
    public function create($visitaId)
    {
        $visita = VisitaAmbiental::findOrFail($visitaId);
        return view('gobernanza.create', compact('visita'));
    }

    public function store(Request $request, $visitaId)
    {
        $request->validate([
            'observaciones' => 'nullable|string|max:2000',
        ]);

        GobernanzaHidrica::create([
            'visita_id' => $visitaId,
            'canales_comunicacion' => $request->has('canales_comunicacion'),
            'identifica_actores_afectados' => $request->has('identifica_actores_afectados'),
            'participa_actividades_gestion' => $request->has('participa_actividades_gestion'),
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('visitasAmbientales.show', $visitaId)
            ->with('success', 'Componente registrado correctamente.');
    }

    public function edit($visitaId)
    {
        $visita = VisitaAmbiental::findOrFail($visitaId);
        $g = $visita->gobernanzaHidrica;

        if (!$g) {
            return redirect()->back()->with('error', 'El componente no existe.');
        }

        return view('gobernanza.edit', compact('visita', 'g'));
    }

    public function update(Request $request, $visitaId)
    {
        $g = GobernanzaHidrica::where('visita_id', $visitaId)->firstOrFail();

        $g->update([
            'canales_comunicacion' => $request->has('canales_comunicacion'),
            'identifica_actores_afectados' => $request->has('identifica_actores_afectados'),
            'participa_actividades_gestion' => $request->has('participa_actividades_gestion'),
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('visitasAmbientales.show', $visitaId)
            ->with('success', 'Componente actualizado.');
    }
}
