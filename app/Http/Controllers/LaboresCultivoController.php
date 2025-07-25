<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use App\Models\LaboresCultivo;
use App\Models\Visita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator; 



class LaboresCultivoController extends Controller
{
     public function create(int $visita_id)
    {
        $visita = Visita::with([
            'proveedor',
            'plantacion',
            'areas',
            'fertilizaciones.detalles',
            'polinizaciones',
            'sanidades',
            'suelo',
            'laboresCultivo' // ✅ Ahora cargará una colección de LaboresCultivo
        ])->findOrFail($visita_id);

        return view('labores_cultivo.create', compact('visita'));
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario principal (visita_id)
        $request->validate([
            'visita_id' => 'required|exists:visitas,id',
            // No hay validación para 'observaciones' generales aquí, ya que ahora está dentro de cada labor[]
        ]);

        $visitaId = $request->input('visita_id');

        // Validar cada entrada de labor dinámica
        $laboresData = $request->validate([
            'labores' => 'required|array', // Espera un array de bloques de labores
            'labores.*.tipo_planta' => 'required|string|in:guinense,hibrido',
            'labores.*.polinizacion' => 'nullable|integer|min:0|max:100',
            'labores.*.limpieza_calle' => 'nullable|integer|min:0|max:100',
            'labores.*.limpieza_plato' => 'nullable|integer|min:0|max:100',
            'labores.*.poda' => 'nullable|integer|min:0|max:100',
            'labores.*.fertilizacion' => 'nullable|integer|min:0|max:100',
            'labores.*.enmiendas' => 'nullable|integer|min:0|max:100',
            'labores.*.ubicacion_tusa_fibra' => 'nullable|integer|min:0|max:100',
            'labores.*.ubicacion_hoja' => 'nullable|integer|min:0|max:100',
            'labores.*.lugar_ubicacion_hoja' => 'nullable|integer|min:0|max:100',
            'labores.*.plantas_nectariferas' => 'nullable|integer|min:0|max:100',
            'labores.*.cobertura' => 'nullable|integer|min:0|max:100',
            'labores.*.labor_cosecha' => 'nullable|integer|min:0|max:100',
            'labores.*.calidad_fruta' => 'nullable|integer|min:0|max:100',
            'labores.*.recoleccion_fruta' => 'nullable|integer|min:0|max:100',
            'labores.*.drenajes' => 'nullable|integer|min:0|max:100',
            'labores.*.observaciones' => 'nullable|string|max:1000', // Observaciones para cada labor
        ]);

        DB::beginTransaction();
        try {
            // ✅ CAMBIO CLAVE: Eliminar todos los registros de labores existentes para esta visita
            // Esto simplifica la lógica de "actualización" para formularios dinámicos.
            LaboresCultivo::where('visita_id', $visitaId)->delete();

            // ✅ CAMBIO CLAVE: Crear un nuevo registro de LaboresCultivo para cada bloque de formulario
            foreach ($laboresData['labores'] as $laborEntry) {
                LaboresCultivo::create(array_merge($laborEntry, ['visita_id' => $visitaId]));
            }

            // Actualizar el estado de la visita principal si es necesario
            $visita = Visita::find($visitaId);
            if ($visita && $visita->estado === 'pendiente') {
                $visita->estado = 'en_ejecucion';
                $visita->save();
            }

            DB::commit();

            return redirect()->route('evaluacion.create', $visitaId)
                ->with('success', '✅ Registros de labores de cultivo guardados correctamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error("Error de validación al guardar labores de cultivo: " . $e->getMessage(), ['errors' => $e->errors(), 'request' => $request->all()]);
            return redirect()->back()->with('error', 'Ocurrió un error de validación al guardar las labores de cultivo: ' . $e->getMessage())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error inesperado al guardar labores de cultivo: " . $e->getMessage(), ['trace' => $e->getTraceAsString(), 'request' => $request->all()]);
            return redirect()->back()->with('error', 'Ocurrió un error interno del servidor al guardar las labores de cultivo: ' . $e->getMessage())->withInput();
        }
    }

    // El método edit ahora cargará un registro específico de LaboresCultivo por su ID
    public function edit(int $id) // ✅ CAMBIO: Ahora recibe el ID del registro de labor, no de la visita
    {
        $laborCultivo = LaboresCultivo::with('visita')->findOrFail($id); // Cargar el registro de labor específico
        $visita = $laborCultivo->visita; // Obtener la visita asociada

        // Para el formulario de edición, necesitamos cargar todos los datos de la visita para los acordeones
        $visita = Visita::with([
            'proveedor',
            'plantacion',
            'areas',
            'fertilizaciones.detalles',
            'polinizaciones',
            'sanidades',
            'suelo',
            'laboresCultivo' // Cargar todas las labores para mostrar en la sección de "registradas"
        ])->findOrFail($visita->id);

        // Pasar tanto la visita como el registro de labor específico a la vista de edición
        return view('labores_cultivo.edit', compact('visita', 'laborCultivo'));
    }

    public function update(Request $request, int $id)
    {
        $laborCultivo = LaboresCultivo::findOrFail($id);

        $data = $request->validate([
            'visita_id' => 'required|exists:visitas,id', // Asegurarse de que este campo se envíe desde el formulario de edición
            'tipo_planta' => 'required|string|in:guinense,hibrido',
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
            'observaciones' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $laborCultivo->update($data);

            // No es necesario actualizar el estado de la visita aquí, ya que se maneja en el store
            // o en el cierre de visita.

            DB::commit();

            // Redirigir de vuelta al formulario de creación/listado para la misma visita
            return redirect()->route('labores_cultivo.create', $laborCultivo->visita_id)
                ->with('success', '✅ Registro de labor de cultivo actualizado correctamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error("Error de validación al actualizar labor de cultivo: " . $e->getMessage(), ['errors' => $e->errors(), 'request' => $request->all()]);
            return redirect()->back()->with('error', 'Ocurrió un error de validación al actualizar la labor de cultivo: ' . $e->getMessage())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error inesperado al actualizar labor de cultivo: " . $e->getMessage(), ['trace' => $e->getTraceAsString(), 'request' => $request->all()]);
            return redirect()->back()->with('error', 'Ocurrió un error interno del servidor al actualizar la labor de cultivo: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(int $id)
    {
        $laboresCultivo = LaboresCultivo::findOrFail($id);
        $visitaId = $laboresCultivo->visita_id;

        DB::beginTransaction();
        try {
            $laboresCultivo->delete();
            DB::commit();
            return redirect()->route('labores_cultivo.create', ['visita_id' => $visitaId])
                             ->with('success', '✅ Registro de labor de cultivo eliminado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al eliminar labor de cultivo: " . $e->getMessage(), ['trace' => $e->getTraceAsString(), 'id' => $id]);
            return redirect()->back()->with('error', 'Ocurrió un error al eliminar el registro de labor de cultivo: ' . $e->getMessage());
        }
    }

                // controllador offline

                
           public function syncOffline(Request $request)
    {
        // Obtiene todos los datos JSON de la petición.
        // Se espera un array de objetos JSON, donde cada objeto es un registro de labor.
        $data = $request->json()->all();

        // ✅ MUY IMPORTANTE PARA DEPURAR: Revisa este log en storage/logs/laravel.log
        // para ver EXACTAMENTE qué datos está recibiendo el controlador.
        Log::info('Datos recibidos para sincronizar Labores de Cultivo (offline):', $data);

        // Validar que el dato recibido sea un array y que cada elemento cumpla las reglas.
        // Asumimos que $data es un array de objetos de labor.
        if (!is_array($data)) {
            Log::error("Datos de labores de cultivo no son un array.", ['data' => $data]);
            return response()->json(['message' => 'Formato de datos inválido. Se esperaba un array de labores.'], 422);
        }

        DB::beginTransaction();
        try {
            $sincronizadosCount = 0;
            $erroresValidacion = [];

            foreach ($data as $index => $laborEntry) {
                // Validar cada registro de labor individualmente
                $validator = Validator::make($laborEntry, [
                    'visita_id' => 'required|integer|exists:visitas,id',
                    'indexeddb_id' => 'required|string', // ID único generado en el frontend (IndexedDB)
                    'tipo_planta' => 'required|string|in:guinense,hibrido',
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
                    'observaciones' => 'nullable|string|max:1000', // Campo de observaciones generales
                ]);

                if ($validator->fails()) {
                    $erroresValidacion[] = [
                        'index' => $index,
                        'data' => $laborEntry,
                        'errors' => $validator->errors()->all()
                    ];
                    Log::warning("Error de validación para registro de labor #{$index}:", $validator->errors()->all());
                    continue; // Saltar a la siguiente labor si falla la validación
                }

                // Utiliza updateOrCreate para insertar o actualizar el registro.
                // La clave para buscar el registro existente es 'visita_id' y 'indexeddb_id'.
                // 'indexeddb_id' es crucial para identificar un registro específico que viene del frontend.
                LaboresCultivo::updateOrCreate(
                    [
                        'visita_id' => $laborEntry['visita_id'],
                        'indexeddb_id' => $laborEntry['indexeddb_id'] // ✅ Usar indexeddb_id para unicidad
                    ],
                    $laborEntry // Todos los datos validados
                );
                $sincronizadosCount++;
            }

            DB::commit();

            if (count($erroresValidacion) > 0) {
                Log::error("Algunos registros de Labores de Cultivo fallaron la validación durante la sincronización.", ['errores' => $erroresValidacion]);
                return response()->json([
                    'message' => "Se sincronizaron {$sincronizadosCount} registros. Algunos registros tuvieron errores de validación.",
                    'sincronizados' => $sincronizadosCount,
                    'errores_validacion' => $erroresValidacion
                ], 200); // Devuelve 200 OK pero con advertencias
            }

            Log::info('Todos los registros de Labores de Cultivo sincronizados con éxito.', ['visita_id' => $data[0]['visita_id'] ?? 'N/A', 'count' => $sincronizadosCount]);
            return response()->json(['message' => "Labores de Cultivo sincronizadas con éxito. Total: {$sincronizadosCount} registros."]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error inesperado al sincronizar Labores de Cultivo: " . $e->getMessage(), ['data' => $data, 'trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Error interno del servidor al sincronizar Labores de Cultivo.', 'error' => $e->getMessage()], 500);
        }
    }

}
