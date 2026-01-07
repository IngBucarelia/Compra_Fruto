<?php

namespace App\Http\Controllers;

use App\Models\EvaluacionIbt;
use App\Models\IbtLaboresCulturales;
use Illuminate\Http\Request;

class IbtLaboresCulturalesController extends Controller
{
    public function create(EvaluacionIbt $evaluacion)
    {
        $registro = $evaluacion->laboresCulturales;

        return view(
            'evaluaciones_ibt.labores_culturales.form',
            compact('evaluacion', 'registro')
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'evaluacion_ibt_id' => 'required|exists:evaluaciones_ibt,id',
            'limpieza_platos' => 'required|integer|min:0|max:2',
            'limpieza_interlineas' => 'required|integer|min:0|max:2',
            'poda' => 'required|integer|min:0|max:2',
            'polinizacion' => 'required|integer|min:0|max:8',
            'disposicion_hojas_podadas' => 'required|integer|min:0|max:4',
            'mantenimiento_infraestructura' => 'required|integer|min:0|max:2',
        ]);

        $data['puntaje_total'] =
            $data['limpieza_platos'] +
            $data['limpieza_interlineas'] +
            $data['poda'] +
            $data['polinizacion'] +
            $data['disposicion_hojas_podadas'] +
            $data['mantenimiento_infraestructura'];

        IbtLaboresCulturales::create($data);

        return redirect()
            ->route('evaluaciones-ibt.show', $data['evaluacion_ibt_id'])
            ->with('success', 'Labores culturales guardadas correctamente');
    }

    public function edit(IbtLaboresCulturales $registro)
    {
        $evaluacion = $registro->evaluacion;

        return view(
            'evaluaciones_ibt.labores_culturales.form',
            compact('registro', 'evaluacion')
        );
    }

    public function update(Request $request, IbtLaboresCulturales $registro)
    {
        $data = $request->validate([
            'limpieza_platos' => 'required|integer|min:0|max:2',
            'limpieza_interlineas' => 'required|integer|min:0|max:2',
            'poda' => 'required|integer|min:0|max:2',
            'polinizacion' => 'required|integer|min:0|max:8',
            'disposicion_hojas_podadas' => 'required|integer|min:0|max:4',
            'mantenimiento_infraestructura' => 'required|integer|min:0|max:2',
        ]);

        $data['puntaje_total'] =
            $data['limpieza_platos'] +
            $data['limpieza_interlineas'] +
            $data['poda'] +
            $data['polinizacion'] +
            $data['disposicion_hojas_podadas'] +
            $data['mantenimiento_infraestructura'];

        $registro->update($data);

        return redirect()
            ->route('evaluaciones-ibt.show', $registro->evaluacion_ibt_id)
            ->with('success', 'Labores culturales actualizadas');
    }
}

