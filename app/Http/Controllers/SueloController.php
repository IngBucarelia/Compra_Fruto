<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visita;
use App\Models\Suelo;
use Illuminate\Support\Facades\Log;


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

    public function syncOffline(Request $request)
    {
        $data = $request->json()->all();
        Log::info('Datos recibidos para sincronizar Suelo:', $data);

        try {
            $request->validate([
                'visita_id' => 'required|integer',
                // ✅ CAMBIO: Usar 'in:si,no' para aceptar las cadenas "si" o "no"
                'analisis_foliar' => 'required|string|in:si,no',
                // ✅ CAMBIO: Corregir el nombre del campo a 'alanalisis_suelo' para que coincida con los datos
                'alanalisis_suelo' => 'required|string|in:si,no',
                'tipo_suelo' => 'required|string', // Si es un select de varias opciones, podría ser JSON o string
                // 'indexeddb_id' => 'nullable|string',
            ]);

            // Asumiendo que solo hay un registro de Suelo por Visita.
            // Si puede haber múltiples, necesitarías una clave única adicional.
            Suelo::updateOrCreate(
                ['visita_id' => $data['visita_id']],
                $data
            );

            Log::info('Registro de Suelo sincronizado con éxito.', ['visita_id' => $data['visita_id']]);
            return response()->json(['message' => 'Suelo sincronizado con éxito.']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("Error de validación al sincronizar Suelo: " . $e->getMessage(), ['errors' => $e->errors(), 'data' => $data]);
            return response()->json(['message' => 'Error de validación.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error("Error inesperado al sincronizar Suelo: " . $e->getMessage(), ['data' => $data, 'trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Error interno del servidor al sincronizar Suelo.', 'error' => $e->getMessage()], 500);
        }
    }


}

