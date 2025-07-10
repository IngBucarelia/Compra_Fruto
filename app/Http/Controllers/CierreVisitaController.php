<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CierreVisita;
use App\Models\Visita;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class CierreVisitaController extends Controller
{
    public function create(Request $request)
    {
        $visita_id = $request->query('visita_id');
        $visita = Visita::with('cierreVisita')->findOrFail($visita_id);

        // Verificar si ya existe un cierre para esta visita
        if ($visita->cierreVisita) {
            return redirect()->route('visitas.show', $visita->id)
                ->with('info', '⚠️ Esta visita ya fue finalizada anteriormente.');
        }

        return view('cierre_visitas.create', compact('visita'));
    }


    public function store(Request $request)
        {
            $data = $request->validate([
                'visita_id' => 'required|exists:visitas,id',
                'firma_responsable' => 'nullable|image|max:2048',
                'observaciones_finales' => 'nullable|string',
                'recomendaciones' => 'nullable|string',
                'imagenes' => 'nullable',
            ]);

            // Guardar firma
            if ($request->hasFile('firma_responsable')) {
                $data['firma_responsable'] = $request->file('firma_responsable')->store('firmas', 'public');
            }

            // Guardar imágenes
            $imagenes = [];
            if ($request->hasFile('imagenes')) {
                foreach ($request->file('imagenes') as $img) {
                    if ($img instanceof \Illuminate\Http\UploadedFile && $img->isValid()) {
                        $imagenes[] = $img->store('imagenes_visita', 'public');
                    }
                }
            }

            // Convertir a JSON antes de guardar
            $data['imagenes'] = json_encode($imagenes);

            $data['finalizada_en'] = now();

            // Crear el registro de cierre
            CierreVisita::create($data);

            // Actualizar el estado de la visita
            \App\Models\Visita::find($data['visita_id'])->update(['estado' => 'finalizada']);

            return redirect()->route('visitas.show', $data['visita_id'])
                ->with('success', '✅ Visita finalizada con éxito.');
        }

        // zona de sincronizar con offline 

        public function syncOffline(Request $request)
    {
        $data = $request->json()->all();
        Log::info('Datos recibidos para sincronizar Cierre de Visita:', $data);

        try {
            // Aquí deberías añadir las reglas de validación para los campos de Cierre de Visita.
            // Como no proporcionaste los campos para Cierre de Visita, esto es un ejemplo.
            $request->validate([
                'visita_id' => 'required|integer',
                // 'campo_ejemplo' => 'required|string', // Añade tus campos aquí
                // 'indexeddb_id' => 'nullable|string',
            ]);

            // Asumiendo que solo hay un registro de Cierre de Visita por Visita.
            // Si puede haber múltiples, necesitarías una clave única adicional.
            CierreVisita::updateOrCreate(
                ['visita_id' => $data['visita_id']],
                $data
            );

            Log::info('Registro de Cierre de Visita sincronizado con éxito.', ['visita_id' => $data['visita_id']]);
            return response()->json(['message' => 'Cierre de Visita sincronizado con éxito.']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("Error de validación al sincronizar Cierre de Visita: " . $e->getMessage(), ['errors' => $e->errors(), 'data' => $data]);
            return response()->json(['message' => 'Error de validación.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error("Error inesperado al sincronizar Cierre de Visita: " . $e->getMessage(), ['data' => $data, 'trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Error interno del servidor al sincronizar Cierre de Visita.', 'error' => $e->getMessage()], 500);
        }
    }
    // fin de sincronizador 

}
