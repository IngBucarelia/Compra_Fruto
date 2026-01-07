<?php
namespace App\Http\Controllers;

use App\Models\EvaluacionIbt;
use App\Models\IbtManejoNutricional;
use Illuminate\Http\Request;



class IbtManejoNutricionalController extends Controller
{
    public function create($evaluacionId)
    {
        $evaluacion = EvaluacionIbt::findOrFail($evaluacionId);

        $registro = $evaluacion->manejoNutricional;

        return view('evaluaciones_ibt.manejo_nutricional.form', compact('evaluacion', 'registro'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['puntaje_total'] = array_sum([
            $data['toma_muestra_foliares'],
            $data['toma_muestras_suelos'],
            $data['censo_produccion'],
            $data['eficacia_fertilizacion'],
            $data['fraccionamiento_fertilizacion'],
            $data['epoca_fertilizacion'],
            $data['medicion_crecimiento'],
        ]);

        IbtManejoNutricional::create($data);

        return redirect()
            ->route('evaluaciones-ibt.show', $request->evaluacion_ibt_id)
            ->with('success', 'Manejo nutricional guardado');
    }

    public function edit($id)
    {
        $registro = IbtManejoNutricional::findOrFail($id);
        $evaluacion = $registro->evaluacion;

        return view('evaluaciones_ibt.manejo_nutricional.form', compact('evaluacion', 'registro'));
    }

    public function update(Request $request, $id)
    {
        $registro = IbtManejoNutricional::findOrFail($id);

        $data = $request->all();
        $data['puntaje_total'] = array_sum([
            $data['toma_muestra_foliares'],
            $data['toma_muestras_suelos'],
            $data['censo_produccion'],
            $data['eficacia_fertilizacion'],
            $data['fraccionamiento_fertilizacion'],
            $data['epoca_fertilizacion'],
            $data['medicion_crecimiento'],
        ]);

        $registro->update($data);

        return redirect()
            ->route('evaluaciones-ibt.show', $registro->evaluacion_ibt_id)
            ->with('success', 'Manejo nutricional actualizado');
    }
}
