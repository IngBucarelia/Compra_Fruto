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
            $visita = \App\Models\Visita::findOrFail($visita_id);

            return view('areas.create', compact('visita'));
        }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar la estructura general del request: debe contener un array 'areas'
        $request->validate([
            'visita_id' => 'required|exists:visitas,id',
            'areas' => 'required|array|min:1', // Espera un array de áreas, al menos una
        ], [
            'areas.required' => 'Debe añadir al menos un formulario de área.',
            'areas.array' => 'Los datos de área deben ser un formato válido.',
            'areas.min' => 'Debe añadir al menos un formulario de área.',
        ]);

        $visitaId = $request->input('visita_id');
        $submittedAreas = $request->input('areas');

        DB::beginTransaction(); // Iniciar una transacción de base de datos
        try {
            foreach ($submittedAreas as $index => $areaData) {
                $rules = [
                    'material' => 'required|in:guinense,hibrido',
                    'estado' => 'required|in:desarrollo,produccion',
                    'anio_siembra' => 'required|date',
                    'area' => 'required|numeric|min:0',
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

                if (isset($areaData['aplica_orden_plantis']) && (bool)$areaData['aplica_orden_plantis']) {
                    $rules['orden_plantis_numero'] = 'required|integer|min:0';
                    $rules['numero_plantas_orden_plantis'] = 'required|integer|min:0';
                    $rules['estado_oren_plantis'] = 'required|in:desarrollo,produccion';
                }

                $messages = [
                    'areas.*.material.required' => 'El material del área #:index es obligatorio.',
                    'areas.*.material.in' => 'El material del área #:index no es válido.',
                    'areas.*.estado.required' => 'El estado del área #:index es obligatorio.',
                    'areas.*.estado.in' => 'El estado del área #:index no es válido.',
                    'areas.*.anio_siembra.required' => 'El año de siembra del área #:index es obligatorio.',
                    'areas.*.anio_siembra.date' => 'El año de siembra del área #:index debe ser una fecha válida.',
                    'areas.*.area.required' => 'El área (m²) del área #:index es obligatoria.',
                    'areas.*.area.numeric' => 'El área (m²) del área #:index debe ser un número.',
                    'areas.*.area.min' => 'El área (m²) del área #:index debe ser al menos :min.',
                    'areas.*.aplica_orden_plantis.required' => 'Debe indicar si aplica Orden Plantis para el área #:index.',
                    'areas.*.aplica_orden_plantis.boolean' => 'El valor de "Aplica Orden Plantis" para el área #:index no es válido.',
                    'areas.*.orden_plantis_numero.required' => 'El número de Orden Plantis del área #:index es obligatorio cuando aplica.',
                    'areas.*.numero_plantas_orden_plantis.required' => 'El número de plantas de Orden Plantis del área #:index es obligatorio cuando aplica.',
                    'areas.*.estado_oren_plantis.required' => 'El estado de Orden Plantis del área #:index es obligatorio cuando aplica.',
                ];

                $validator = Validator::make($areaData, $rules, $messages);

                if ($validator->fails()) {
                    DB::rollBack();
                    return redirect()->back()->withErrors($validator)->withInput()->with('error_area_index', $index);
                }

                $validatedAreaData = $validator->validated();

                if (!(bool)$validatedAreaData['aplica_orden_plantis']) {
                    $validatedAreaData['orden_plantis_numero'] = null;
                    $validatedAreaData['numero_plantas_orden_plantis'] = null;
                    $validatedAreaData['estado_oren_plantis'] = null;
                }

                $validatedAreaData['visita_id'] = $visitaId;

                Area::create($validatedAreaData);
            }

            $visita = Visita::find($visitaId);
            if ($visita && $visita->estado === 'pendiente') {
                $visita->estado = 'en_ejecucion';
                $visita->save();
            }

            DB::commit();

            return redirect()->route('fertilizaciones.create', ['visita_id' => $visitaId])
                ->with('success', '✅ Áreas registradas correctamente. Continúa con la fertilización.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al guardar áreas: " . $e->getMessage(), ['trace' => $e->getTraceAsString(), 'request' => $request->all()]);
            // ✅ ESTA LÍNEA ES CLAVE PARA LA DEPURACIÓN INMEDIATA
            dd($e->getMessage()); // Muestra el mensaje de error directamente en el navegador
            // Después de depurar, comenta o elimina esta línea y deja la redirección:
            // return redirect()->back()->with('error', 'Ocurrió un error inesperado al guardar las áreas: ' . $e->getMessage())->withInput();
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
            'anio_siembra' => 'required|date',
            'area' => 'required|numeric|min:0',

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
            $rules['orden_plantis_numero'] = 'required|integer|min:0';
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
        $submissions = $request->input('submissions');
        $results = [];

        // Asegurarse de que 'submissions' sea un array válido
        if (!is_array($submissions)) {
            Log::error('Invalid sync data: "submissions" is not an array.', ['request' => $request->all()]);
            return response()->json(['message' => 'Invalid sync data.'], 400);
        }

        foreach ($submissions as $submission) {
            $formName = $submission['formName'] ?? null;
            $formData = $submission['formData'] ?? [];
            $local_id = $submission['local_id'] ?? null; // El ID único generado en el frontend

            // Log para depuración de cada envío
            Log::info("Processing offline submission for form: {$formName}, local_id: {$local_id}", ['formData' => $formData]);

            try {
                if ($formName === 'area') {
                    $rules = [
                        'visita_id' => 'required|exists:visitas,id',
                        'local_id' => 'required|string|max:36', // ✅ Validar el local_id (UUID)
                        'material' => 'required|in:guinense,hibrido',
                        'estado' => 'required|in:desarrollo,produccion',
                        'anio_siembra' => 'required|date',
                        'area' => 'required|numeric|min:0',
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

                    // Reglas condicionales para 'aplica_orden_plantis'
                    // Asegúrate de que 'aplica_orden_plantis' se interprete como booleano
                    if (isset($formData['aplica_orden_plantis']) && (bool)$formData['aplica_orden_plantis']) {
                        $rules['orden_plantis_numero'] = 'required|integer|min:0';
                        $rules['numero_plantas_orden_plantis'] = 'required|integer|min:0';
                        $rules['estado_oren_plantis'] = 'required|in:desarrollo,produccion';
                    }

                    $validator = Validator::make($formData, $rules);

                    if ($validator->fails()) {
                        // Devuelve los errores de validación específicos para este envío
                        $results[] = [
                            'id' => $local_id, // Usar local_id para identificar el error en el frontend
                            'formName' => $formName,
                            'success' => false,
                            'message' => 'Validation error.',
                            'errors' => $validator->errors()->all() // Mensajes de error detallados
                        ];
                        continue; // Pasa al siguiente envío en el bucle
                    }

                    $validatedData = $validator->validated();

                    // Asegurar que los campos condicionales sean null si aplica_orden_plantis es false
                    if (!(bool)$validatedData['aplica_orden_plantis']) {
                        $validatedData['orden_plantis_numero'] = null;
                        $validatedData['numero_plantas_orden_plantis'] = null;
                        $validatedData['estado_oren_plantis'] = null;
                    }

                    // ✅ Usar 'local_id' para buscar y actualizar o crear el registro
                    Area::updateOrCreate(
                        ['local_id' => $validatedData['local_id']], // Clave para buscar
                        $validatedData // Datos a crear o actualizar
                    );

                    // Opcional: Actualizar el estado de la visita a 'en_ejecucion'
                    $visita = Visita::find($validatedData['visita_id']);
                    if ($visita && $visita->estado === 'pendiente') {
                        $visita->estado = 'en_ejecucion';
                        $visita->save();
                    }

                    $results[] = ['id' => $local_id, 'formName' => $formName, 'success' => true];

                } else {
                    // Aquí puedes añadir lógica para otros formName (ej. 'fertilizacion', 'polinizacion')
                    // Por ahora, si el formName no es 'area', lo marcamos como no soportado.
                    $results[] = ['id' => $local_id, 'formName' => $formName, 'success' => false, 'message' => 'Form type not supported.'];
                }

            } catch (\Exception $e) {
                // Captura cualquier otro error inesperado
                Log::error("Unexpected error syncing offline data for form: {$formName}, local_id: {$local_id}. Error: " . $e->getMessage(), ['trace' => $e->getTraceAsString(), 'data' => $formData]);
                $results[] = [
                    'id' => $local_id,
                    'formName' => $formName,
                    'success' => false,
                    'message' => 'Internal server error: ' . $e->getMessage()
                ];
            }
        }

        return response()->json(['results' => $results]);
    }
    // controllador offline
     

    public function syncOffline(Request $request)
    {
        $submissions = $request->input('submissions');
        $results = [];

        // Asegurarse de que 'submissions' sea un array válido
        if (!is_array($submissions)) {
            Log::error('Invalid sync data: "submissions" is not an array.', ['request' => $request->all()]);
            return response()->json(['message' => 'Invalid sync data.'], 400);
        }

        foreach ($submissions as $submission) {
            $formName = $submission['formName'] ?? null;
            $formData = $submission['formData'] ?? [];
            $local_id = $submission['local_id'] ?? null; // El ID único generado en el frontend

            // Log para depuración de cada envío
            Log::info("Processing offline submission for form: {$formName}, local_id: {$local_id}", ['formData' => $formData]);

            try {
                if ($formName === 'area') {
                    $rules = [
                        'visita_id' => 'required|exists:visitas,id',
                        'local_id' => 'required|string|max:36', // ✅ Validar el local_id (UUID)
                        'material' => 'required|in:guinense,hibrido',
                        'estado' => 'required|in:desarrollo,produccion',
                        'anio_siembra' => 'required|date',
                        'area' => 'required|numeric|min:0',
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

                    // Reglas condicionales para 'aplica_orden_plantis'
                    // Asegúrate de que 'aplica_orden_plantis' se interprete como booleano
                    if (isset($formData['aplica_orden_plantis']) && (bool)$formData['aplica_orden_plantis']) {
                        $rules['orden_plantis_numero'] = 'required|integer|min:0';
                        $rules['numero_plantas_orden_plantis'] = 'required|integer|min:0';
                        $rules['estado_oren_plantis'] = 'required|in:desarrollo,produccion';
                    }

                    $validator = Validator::make($formData, $rules);

                    if ($validator->fails()) {
                        // Devuelve los errores de validación específicos para este envío
                        $results[] = [
                            'id' => $local_id, // Usar local_id para identificar el error en el frontend
                            'formName' => $formName,
                            'success' => false,
                            'message' => 'Validation error.',
                            'errors' => $validator->errors()->all() // Mensajes de error detallados
                        ];
                        continue; // Pasa al siguiente envío en el bucle
                    }

                    $validatedData = $validator->validated();

                    // Asegurar que los campos condicionales sean null si aplica_orden_plantis es false
                    if (!(bool)$validatedData['aplica_orden_plantis']) {
                        $validatedData['orden_plantis_numero'] = null;
                        $validatedData['numero_plantas_orden_plantis'] = null;
                        $validatedData['estado_oren_plantis'] = null;
                    }

                    // ✅ Usar 'local_id' para buscar y actualizar o crear el registro
                    Area::updateOrCreate(
                        ['local_id' => $validatedData['local_id']], // Clave para buscar
                        $validatedData // Datos a crear o actualizar
                    );

                    // Opcional: Actualizar el estado de la visita a 'en_ejecucion'
                    $visita = Visita::find($validatedData['visita_id']);
                    if ($visita && $visita->estado === 'pendiente') {
                        $visita->estado = 'en_ejecucion';
                        $visita->save();
                    }

                    $results[] = ['id' => $local_id, 'formName' => $formName, 'success' => true];

                } else {
                    // Aquí puedes añadir lógica para otros formName (ej. 'fertilizacion', 'polinizacion')
                    // Por ahora, si el formName no es 'area', lo marcamos como no soportado.
                    $results[] = ['id' => $local_id, 'formName' => $formName, 'success' => false, 'message' => 'Form type not supported.'];
                }

            } catch (\Exception $e) {
                // Captura cualquier otro error inesperado
                Log::error("Unexpected error syncing offline data for form: {$formName}, local_id: {$local_id}. Error: " . $e->getMessage(), ['trace' => $e->getTraceAsString(), 'data' => $formData]);
                $results[] = [
                    'id' => $local_id,
                    'formName' => $formName,
                    'success' => false,
                    'message' => 'Internal server error: ' . $e->getMessage()
                ];
            }
        }

        return response()->json(['results' => $results]);
    }

}
