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
        $data = $request->json()->all();
        Log::info('Datos recibidos para sincronizar Cierre de Visita:', $data);

        try {
            $request->validate([
                'visita_id' => 'required|integer',
                'fecha_cierre' => 'required|date',
                'estado_visita' => 'required|string|in:completado,pendiente,cancelado',
                'observaciones_finales' => 'nullable|string', // ✅ CAMBIO DE NOMBRE
                'recomendaciones' => 'nullable|string',      // ✅ NUEVO CAMPO

                'firma_responsable' => 'required|string', // ✅ CAMBIO DE NOMBRE
                'firma_recibe' => 'required|string',
                'firma_testigo' => 'nullable|string',
                'imagenes' => 'nullable|array',
                'imagenes.*' => 'nullable|string',
                'finalizada_en' => 'nullable|date', // ✅ NUEVO CAMPO
            ]);

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
