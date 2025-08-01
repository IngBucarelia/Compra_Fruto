<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CierreVisita;
use App\Models\Visita;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;



class CierreVisitaController extends Controller
{
     public function create(int $visita_id) // ✅ CAMBIO CLAVE: Recibir visita_id como parámetro de ruta
    {
        $visita = Visita::with([
            'proveedor',
            'plantacion',
            'areas', // Cargar áreas como colección
            'fertilizaciones.detalles', // Cargar detalles de fertilización
            'polinizaciones',
            'sanidad',
            'suelo',
            'laboresCultivo', // Cargar labores como colección
            'evaluacionCosechaCampo', // Cargar evaluaciones como colección
            'cierreVisita' // Cargar el cierre de visita si ya existe
        ])->findOrFail($visita_id);

        // Si ya existe un cierre para esta visita, redirigir
        // Esto es para evitar crear múltiples cierres si la lógica de tu negocio lo prohíbe.
        // Si permites un único cierre, esta lógica es correcta.
        // Si permites múltiples cierres, necesitarías un 'indexeddb_id' para la unicidad.
        $cierreVisitaExistente = CierreVisita::where('visita_id', $visita_id)->first();
        if ($cierreVisitaExistente) {
             return redirect()->route('visitas.show', $visita->id)
                 ->with('info', '⚠️ Esta visita ya fue finalizada anteriormente.');
        }

        return view('cierre_visitas.create', compact('visita', 'cierreVisitaExistente'));
    }


    public function store(Request $request)
    {
        // Log para ver los datos que llegan
        Log::info('Datos recibidos para Cierre de Visita (online):', $request->all());

        try {
            $data = $request->validate([
                'visita_id' => 'required|exists:visitas,id',
                'fecha_cierre' => 'required|date',
                'estado_visita' => 'required|string|in:completado,pendiente,cancelado',
                'observaciones_finales' => 'nullable|string',
                'recomendaciones' => 'nullable|string',
                'firma_responsable' => 'required|string', // Ahora es Base64
                'firma_recibe' => 'required|string',     // Ahora es Base64
                'firma_testigo' => 'nullable|string',    // Ahora es Base64
                'imagenes' => 'nullable|array',          // Array de Base64
                'imagenes.*' => 'nullable|string',       // Cada elemento es una cadena Base64
                'finalizada_en' => 'nullable|date',      // Fecha de finalización
            ]);

            // No necesitamos guardar archivos en storage, ya vienen como Base64
            // y se guardarán directamente en la DB gracias al fillable y casts.

            // Crear o actualizar el registro de cierre
            // Usamos updateOrCreate para manejar casos donde se pueda intentar cerrar una visita más de una vez
            // o si un borrador ya existe.
            $cierreVisita = CierreVisita::updateOrCreate(
                ['visita_id' => $data['visita_id']], // Clave para buscar/actualizar
                $data                                // Datos a insertar/actualizar
            );

            // Actualizar el estado de la visita principal
            $visita = Visita::findOrFail($data['visita_id']);
            $visita->update(['estado' => 'finalizada']);

            Log::info('Cierre de Visita online guardado/actualizado con éxito.', ['visita_id' => $data['visita_id']]);

            return response()->json(['message' => '✅ Visita finalizada con éxito.'], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("Error de validación al guardar Cierre de Visita (online): " . $e->getMessage(), ['errors' => $e->errors(), 'data' => $request->all()]);
            return response()->json(['message' => 'Error de validación.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error("Error inesperado al guardar Cierre de Visita (online): " . $e->getMessage(), ['data' => $request->all(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Error interno del servidor al finalizar la visita.', 'error' => $e->getMessage()], 500);
        }
    }
        // zona de sincronizar con offline 

     public function syncOffline(Request $request)
        {
            // Log de depuración
            Log::info('Datos recibidos para sincronizar cierre de visita:', $request->all());

            try {
                // Validación correcta usando el facade Validator o el método validate() del request
                $validatedData = $request->validate([
                    'visita_id' => 'nullable|integer|exists:visitas,id',
                    'fecha_cierre' => 'required|date',
                    'estado_visita' => 'required|string|in:completado,pendiente,cancelado',
                    'observaciones_finales' => 'nullable|string',
                    'recomendaciones' => 'nullable|string',
                    'firma_responsable' => 'required|string',
                    'firma_recibe' => 'required|string',
                    'firma_testigo' => 'nullable|string',
                    'imagenes' => 'nullable|array',
                    'imagenes.*' => 'nullable|string', // Aceptamos strings Base64 directamente
                    'finalizada_en' => 'nullable|date'
                ]);

                // Procesamiento de imágenes (opcional, si necesitas guardarlas como archivos)
                if (!empty($validatedData['imagenes'])) {
                    $validatedData['imagenes'] = json_encode($validatedData['imagenes']); // Guardar como JSON
                }

                // Buscar o crear el registro
                $cierreVisita = CierreVisita::updateOrCreate(
                    ['visita_id' => $validatedData['visita_id']],
                    $validatedData
                );

                Log::info('Cierre de visita sincronizado exitosamente', ['id' => $cierreVisita->id]);

                return response()->json([
                    'success' => true,
                    'message' => 'Cierre de visita sincronizado correctamente',
                    'data' => $cierreVisita
                ]);

            } catch (\Illuminate\Validation\ValidationException $e) {
                Log::error('Error de validación al sincronizar cierre de visita', [
                    'errors' => $e->errors(),
                    'input' => $request->all()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $e->errors()
                ], 422);
                
            } catch (\Exception $e) {
                Log::error('Error al sincronizar cierre de visita: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Error interno del servidor',
                    'error' => $e->getMessage()
                ], 500);
            }
        }
    // fin de sincronizador 

}
