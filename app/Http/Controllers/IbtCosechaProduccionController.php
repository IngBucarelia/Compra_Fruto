<?php

namespace App\Http\Controllers;

use App\Models\EvaluacionIbt;
use App\Models\IbtCosechaProduccion;
use Illuminate\Http\Request;

class IbtCosechaProduccionController extends Controller
{
    public function create($evaluacionId)
    {
        $evaluacion = EvaluacionIbt::with(['proveedor','plantacion','usuario'])
            ->findOrFail($evaluacionId);

        $registro = IbtCosechaProduccion::where('evaluacion_ibt_id',$evaluacionId)->first();

        return view('evaluaciones_ibt.cosecha_produccion.form', compact('evaluacion','registro'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'evaluacion_ibt_id' => 'required|exists:evaluaciones_ibt,id',
            'criterio_ciclo_cosecha' => 'required|integer|min:0|max:3',
            'recoleccion_fruto' => 'required|integer|min:0|max:3',
            'calidad_fruto_cosechado' => 'required|integer|min:0|max:3',
            'produccion' => 'required|integer|min:0|max:6',
        ]);

        $data['puntaje_total'] =
            $data['criterio_ciclo_cosecha'] +
            $data['recoleccion_fruto'] +
            $data['calidad_fruto_cosechado'] +
            $data['produccion'];

        IbtCosechaProduccion::create($data);

        return redirect()
            ->route('evaluaciones-ibt.show',$data['evaluacion_ibt_id'])
            ->with('success','Cosecha y producción guardado');
    }

    public function edit($id)
    {
        $registro = IbtCosechaProduccion::findOrFail($id);

        $evaluacion = EvaluacionIbt::with(['proveedor','plantacion','usuario'])
            ->findOrFail($registro->evaluacion_ibt_id);

        return view('evaluaciones_ibt.cosecha_produccion.form', compact('evaluacion','registro'));
    }

    public function update(Request $request, $id)
    {
        $registro = IbtCosechaProduccion::findOrFail($id);

        $data = $request->validate([
            'criterio_ciclo_cosecha' => 'required|integer|min:0|max:3',
            'recoleccion_fruto' => 'required|integer|min:0|max:3',
            'calidad_fruto_cosechado' => 'required|integer|min:0|max:3',
            'produccion' => 'required|integer|min:0|max:6',
        ]);

        $data['puntaje_total'] =
            $data['criterio_ciclo_cosecha'] +
            $data['recoleccion_fruto'] +
            $data['calidad_fruto_cosechado'] +
            $data['produccion'];

        $registro->update($data);

        return redirect()
            ->route('evaluaciones-ibt.show',$registro->evaluacion_ibt_id)
            ->with('success','Cosecha y producción actualizado');
    }
}
