<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use App\Models\EvaluacionCosechaCampo;
use App\Models\Visita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator; 



class EvaluacionCosechaCampoController extends Controller
{
   public function create(Request $request)
    {
        $visita = Visita::with([
            'areas',
            'fertilizaciones.detalles',
            'polinizaciones',
            'sanidad',
            'suelo',
            'laboresCultivo',
            'evaluacionCosechaCampo' // ✅ Ahora cargará una colección de EvaluacionCosechaCampo
        ])->findOrFail($request->query('visita_id'));

        return view('evaluacion_cosecha.create', compact('visita'));
    }

    public function store(Request $request)
    {
        // Validar el visita_id principal
        $request->validate([
            'visita_id' => 'required|exists:visitas,id',
        ]);

        $visitaId = $request->input('visita_id');

        // Validar cada entrada de evaluación dinámica
        $evaluacionesData = $request->validate([
            'evaluaciones' => 'required|array', // Espera un array de bloques de evaluaciones
            'evaluaciones.*.variedad_fruto' => 'required|string|in:guinense,hibrido',
            'evaluaciones.*.cantidad_racimos' => 'required|integer|min:0',
            'evaluaciones.*.verde' => 'nullable|integer|min:0|max:100',
            'evaluaciones.*.maduro' => 'nullable|integer|min:0|max:100',
            'evaluaciones.*.sobremaduro' => 'nullable|integer|min:0|max:100',
            'evaluaciones.*.pedunculo' => 'nullable|integer|min:0|max:100',
            'evaluaciones.*.conformacion' => 'nullable|string|max:255', // ✅ NUEVO CAMPO: Conformación
            'evaluaciones.*.observaciones' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            // ✅ CAMBIO CLAVE: Eliminar todos los registros de evaluaciones existentes para esta visita
            EvaluacionCosechaCampo::where('visita_id', $visitaId)->delete();

            // ✅ CAMBIO CLAVE: Crear un nuevo registro de EvaluacionCosechaCampo para cada bloque de formulario
            foreach ($evaluacionesData['evaluaciones'] as $evaluacionEntry) {
                // Si 'conformacion' no se envió (porque no era 'hibrido'), asegúrate de que sea null
                if (!isset($evaluacionEntry['conformacion'])) {
                    $evaluacionEntry['conformacion'] = null;
                }
                EvaluacionCosechaCampo::create(array_merge($evaluacionEntry, ['visita_id' => $visitaId]));
            }

            // Actualizar el estado de la visita principal si es necesario
            $visita = Visita::find($visitaId);
            if ($visita && $visita->estado === 'pendiente') {
                $visita->estado = 'en_ejecucion';
                $visita->save();
            }

            DB::commit();

            return redirect()->route('cierre-visitas.create', $visitaId)
                ->with('success', '✅ Registros de evaluación de cosecha guardados correctamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error("Error de validación al guardar evaluación de cosecha: " . $e->getMessage(), ['errors' => $e->errors(), 'request' => $request->all()]);
            return redirect()->back()->with('error', 'Ocurrió un error de validación al guardar la evaluación de cosecha: ' . $e->getMessage())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error inesperado al guardar evaluación de cosecha: " . $e->getMessage(), ['trace' => $e->getTraceAsString(), 'request' => $request->all()]);
            return redirect()->back()->with('error', 'Ocurrió un error interno del servidor al guardar la evaluación de cosecha: ' . $e->getMessage())->withInput();
        }
    }

    // El método edit ahora cargará un registro específico de EvaluacionCosechaCampo por su ID
    public function edit(int $evaluacion) // ✅ CAMBIO: Ahora recibe el ID del registro de evaluación
    {
        $evaluacionCosecha = EvaluacionCosechaCampo::with('visita')->findOrFail($evaluacion); // Cargar el registro específico
        $visita = $evaluacionCosecha->visita; // Obtener la visita asociada

        // Para el formulario de edición, necesitamos cargar todos los datos de la visita para los acordeones
        $visita = Visita::with([
            'areas',
            'fertilizaciones.detalles',
            'polinizaciones',
            'sanidad',
            'suelo',
            'laboresCultivo',
            'evaluacionCosechaCampo' // Cargar todas las evaluaciones para mostrar en la sección de "registradas"
        ])->findOrFail($visita->id);

        // Pasar tanto la visita como el registro de evaluación específico a la vista de edición
        return view('evaluacion_cosecha.edit', compact('visita', 'evaluacionCosecha'));
    }

    public function update(Request $request, int $evaluacion) // ✅ CAMBIO: Recibe el ID del registro
    {
        $evaluacionCosecha = EvaluacionCosechaCampo::findOrFail($evaluacion);

        $data = $request->validate([
            'visita_id' => 'required|exists:visitas,id', // Asegurarse de que este campo se envíe desde el formulario de edición
            'variedad_fruto' => 'required|in:guinense,hibrido',
            'cantidad_racimos' => 'required|integer|min:0',
            'verde' => 'nullable|integer|min:0|max:100',
            'maduro' => 'nullable|integer|min:0|max:100',
            'sobremaduro' => 'nullable|integer|min:0|max:100',
            'pedunculo' => 'nullable|integer|min:0|max:100',
            'conformacion' => 'nullable|string|max:255', // ✅ NUEVO CAMPO: Conformación
            'observaciones' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            // Si 'conformacion' no se envió (porque no era 'hibrido'), asegúrate de que sea null
            if (!isset($data['conformacion'])) {
                $data['conformacion'] = null;
            }
            $evaluacionCosecha->update($data);

            DB::commit();

            // Redirigir de vuelta al formulario de creación/listado para la misma visita
            return redirect()->route('evaluacion.create', $evaluacionCosecha->visita_id)
                ->with('success', '✅ Registro de evaluación de cosecha actualizado correctamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error("Error de validación al actualizar evaluación de cosecha: " . $e->getMessage(), ['errors' => $e->errors(), 'request' => $request->all()]);
            return redirect()->back()->with('error', 'Ocurrió un error de validación al actualizar la evaluación de cosecha: ' . $e->getMessage())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error inesperado al actualizar evaluación de cosecha: " . $e->getMessage(), ['trace' => $e->getTraceAsString(), 'request' => $request->all()]);
            return redirect()->back()->with('error', 'Ocurrió un error interno del servidor al actualizar la evaluación de cosecha: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $evaluacion = EvaluacionCosechaCampo::findOrFail($id);
        $visitaId = $evaluacion->visita_id;

        DB::beginTransaction();
        try {
            $evaluacion->delete();
            DB::commit();
            return redirect()->route('evaluacion.create', ['visita_id' => $visitaId])
                             ->with('success', '🗑️ Evaluación de Cosecha de Campo eliminada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al eliminar evaluación de cosecha: " . $e->getMessage(), ['trace' => $e->getTraceAsString(), 'id' => $id]);
            return redirect()->back()->with('error', 'Ocurrió un error al eliminar el registro de evaluación de cosecha: ' . $e->getMessage());
        }
    }

        public function syncOffline(Request $request)
    {
        // Obtiene todos los datos JSON de la petición.
        // Se espera un array de objetos JSON, donde cada objeto es un registro de evaluación.
        $data = $request->json()->all();

        // ✅ MUY IMPORTANTE PARA DEPURAR: Revisa este log en storage/logs/laravel.log
        // para ver EXACTAMENTE qué datos está recibiendo el controlador.
        Log::info('Datos recibidos para sincronizar Evaluación de Cosecha de Campo (offline):', $data);

        // Validar que el dato recibido sea un array y que cada elemento cumpla las reglas.
        if (!is_array($data)) {
            Log::error("Datos de evaluación de cosecha no son un array.", ['data' => $data]);
            return response()->json(['message' => 'Formato de datos inválido. Se esperaba un array de evaluaciones.'], 422);
        }

        DB::beginTransaction();
        try {
            $sincronizadosCount = 0;
            $erroresValidacion = [];

            foreach ($data as $index => $evaluacionEntry) {
                // Validar cada registro de evaluación individualmente
                $validator = Validator::make($evaluacionEntry, [
                    'visita_id' => 'required|integer|exists:visitas,id',
                    'indexeddb_id' => 'required|string', // ID único generado en el frontend (IndexedDB)
                    'variedad_fruto' => 'required|string|in:guinense,hibrido',
                    'cantidad_racimos' => 'required|integer|min:0',
                    'verde' => 'nullable|integer|min:0|max:100',
                    'maduro' => 'nullable|integer|min:0|max:100',
                    'sobremaduro' => 'nullable|integer|min:0|max:100',
                    'pedunculo' => 'nullable|integer|min:0|max:100',
                    'conformacion' => 'nullable|string|max:255', // ✅ NUEVO CAMPO: Conformación
                    'observaciones' => 'nullable|string|max:1000',
                ]);

                if ($validator->fails()) {
                    $erroresValidacion[] = [
                        'index' => $index,
                        'data' => $evaluacionEntry,
                        'errors' => $validator->errors()->all()
                    ];
                    Log::warning("Error de validación para registro de evaluación #{$index}:", $validator->errors()->all());
                    continue; // Saltar a la siguiente evaluación si falla la validación
                }

                // Asegurarse de que 'conformacion' sea null si no es 'hibrido'
                // Esto es importante si el campo no se envía en el frontend para 'guinense'
                if (isset($evaluacionEntry['variedad_fruto']) && $evaluacionEntry['variedad_fruto'] !== 'hibrido') {
                    $evaluacionEntry['conformacion'] = null;
                }

                // Utiliza updateOrCreate para insertar o actualizar el registro.
                // La clave para buscar el registro existente es 'visita_id' y 'indexeddb_id'.
                EvaluacionCosechaCampo::updateOrCreate(
                    [
                        'visita_id' => $evaluacionEntry['visita_id'],
                        'indexeddb_id' => $evaluacionEntry['indexeddb_id'] // ✅ Usar indexeddb_id para unicidad
                    ],
                    $evaluacionEntry // Todos los datos validados
                );
                $sincronizadosCount++;
            }

            DB::commit();

            if (count($erroresValidacion) > 0) {
                Log::error("Algunos registros de Evaluación de Cosecha fallaron la validación durante la sincronización.", ['errores' => $erroresValidacion]);
                return response()->json([
                    'message' => "Se sincronizaron {$sincronizadosCount} registros. Algunos registros tuvieron errores de validación.",
                    'sincronizados' => $sincronizadosCount,
                    'errores_validacion' => $erroresValidacion
                ], 200); // Devuelve 200 OK pero con advertencias
            }

            Log::info('Todos los registros de Evaluación de Cosecha sincronizados con éxito.', ['visita_id' => $data[0]['visita_id'] ?? 'N/A', 'count' => $sincronizadosCount]);
            return response()->json(['message' => "Evaluaciones de Cosecha sincronizadas con éxito. Total: {$sincronizadosCount} registros."]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error inesperado al sincronizar Evaluación de Cosecha: " . $e->getMessage(), ['data' => $data, 'trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Error interno del servidor al sincronizar Evaluación de Cosecha.', 'error' => $e->getMessage()], 500);
        }
    }
}

