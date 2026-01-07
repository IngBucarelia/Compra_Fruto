<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log; // Para registrar errores

use App\Models\Area;
use App\Models\Visita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Importar DB para transacciones
use Illuminate\Support\Facades\Validator; // Importar Validator


class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
public function create(Request $request)
{
    $visita_id = $request->query('visita_id');

    // 1. Cargar la visita actual
    $visita = Visita::with(['proveedor', 'plantacion', 'areas'])
        ->findOrFail($visita_id);

    // 2. Buscar la visita inmediatamente anterior de la misma plantación
    $visitaAnterior = Visita::where('plantacion_id', $visita->plantacion_id)
        ->where('id', '<', $visita->id)
        ->orderBy('id', 'desc')
        ->first();

    // 3. Si NO existe visita anterior → colección vacía
    $areasPenultima = collect();
    $tieneAreasActuales = $visita->areas->count() > 0;
    $tieneAreasPrevias = false;

    if ($visitaAnterior) {
        // 4. Traer TODAS las áreas de la visita anterior
        $areasPenultima = Area::where('visita_id', $visitaAnterior->id)->get();
        $tieneAreasPrevias = $areasPenultima->count() > 0;
    }

    return view('areas.create', compact('visita', 'areasPenultima', 'tieneAreasActuales', 'tieneAreasPrevias'));
}



    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        // Validar la estructura general del request
        $request->validate([
            'visita_id' => 'required|exists:visitas,id',
            'area_total_finca_hectareas' => 'required|numeric|min:0',
            'numero_palmas_total_finca' => 'required|integer|min:0', 
            'ciclos_cosecha_total' => 'required|integer|min:0',
            'produccion_total' => 'required|numeric|min:0',
            'areas' => 'required|array|min:1',
        ], [
            'areas.required' => 'Debe añadir al menos un formulario de área.',
            'areas.array' => 'Los datos de área deben ser un formato válido.',
            'areas.min' => 'Debe añadir al menos un formulario de área.',
        ]);

        $visitaId = $request->input('visita_id');
        $submittedAreas = $request->input('areas');

        // ⭐⭐ RECALCULAR TOTAL DE PALMAS INCLUYENDO ORDEN PLANTIS ⭐⭐
        $totalPalmasRecalculado = 0;
        $areasCount = 0;
        
        foreach ($submittedAreas as $areaData) {
            $palmasEstaArea = 0;
            
            // 1. Sumar palmas según estado
            if ($areaData['estado'] === 'desarrollo') {
                $palmasEstaArea += (int)($areaData['numero_palmas_desarrollo'] ?? 0);
            } elseif ($areaData['estado'] === 'produccion') {
                $palmasEstaArea += (int)($areaData['numero_palmas_produccion'] ?? 0);
            }
            
            // 2. Sumar plantas de Orden Plantis si aplica
            if (isset($areaData['aplica_orden_plantis']) && (bool)$areaData['aplica_orden_plantis']) {
                $palmasEstaArea += (int)($areaData['numero_plantas_orden_plantis'] ?? 0);
            }
            
            $totalPalmasRecalculado += $palmasEstaArea;
            $areasCount++;
            
            // DEBUG: Ver cálculo por área
            Log::info("Área #{$areasCount} - Palmas: {$palmasEstaArea}", [
                'estado' => $areaData['estado'] ?? 'N/A',
                'desarrollo' => $areaData['numero_palmas_desarrollo'] ?? 0,
                'produccion' => $areaData['numero_palmas_produccion'] ?? 0,
                'orden_plantis' => (bool)($areaData['aplica_orden_plantis'] ?? false),
                'plantas_orden_plantis' => $areaData['numero_plantas_orden_plantis'] ?? 0,
            ]);
        }
        
        // ⭐⭐ USAR EL TOTAL RECALCULADO (INCLUYE ORDEN PLANTIS) ⭐⭐
        $resumenData = [
            'area_total_finca_hectareas' => $request->input('area_total_finca_hectareas'),
            'numero_palmas_total_finca' => $totalPalmasRecalculado, // ⭐⭐ CAMBIADO: Usar recalculado ⭐⭐
            'ciclos_cosecha_total' => $request->input('ciclos_cosecha_total'),
            'produccion_total' => $request->input('produccion_total'),
        ];

        // ⭐⭐ VERIFICAR SI EL TOTAL DEL FRONTEND COINCIDE CON EL RECALCULADO ⭐⭐
        $totalFrontend = (int)$request->input('numero_palmas_total_finca');
        if ($totalFrontend !== $totalPalmasRecalculado) {
            Log::warning("Discrepancia en total de palmas", [
                'frontend' => $totalFrontend,
                'recalculado' => $totalPalmasRecalculado,
                'diferencia' => $totalPalmasRecalculado - $totalFrontend
            ]);
        }

        DB::beginTransaction();
        try {
            foreach ($submittedAreas as $index => $areaData) {
                $rules = [
                    'variedad' => 'required|string',
                    'material' => 'required|string',
                    'estado' => 'required|in:desarrollo,produccion',
                    'anio_siembra' => 'required|string|max:4|regex:/^\d{4}$/',
                    
                    // Campos de compatibilidad (ocultos en el formulario)
                    'area' => 'nullable|numeric|min:0',
                    'area_total_finca_hectareas' => 'nullable|numeric|min:0',
                    'numero_palmas_total_finca' => 'nullable|integer|min:0',
                    'ciclos_cosecha' => 'nullable|integer|min:0',
                    'produccion_toneladas_por_mes' => 'nullable|numeric|min:0',
                    
                    // Campos condicionales según estado
                    'area_palmas_desarrollo_hectareas' => 'nullable|numeric|min:0',
                    'numero_palmas_desarrollo' => 'nullable|integer|min:0',
                    'area_palmas_produccion_hectareas' => 'nullable|numeric|min:0',
                    'numero_palmas_produccion' => 'nullable|integer|min:0',
                    
                    'aplica_orden_plantis' => 'required|boolean',
                    'orden_plantis_numero' => 'nullable|string',
                    'numero_plantas_orden_plantis' => 'nullable|integer|min:0',
                    'estado_oren_plantis' => 'nullable|in:desarrollo,produccion',
                ];

                // Validación condicional basada en el estado
                if ($areaData['estado'] === 'desarrollo') {
                    $rules['area_palmas_desarrollo_hectareas'] = 'required|numeric|min:0';
                    $rules['numero_palmas_desarrollo'] = 'required|integer|min:0';
                } else if ($areaData['estado'] === 'produccion') {
                    $rules['area_palmas_produccion_hectareas'] = 'required|numeric|min:0';
                    $rules['numero_palmas_produccion'] = 'required|integer|min:0';
                }

                // Validación para Orden Plantis
                if (isset($areaData['aplica_orden_plantis']) && (bool)$areaData['aplica_orden_plantis']) {
                    $rules['numero_plantas_orden_plantis'] = 'required|integer|min:0';
                    $rules['estado_oren_plantis'] = 'required|in:desarrollo,produccion';
                }

                $messages = [
                    'areas.*.material.required' => 'El material del área #' . ($index + 1) . ' es obligatorio.',
                    'areas.*.estado.required' => 'El estado del área #' . ($index + 1) . ' es obligatorio.',
                    'areas.*.anio_siembra.required' => 'El año de siembra del área #' . ($index + 1) . ' es obligatorio.',
                    'areas.*.area_palmas_desarrollo_hectareas.required' => 'El área de desarrollo (Ha) del área #' . ($index + 1) . ' es obligatoria cuando el estado es Desarrollo.',
                    'areas.*.numero_palmas_desarrollo.required' => 'El número de palmas en desarrollo del área #' . ($index + 1) . ' es obligatorio cuando el estado es Desarrollo.',
                    'areas.*.area_palmas_produccion_hectareas.required' => 'El área de producción (Ha) del área #' . ($index + 1) . ' es obligatoria cuando el estado es Producción.',
                    'areas.*.numero_palmas_produccion.required' => 'El número de palmas en producción del área #' . ($index + 1) . ' es obligatorio cuando el estado es Producción.',
                ];

                $validator = Validator::make($areaData, $rules, $messages);

                if ($validator->fails()) {
                    DB::rollBack();
                    return redirect()->back()->withErrors($validator)->withInput()->with('error_area_index', $index);
                }

                $validatedAreaData = $validator->validated();

                // 🔥 **AQUÍ ESTÁ LA SOLUCIÓN: COMBINAR DATOS DEL RESUMEN CON DATOS DEL ÁREA**
                $areaToSave = array_merge($validatedAreaData, [
                    'area_total_finca_hectareas' => $resumenData['area_total_finca_hectareas'],
                    'numero_palmas_total_finca' => $resumenData['numero_palmas_total_finca'], // ⭐⭐ AHORA INCLUYE ORDEN PLANTIS ⭐⭐
                    'ciclos_cosecha' => $resumenData['ciclos_cosecha_total'],
                    'produccion_toneladas_por_mes' => $resumenData['produccion_total'],
                ]);

                // Limpiar campos según el estado
                if ($areaToSave['estado'] === 'desarrollo') {
                    $areaToSave['area_palmas_produccion_hectareas'] = null;
                    $areaToSave['numero_palmas_produccion'] = null;
                } else {
                    $areaToSave['area_palmas_desarrollo_hectareas'] = null;
                    $areaToSave['numero_palmas_desarrollo'] = null;
                }

                // Limpiar campos de Orden Plantis si no aplica
                if (!(bool)$areaToSave['aplica_orden_plantis']) {
                    $areaToSave['orden_plantis_numero'] = null;
                    $areaToSave['numero_plantas_orden_plantis'] = null;
                    $areaToSave['estado_oren_plantis'] = null;
                }

                $areaToSave['visita_id'] = $visitaId;

                // ⭐⭐ AÑADIR CAMPO ADICIONAL PARA REGISTRAR SI INCLUYE ORDEN PLANTIS ⭐⭐
                $areaToSave['incluye_orden_plantis_en_total'] = (bool)($areaData['aplica_orden_plantis'] ?? false);

                // DEBUG: Ver qué datos se están guardando
                Log::info("Guardando área #" . ($index + 1) . ":", [
                    'datos' => $areaToSave,
                    'palmas_esta_area' => $palmasEstaArea ?? 0
                ]);

                Area::create($areaToSave);
            }

            // ⭐⭐ ACTUALIZAR LA VISITA CON EL TOTAL RECALCULADO ⭐⭐
            $visita = Visita::find($visitaId);
            if ($visita) {
                $updateData = [
                    'area_total_finca_hectareas' => $resumenData['area_total_finca_hectareas'],
                    'numero_palmas_total_finca' => $resumenData['numero_palmas_total_finca'], // ⭐⭐ INCLUYE ORDEN PLANTIS ⭐⭐
                    'ciclos_cosecha_total' => $resumenData['ciclos_cosecha_total'],
                    'produccion_total' => $resumenData['produccion_total'],
                ];
                
                if ($visita->estado === 'pendiente') {
                    $updateData['estado'] = 'en_ejecucion';
                }
                
                $visita->update($updateData);
                
                Log::info("Visita actualizada con total de palmas (incluye Orden Plantis):", [
                    'visita_id' => $visitaId,
                    'numero_palmas_total_finca' => $resumenData['numero_palmas_total_finca'],
                    'areas_count' => $areasCount
                ]);
            }

            DB::commit();

            return redirect()->route('fertilizaciones.create', ['visita_id' => $visitaId])
                ->with('success', '✅ Áreas registradas correctamente. Total de palmas: ' . $resumenData['numero_palmas_total_finca'] . ' (incluye Orden Plantis). Continúa con la fertilización.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al guardar áreas: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(), 
                'request_data' => $request->all(),
                'resumen_data' => $resumenData,
                'total_palmas_recalculado' => $totalPalmasRecalculado ?? 0
            ]);
            return redirect()->back()->with('error', 'Ocurrió un error inesperado al guardar las áreas: ' . $e->getMessage())->withInput();
        }
    }
    /**
     * Muestra el formulario para editar un área específica.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $area = Area::findOrFail($id);
        $visita = $area->visita; // Obtener la visita relacionada

        return view('areas.edit', compact('area', 'visita'));
    }

    /**
     * Actualiza un área específica.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $area = Area::findOrFail($id);

        $rules = [
            'visita_id' => 'required|exists:visitas,id',
            'material' => 'required|in:guinense,hibrido',
            'estado' => 'required|in:desarrollo,produccion',
            'anio_siembra' => [
                    'required',
                    'regex:/^\d{4}$/'
                ],
            'area' => 'nullable|numeric|min:0',

            'area_total_finca_hectareas' => 'nullable|numeric|min:0',
            'numero_palmas_total_finca' => 'nullable|integer|min:0',
            'area_palmas_desarrollo_hectareas' => 'nullable|numeric|min:0',
            'numero_palmas_desarrollo' => 'nullable|integer|min:0',
            'area_palmas_produccion_hectareas' => 'nullable|numeric|min:0',
            'numero_palmas_produccion' => 'nullable|integer|min:0',
            'ciclos_cosecha' => 'nullable|integer|min:0',
            'produccion_toneladas_por_mes' => 'nullable|numeric|min:0',

            'aplica_orden_plantis' => 'required|boolean',
            'orden_plantis_numero' => 'nullable|integer|min:0',
            'numero_plantas_orden_plantis' => 'nullable|integer|min:0',
            'estado_oren_plantis' => 'nullable|in:desarrollo,produccion',
        ];

        if ($request->input('aplica_orden_plantis')) {
            $rules['numero_plantas_orden_plantis'] = 'required|integer|min:0';
            $rules['estado_oren_plantis'] = 'required|in:desarrollo,produccion';
        }

        $data = $request->validate($rules);

        if (!$data['aplica_orden_plantis']) {
            $data['orden_plantis_numero'] = null;
            $data['numero_plantas_orden_plantis'] = null;
            $data['estado_oren_plantis'] = null;
        }

        $area->update($data);

        return redirect()->route('fertilizaciones.create', ['visita_id' => $area->visita_id])
            ->with('success', '✅ Área actualizada correctamente. Continúa con la fertilización.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

     public function syncOfflineData(Request $request)
{
    $submissions = $request->input('submissions', []);
    $results = [];

    Log::info('📥 Sincronización Áreas OFFLINE', [
        'total' => count($submissions)
    ]);

    foreach ($submissions as $index => $data) {

        try {

            // 🔒 Validación
            $validator = Validator::make($data, [
                'visita_id' => 'required|exists:visitas,id',
                'local_id'  => 'required|string|max:36',

                'variedad' => 'required|string',
                'material' => 'required|string',
                'estado'   => 'required|in:desarrollo,produccion',

                // STRING (varchar)
                'anio_siembra' => ['required', 'regex:/^\d{4}$/'],

                'area' => 'nullable|numeric|min:0',
                'area_total_finca_hectareas' => 'nullable|numeric|min:0',
                'numero_palmas_total_finca' => 'nullable|integer|min:0',

                'area_palmas_desarrollo_hectareas' => 'nullable|numeric|min:0',
                'numero_palmas_desarrollo' => 'nullable|integer|min:0',

                'area_palmas_produccion_hectareas' => 'nullable|numeric|min:0',
                'numero_palmas_produccion' => 'nullable|integer|min:0',

                'ciclos_cosecha' => 'nullable|integer|min:0',
                'produccion_toneladas_por_mes' => 'nullable|numeric|min:0',

                'aplica_orden_plantis' => 'required|boolean',
                'orden_plantis_numero' => 'nullable|integer|min:0',
                'numero_plantas_orden_plantis' => 'nullable|integer|min:0',
                'estado_orden_plantis' => 'nullable|in:desarrollo,produccion',
            ]);

            if ($validator->fails()) {
                Log::warning('❌ Error validación Área', [
                    'local_id' => $data['local_id'],
                    'errors' => $validator->errors()->toArray()
                ]);

                $results[] = [
                    'id' => $data['local_id'],
                    'success' => false,
                    'errors' => $validator->errors()->all()
                ];
                continue;
            }

            $validated = $validator->validated();

            // Limpieza si no aplica orden plantis
            if (!$validated['aplica_orden_plantis']) {
                $validated['orden_plantis_numero'] = null;
                $validated['numero_plantas_orden_plantis'] = null;
                $validated['estado_orden_plantis'] = null;
            }

            // 💾 GUARDADO REAL
            $area = Area::updateOrCreate(
                ['local_id' => $validated['local_id']],
                $validated
            );

            Log::info('✅ Área guardada', [
                'id' => $area->id,
                'local_id' => $area->local_id
            ]);

            // Actualizar visita
            $visita = Visita::find($validated['visita_id']);
            if ($visita && $visita->estado === 'pendiente') {
                $visita->update(['estado' => 'en_ejecucion']);
            }

            $results[] = [
                'id' => $validated['local_id'],
                'success' => true,
                'area_id' => $area->id
            ];

        } catch (\Throwable $e) {

            Log::error('🔥 Error guardando Área', [
                'local_id' => $data['local_id'] ?? null,
                'error' => $e->getMessage()
            ]);

            $results[] = [
                'id' => $data['local_id'] ?? null,
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    return response()->json([
        'success' => true,
        'results' => $results
    ]);
}

    // controllador offline
     

    


}
