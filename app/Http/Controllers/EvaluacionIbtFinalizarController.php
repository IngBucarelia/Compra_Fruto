<?php

namespace App\Http\Controllers;

use App\Models\EvaluacionIbt;

class EvaluacionIbtFinalizarController extends Controller
{
    public function finalizar($id)
    {
        $evaluacion = EvaluacionIbt::with([
            'establecimientoCultivo',
            'laboresCulturales',
            'manejoNutricional',
            'manejoSanitario',
            'cosechaProduccion'
        ])->findOrFail($id);

        $puntajeTotal = $evaluacion->calcularPuntajeTotal();

        $evaluacion->update([
            'puntaje_total' => $puntajeTotal,
            'calificacion' => 'terminado',
        ]);

        return redirect()
            ->route('evaluaciones-ibt.show', $evaluacion->id)
            ->with('success', 'Evaluación IBT finalizada correctamente');
    }

    public function editar($id)
    {
        $evaluacion = EvaluacionIbt::findOrFail($id);
        return view('evaluaciones_ibt.editar_calificacion', compact('evaluacion'));
    }

    public function actualizar($id)
    {
        $evaluacion = EvaluacionIbt::findOrFail($id);

        $evaluacion->update([
            'puntaje_total' => request('puntaje_total'),
            'estado' => request('estado'),
        ]);

        return redirect()
            ->route('evaluaciones-ibt.show', $evaluacion->id)
            ->with('success', 'Calificación final actualizada correctamente');
    }

}