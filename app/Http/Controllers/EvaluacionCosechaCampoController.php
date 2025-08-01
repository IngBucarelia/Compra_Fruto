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
            $data = $request->json()->all();
            
            Log::info('Datos recibidos para evaluacion_cosecha:', [
                'raw_data' => $request->getContent(), 
                'parsed_data' => $data
            ]);

            // Validar que sea un array
            if (!is_array($data)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Formato inválido. Se esperaba un array de evaluaciones.'
                ], 422);
            }

            DB::beginTransaction();
            try {
                $sincronizados = 0;
                $errores = [];

                foreach ($data as $index => $evaluacion) {
                    // Validar estructura básica
                    if (!is_array($evaluacion)) {
                        $errores[] = [
                            'index' => $index,
                            'error' => 'El registro no es un array válido'
                        ];
                        continue;
                    }

                    // Validar campos requeridos
                    $validator = Validator::make($evaluacion, [
                        'visita_id' => 'required|exists:visitas,id',
                        'indexeddb_id' => 'required|string',
                        'variedad_fruto' => 'required|in:guinense,hibrido',
                        'cantidad_racimos' => 'required|integer|min:0',
                        'verde' => 'nullable|integer|min:0|max:100',
                        'maduro' => 'nullable|integer|min:0|max:100',
                        'sobremaduro' => 'nullable|integer|min:0|max:100',
                        'pedunculo' => 'nullable|integer|min:0|max:100',
                        'conformacion' => 'nullable|string|max:255',
                        'observaciones' => 'nullable|string|max:1000'
                    ]);

                    if ($validator->fails()) {
                        $errores[] = [
                            'index' => $index,
                            'error' => 'Error de validación',
                            'errors' => $validator->errors()->all()
                        ];
                        continue;
                    }

                    // Preparar datos para guardar
                    $evaluacionData = [
                        'visita_id' => $evaluacion['visita_id'],
                        'indexeddb_id' => $evaluacion['indexeddb_id'],
                        'variedad_fruto' => $evaluacion['variedad_fruto'],
                        'cantidad_racimos' => $evaluacion['cantidad_racimos'],
                        'verde' => $evaluacion['verde'] ?? null,
                        'maduro' => $evaluacion['maduro'] ?? null,
                        'sobremaduro' => $evaluacion['sobremaduro'] ?? null,
                        'pedunculo' => $evaluacion['pedunculo'] ?? null,
                        'conformacion' => $evaluacion['variedad_fruto'] === 'hibrido' ? ($evaluacion['conformacion'] ?? null) : null,
                        'observaciones' => $evaluacion['observaciones'] ?? null,
                        'created_at' => $evaluacion['created_at'] ?? now(),
                        'updated_at' => $evaluacion['updated_at'] ?? now()
                    ];

                    // Guardar o actualizar
                    EvaluacionCosechaCampo::updateOrCreate(
                        ['indexeddb_id' => $evaluacion['indexeddb_id']],
                        $evaluacionData
                    );
                    
                    $sincronizados++;
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Sincronización completada',
                    'sincronizados' => $sincronizados,
                    'errores' => $errores
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Error sincronizando evaluaciones: " . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Error al sincronizar evaluaciones',
                    'error' => $e->getMessage()
                ], 500);
            }
        }
}

