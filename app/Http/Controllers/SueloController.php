<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visita;
use App\Models\Suelo;

class SueloController extends Controller
{
    public function create(Request $request)
        {
            $visita = Visita::with([
                'area',
                'fertilizaciones.fertilizantes',
                'polinizaciones',
                'sanidad',
                'suelo' // incluimos la relación
            ])->findOrFail($request->query('visita_id'));

            $suelo = $visita->suelo; // puede ser null

            return view('suelos.create', compact('visita', 'suelo'));
        }


    public function store(Request $request)
    {
        $data = $request->validate([
            'visita_id' => 'required|exists:visitas,id',
            'analisis_foliar' => 'required|in:si,no',
            'alanisis_suelo' => 'required|in:si,no',
            'tipo_suelo' => 'required|string',
        ]);

        Suelo::create($data);

        return redirect()->route('labores_cultivo.create', $data['visita_id'])
            ->with('success', '✅ Análisis de suelo registrado correctamente.');
    }

    public function edit(Suelo $suelo)
    {
        $visita = $suelo->visita()->with([
            'area',
            'fertilizaciones.fertilizantes',
            'polinizaciones',
            'sanidad'
        ])->first();

        return view('suelos.edit', compact('suelo', 'visita'));
    }

    public function update(Request $request, Suelo $suelo)
    {
        $data = $request->validate([
            'analisis_foliar' => 'required|in:si,no',
            'alanisis_suelo' => 'required|in:si,no',
            'tipo_suelo' => 'required|string|max:100',
        ]);

        $suelo->update($data);

        return redirect()->route('visitas.show', $suelo->visita_id)
            ->with('success', '✅ Análisis de suelo actualizado correctamente.');
    }

    public function destroy($id)
    {
        $suelo = Suelo::findOrFail($id);
        $visitaId = $suelo->visita_id;
        $suelo->delete();

        return redirect()->route('suelos.create', ['visita_id' => $visitaId])
            ->with('success', '🗑️ Análisis de suelo eliminado correctamente.');
    }



}

