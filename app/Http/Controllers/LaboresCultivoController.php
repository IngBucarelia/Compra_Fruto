<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use App\Models\LaboresCultivo;
use App\Models\Visita;
use Illuminate\Http\Request;

class LaboresCultivoController extends Controller
{
   public function create(Request $request)
        {
            $visita = Visita::with([
                'area',
                'fertilizaciones.fertilizantes',
                'polinizaciones',
                'sanidad',
                'suelo',
                'laboresCultivo' 
            ])->findOrFail($request->query('visita_id'));

            return view('labores_cultivo.create', compact('visita'));
        }

        public function store(Request $request)
        {
            $data = $request->validate([
                'visita_id' => 'required|exists:visitas,id',
                'polinizacion' => 'nullable|integer|min:0|max:100',
                'limpieza_calle' => 'nullable|integer|min:0|max:100',
                'limpieza_plato' => 'nullable|integer|min:0|max:100',
                'poda' => 'nullable|integer|min:0|max:100',
                'fertilizacion' => 'nullable|integer|min:0|max:100',
                'enmiendas' => 'nullable|integer|min:0|max:100',
                'ubicacion_tusa_fibra' => 'nullable|integer|min:0|max:100',
                'ubicacion_hoja' => 'nullable|integer|min:0|max:100',
                'lugar_ubicacion_hoja' => 'nullable|integer|min:0|max:100',
                'plantas_nectariferas' => 'nullable|integer|min:0|max:100',
                'cobertura' => 'nullable|integer|min:0|max:100',
                'labor_cosecha' => 'nullable|integer|min:0|max:100',
                'calidad_fruta' => 'nullable|integer|min:0|max:100',
                'recoleccion_fruta' => 'nullable|integer|min:0|max:100',
                'drenajes' => 'nullable|integer|min:0|max:100',
            ]);

            LaboresCultivo::create($data);

            return redirect()->route('visitas.show', $data['visita_id'])
                ->with('success', '✅ Registro de labores de cultivo guardado correctamente.');
        }

        public function edit($visita_id)
        {
            $visita = Visita::with('laboresCultivo')->findOrFail($visita_id);
            return view('labores_cultivo.edit', compact('visita'));
        }

        public function update(Request $request, $id)
        {
            $data = $request->validate([
                'visita_id' => 'required|exists:visitas,id',
                'polinizacion' => 'nullable|integer|min:0|max:100',
                'limpieza_calle' => 'nullable|integer|min:0|max:100',
                'limpieza_plato' => 'nullable|integer|min:0|max:100',
                'poda' => 'nullable|integer|min:0|max:100',
                'fertilizacion' => 'nullable|integer|min:0|max:100',
                'enmiendas' => 'nullable|integer|min:0|max:100',
                'ubicacion_tusa_fibra' => 'nullable|integer|min:0|max:100',
                'ubicacion_hoja' => 'nullable|integer|min:0|max:100',
                'lugar_ubicacion_hoja' => 'nullable|integer|min:0|max:100',
                'plantas_nectariferas' => 'nullable|integer|min:0|max:100',
                'cobertura' => 'nullable|integer|min:0|max:100',
                'labor_cosecha' => 'nullable|integer|min:0|max:100',
                'calidad_fruta' => 'nullable|integer|min:0|max:100',
                'recoleccion_fruta' => 'nullable|integer|min:0|max:100',
                'drenajes' => 'nullable|integer|min:0|max:100',
            ]);

            $labores = LaboresCultivo::findOrFail($id);
            $labores->update($data);

            return redirect()->route('visitas.show', $data['visita_id'])
                ->with('success', '✅ Labores de cultivo actualizadas correctamente.');
        }

        public function destroy($id)
        {
            $suelo = LaboresCultivo::findOrFail($id);
            $visitaId = $suelo->visita_id;
            $suelo->delete();

            return redirect()->route('labores-cultivo', ['visita_id' => $visitaId])
                ->with('success', '🗑️ labores-cultivo eliminado correctamente.');
        }

                // controllador offline

                
           public function syncOffline(Request $request)
    {
        $data = $request->json()->all();
        Log::info('Datos recibidos para sincronizar Labores de Cultivo:', $data);

        try {
            $request->validate([
                'visita_id' => 'required|integer',
                'polinizacion' => 'nullable|integer|min:0|max:100',
                'limpeza_calle' => 'nullable|integer|min:0|max:100',
                'limpieza_plato' => 'nullable|integer|min:0|max:100',
                'poda' => 'nullable|integer|min:0|max:100',
                'fertilizacion' => 'nullable|integer|min:0|max:100',
                'enmiendas' => 'nullable|integer|min:0|max:100',
                'ubicacion_tusa_fibra' => 'nullable|integer|min:0|max:100',
                'ubicacion_hoja' => 'nullable|integer|min:0|max:100',
                'lugar_ubicacion_hoja' => 'nullable|integer|min:0|max:100',
                'plantas_nectariferas' => 'nullable|integer|min:0|max:100',
                'cobertura' => 'nullable|integer|min:0|max:100',
                'labor_cosecha' => 'nullable|integer|min:0|max:100',
                'calidad_fruta' => 'nullable|integer|min:0|max:100',
                'recoleccion_fruta' => 'nullable|integer|min:0|max:100',
                'drenajes' => 'nullable|integer|min:0|max:100',
                // 'indexeddb_id' => 'nullable|string',
            ]);

            // Asumiendo que solo hay un registro de Labores de Cultivo por Visita.
            // Si puede haber múltiples, necesitarías una clave única adicional.
            LaboresCultivo::updateOrCreate(
                ['visita_id' => $data['visita_id']],
                $data
            );

            Log::info('Registro de Labores de Cultivo sincronizado con éxito.', ['visita_id' => $data['visita_id']]);
            return response()->json(['message' => 'Labores de Cultivo sincronizadas con éxito.']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("Error de validación al sincronizar Labores de Cultivo: " . $e->getMessage(), ['errors' => $e->errors(), 'data' => $data]);
            return response()->json(['message' => 'Error de validación.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error("Error inesperado al sincronizar Labores de Cultivo: " . $e->getMessage(), ['data' => $data, 'trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Error interno del servidor al sincronizar Labores de Cultivo.', 'error' => $e->getMessage()], 500);
        }
    }


}
