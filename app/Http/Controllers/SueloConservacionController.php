<?php

namespace App\Http\Controllers;

use App\Models\SueloConservacion;
use App\Models\VisitaAmbiental;
use Illuminate\Http\Request;

class SueloConservacionController extends Controller
{
    public function create($visitaAmbientalId)
    {
        $visita = VisitaAmbiental::findOrFail($visitaAmbientalId);
        return view('suelo_conservacion.create', compact('visita'));
    }

    public function store(Request $request)
    {
        SueloConservacion::create([
            'visita_ambiental_id' => $request->visita_ambiental_id,
            'usa_fuego_preparacion' => $request->has('usa_fuego_preparacion'),
            'control_coberturas_invasoras' => $request->has('control_coberturas_invasoras'),
            'siembra_coberturas' => $request->has('siembra_coberturas'),
            'sigue_recomendaciones_comerciales' => $request->has('sigue_recomendaciones_comerciales'),
            'observaciones' => $request->has('observaciones'),
        ]);

        return redirect()->back()->with('success', 'Componente registrado correctamente.');
    }

    public function edit($id)
    {
        $componente = SueloConservacion::findOrFail($id);
        return view('suelo_conservacion.edit', compact('componente'));
    }

    public function update(Request $request, $id)
    {
        $componente = SueloConservacion::findOrFail($id);

        $componente->update([
            'usa_fuego_preparacion' => $request->has('usa_fuego_preparacion'),
            'control_coberturas_invasoras' => $request->has('control_coberturas_invasoras'),
            'siembra_coberturas' => $request->has('siembra_coberturas'),
            'sigue_recomendaciones_comerciales' => $request->has('sigue_recomendaciones_comerciales'),
        ]);

        return redirect()->back()->with('success', 'Componente actualizado correctamente.');
    }
}
