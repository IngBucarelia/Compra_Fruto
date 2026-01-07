<?php

namespace App\Http\Controllers;

use App\Models\EvaluacionIbt;
use App\Models\IbtEstablecimientoCultivo;
use Illuminate\Http\Request;

class IbtEstablecimientoCultivoController extends Controller
{
    /**
     * Decide si crea o edita
     */
    public function redirect(EvaluacionIbt $evaluacion)
    {
        if ($evaluacion->establecimientoCultivo) {
            return redirect()->route(
                'ibt.establecimiento_cultivo.edit',
                $evaluacion->establecimientoCultivo->id
            );
        }

        return redirect()->route(
            'ibt.establecimiento_cultivo.create',
            $evaluacion->id
        );
    }

    public function create(EvaluacionIbt $evaluacion)
    {
        $registro = IbtEstablecimientoCultivo::where('evaluacion_ibt_id', $evaluacion->id)->first();
        return view('evaluaciones_ibt.establecimiento_cultivo.create', compact('evaluacion', 'registro'));
    }

    public function edit(IbtEstablecimientoCultivo $registro)
    {
        $evaluacion = $registro->evaluacion; // ← ESTA LÍNEA ES CLAVE

        return view(
            'evaluaciones_ibt.establecimiento_cultivo.edit',
            compact('registro', 'evaluacion')
        );
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'evaluacion_ibt_id' => 'required|exists:evaluaciones_ibt,id',
            'estudios_caracterizacion_suelos' => 'required|integer',
            'estudios_topograficos' => 'required|integer',
            'diseno_riegos_drenajes' => 'required|integer',
            'diseno_uma' => 'required|integer',
            'preparacion_suelos' => 'required|integer',
            'leguminosas_cobertura' => 'required|integer',
        ]);

        $data['puntaje_total'] = array_sum([
            $data['estudios_caracterizacion_suelos'],
            $data['estudios_topograficos'],
            $data['diseno_riegos_drenajes'],
            $data['diseno_uma'],
            $data['preparacion_suelos'],
            $data['leguminosas_cobertura'],
        ]);

        $establecimiento = IbtEstablecimientoCultivo::create($data);

        return redirect()
            ->route('evaluaciones-ibt.show', $data['evaluacion_ibt_id'])
            ->with('success', 'Establecimiento de cultivo registrado');
    }

    public function update(Request $request, IbtEstablecimientoCultivo $establecimiento)
    {
        $data = $request->validate([
            'estudios_caracterizacion_suelos' => 'required|integer',
            'estudios_topograficos' => 'required|integer',
            'diseno_riegos_drenajes' => 'required|integer',
            'diseno_uma' => 'required|integer',
            'preparacion_suelos' => 'required|integer',
            'leguminosas_cobertura' => 'required|integer',
        ]);

        $data['puntaje_total'] = array_sum($data);

        $establecimiento->update($data);

        return redirect()
            ->route('evaluaciones-ibt.show', $establecimiento->evaluacion_ibt_id)
            ->with('success', 'Establecimiento de cultivo actualizado');
    }
}
