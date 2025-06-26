<?php

namespace App\Http\Controllers;

use App\Models\EvaluacionCosechaCampo;
use App\Models\Visita;
use Illuminate\Http\Request;

class EvaluacionCosechaCampoController extends Controller
{
    public function create(Request $request)
    {
        $visita = Visita::with([
            'area',
            'fertilizaciones.fertilizantes',
            'polinizaciones',
            'sanidad',
            'suelo',
            'laboresCultivo',
            'evaluacionCosechaCampo'
        ])->findOrFail($request->query('visita_id'));

        return view('evaluacion_cosecha.create', compact('visita'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'visita_id' => 'required|exists:visitas,id',
            'variedad_fruto' => 'required|in:guinense,hibrido',
            'cantidad_racimos' => 'required|integer|min:0',
            'verde' => 'required|integer|min:0|max:100',
            'maduro' => 'required|integer|min:0|max:100',
            'sobremaduro' => 'required|integer|min:0|max:100',
            'pedunculo' => 'required|integer|min:0|max:100',
            'observaciones' => 'nullable|string',
        ]);

        EvaluacionCosechaCampo::create($data);

        return redirect()->route('visitas.show', $data['visita_id'])
            ->with('success', '✅ Evaluación de cosecha registrada correctamente.');
    }

    public function edit(EvaluacionCosechaCampo $evaluacion)
    {
        $visita = $evaluacion->visita()->with([
            'area',
            'fertilizaciones.fertilizantes',
            'polinizaciones',
            'sanidad',
            'suelo',
            'laboresCultivo',
        ])->first();

        return view('evaluacion_cosecha.edit', compact('evaluacion', 'visita'));
    }

    public function update(Request $request, EvaluacionCosechaCampo $evaluacion)
    {
        $data = $request->validate([
            'variedad_fruto' => 'required|in:guinense,hibrido',
            'cantidad_racimos' => 'required|integer|min:0',
            'verde' => 'required|integer|min:0|max:100',
            'maduro' => 'required|integer|min:0|max:100',
            'sobremaduro' => 'required|integer|min:0|max:100',
            'pedunculo' => 'required|integer|min:0|max:100',
            'observaciones' => 'nullable|string',
        ]);

        $evaluacion->update($data);

        return redirect()->route('visitas.show', $evaluacion->visita_id)
            ->with('success', '✅ Evaluación de cosecha actualizada correctamente.');
    }

    public function destroy($id)
        {
            $suelo = EvaluacionCosechaCampo::findOrFail($id);
            $visitaId = $suelo->visita_id;
            $suelo->delete();

            return redirect()->route('evaluacion.create', ['visita_id' => $visitaId])
                ->with('success', '🗑️ Evaluacion Cosecha Campo eliminado correctamente.');
        }

}

