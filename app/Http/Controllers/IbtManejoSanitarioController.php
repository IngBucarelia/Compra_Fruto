<?php

namespace App\Http\Controllers;

use App\Models\EvaluacionIbt;
use App\Models\IbtManejoSanitario;
use Illuminate\Http\Request;

class IbtManejoSanitarioController extends Controller
{
    public function create($evaluacionId)
    {
        $evaluacion = EvaluacionIbt::with(['proveedor','plantacion','usuario'])->findOrFail($evaluacionId);

        $registro = IbtManejoSanitario::where('evaluacion_ibt_id', $evaluacionId)->first();

        return view('evaluaciones_ibt.manejo_sanitario.form', compact('evaluacion','registro'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'evaluacion_ibt_id' => 'required|exists:evaluaciones_ibt,id',
            'censo_enfermedades_plagas' => 'required|integer|min:0|max:8',
            'oportunidad_control' => 'required|integer|min:0|max:8',
            'calidad_follaje' => 'required|integer|min:0|max:1',
            'area_foliar' => 'required|integer|min:0|max:2',
            'censo_palmas_anormales' => 'required|integer|min:0|max:1',
        ]);

        $data['puntaje_total'] =
            $data['censo_enfermedades_plagas'] +
            $data['oportunidad_control'] +
            $data['calidad_follaje'] +
            $data['area_foliar'] +
            $data['censo_palmas_anormales'];

        IbtManejoSanitario::create($data);

        return redirect()
            ->route('evaluaciones-ibt.show', $data['evaluacion_ibt_id'])
            ->with('success','Manejo sanitario guardado');
    }

    public function edit($id)
    {
        $registro = IbtManejoSanitario::findOrFail($id);
        $evaluacion = EvaluacionIbt::with(['proveedor','plantacion','usuario'])
            ->findOrFail($registro->evaluacion_ibt_id);

        return view('evaluaciones_ibt.manejo_sanitario.form', compact('evaluacion','registro'));
    }

    public function update(Request $request, $id)
    {
        $registro = IbtManejoSanitario::findOrFail($id);

        $data = $request->validate([
            'censo_enfermedades_plagas' => 'required|integer|min:0|max:8',
            'oportunidad_control' => 'required|integer|min:0|max:8',
            'calidad_follaje' => 'required|integer|min:0|max:1',
            'area_foliar' => 'required|integer|min:0|max:2',
            'censo_palmas_anormales' => 'required|integer|min:0|max:1',
        ]);

        $data['puntaje_total'] =
            $data['censo_enfermedades_plagas'] +
            $data['oportunidad_control'] +
            $data['calidad_follaje'] +
            $data['area_foliar'] +
            $data['censo_palmas_anormales'];

        $registro->update($data);

        return redirect()
            ->route('evaluaciones-ibt.show', $registro->evaluacion_ibt_id)
            ->with('success','Manejo sanitario actualizado');
    }
}
