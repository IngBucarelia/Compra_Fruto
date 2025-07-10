<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

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
       
        public function syncOffline(Request $request)
    {
        $data = $request->json()->all();
        Log::info('Datos recibidos para sincronizar Evaluación de Cosecha de Campo:', $data);

        try {
            $request->validate([
                'visita_id' => 'required|integer',
                'variedad_fruto' => 'required|string',
                'cantidad_racimos' => 'required|integer',
                'verde' => 'nullable|integer|min:0|max:100',
                'maduro' => 'nullable|integer|min:0|max:100',
                'sobremaduro' => 'nullable|integer|min:0|max:100',
                'pedunculo' => 'nullable|integer|min:0|max:100',
                'observaciones' => 'nullable|string',
                // 'indexeddb_id' => 'nullable|string',
            ]);

            // Asumiendo que solo hay un registro de Evaluación de Cosecha por Visita.
            // Si puede haber múltiples, necesitarías una clave única adicional.
            EvaluacionCosechaCampo::updateOrCreate(
                ['visita_id' => $data['visita_id']],
                $data
            );

            Log::info('Registro de Evaluación de Cosecha de Campo sincronizado con éxito.', ['visita_id' => $data['visita_id']]);
            return response()->json(['message' => 'Evaluación de Cosecha de Campo sincronizada con éxito.']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("Error de validación al sincronizar Evaluación de Cosecha de Campo: " . $e->getMessage(), ['errors' => $e->errors(), 'data' => $data]);
            return response()->json(['message' => 'Error de validación.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error("Error inesperado al sincronizar Evaluación de Cosecha de Campo: " . $e->getMessage(), ['data' => $data, 'trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Error interno del servidor al sincronizar Evaluación de Cosecha de Campo.', 'error' => $e->getMessage()], 500);
        }
    }
}

