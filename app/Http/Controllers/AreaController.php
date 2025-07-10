<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log; // Para registrar errores

use App\Models\Area;
use Illuminate\Http\Request;

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
            $data = $request->validate([
                'visita_id' => 'required|exists:visitas,id',
                'material' => 'required|in:guinense,hibrido',
                'estado' => 'required|in:desarrollo,produccion',
                'anio_siembra' => 'required|date',
                'area' => 'required|integer',
                'orden_plantis_numero' => 'required|integer',
                'estado_oren_plantis' => 'required|in:desarrollo,produccion',
            ]);

            Area::create($data);

            $visita = \App\Models\Visita::find($data['visita_id']);
            if ($visita) {
                $visita->estado = 'en_ejecucion';
                $visita->save();
            }

            // Redirige al siguiente formulario (fertilización)
            return redirect()->route('fertilizaciones.create', ['visita_id' => $data['visita_id']])
                ->with('success', 'Área registrada. Continúa con la fertilización.');
        }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Area $area)
    {
        $visita = $area->visita()->with('proveedor')->first();
        return view('areas.edit', compact('area', 'visita'));
    }

    public function update(Request $request, Area $area)
    {
        $data = $request->validate([
            'material' => 'required|in:guinense,hibrido',
            'estado' => 'required|in:desarrollo,produccion',
            'anio_siembra' => 'required|date',
            'area' => 'required|integer',
            'orden_plantis_numero' => 'required|integer',
            'estado_oren_plantis' => 'required|in:desarrollo,produccion',
        ]);

        $area->update($data);

        return redirect()->route('areas.create', ['visita_id' => $area->visita_id])
            ->with('success', 'Área actualizada correctamente.');
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

        foreach ($submissions as $submission) {
            try {
                if ($submission['formName'] === 'area') {
                    $validated = validator($submission['formData'], [
                        'visita_id' => 'required|exists:visitas,id',
                        'material' => 'required|in:guinense,hibrido',
                        'estado' => 'required',
                        'anio_siembra' => 'required|date',
                        'area' => 'required|numeric',
                        'orden_plantis_numero' => 'required|numeric',
                        'estado_oren_plantis' => 'required'
                    ])->validate();

                    \App\Models\Area::updateOrCreate(
                        ['visita_id' => $validated['visita_id']],
                        $validated
                    );

                    $results[] = ['id' => $submission['formName'], 'success' => true];
                }
            } catch (\Exception $e) {
                $results[] = ['id' => $submission['formName'], 'success' => false, 'message' => $e->getMessage()];
            }
        }

        return response()->json(['results' => $results]);
    }

    // controllador offline
     

    public function syncOffline(Request $request)
    {
        // Obtiene todos los datos JSON de la petición.
        // Se espera un único objeto JSON por cada llamada de sincronización.
        $data = $request->json()->all();

        // Registra los datos recibidos para depuración (opcional, puedes eliminarlo en producción)
        Log::info('Datos recibidos para sincronizar Área:', $data);

        try {
            // Validar los datos si es necesario (ejemplo básico, puedes expandir las reglas)
            // Esto es importante para asegurar que los datos son válidos antes de guardarlos.
            // Si la validación falla, se lanzará una excepción.
            $request->validate([
                'visita_id' => 'required|integer',
                'material' => 'required|string',
                'estado' => 'required|string',
                'anio_siembra' => 'required|date',
                'area' => 'required|numeric', // 'area' como campo numérico para la superficie
                'orden_plantis_numero' => 'required|integer',
                'estado_oren_plantis' => 'required|string',
                // Si usas un ID de IndexedDB para la unicidad, añádelo aquí
                // 'indexeddb_id' => 'nullable|string',
            ]);

            // Utiliza updateOrCreate para insertar o actualizar el registro.
            // La clave para buscar el registro existente es crucial.
            // Aquí se asume que la combinación de 'visita_id' y 'orden_plantis_numero'
            // es única para identificar un registro de Área.
            // Si 'orden_plantis_numero' no es único para una visita, considera añadir
            // un campo 'id_offline' generado en el frontend (IndexedDB ID) a tu modelo
            // y usarlo como clave de búsqueda: ['id_offline' => $data['id_offline']]
            Area::updateOrCreate(
                [
                    'visita_id' => $data['visita_id'],
                    'orden_plantis_numero' => $data['orden_plantis_numero']
                ],
                $data
            );

            Log::info('Registro de Área sincronizado con éxito.', ['visita_id' => $data['visita_id']]);
            return response()->json(['message' => 'Área sincronizada con éxito.']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Captura errores de validación y devuelve una respuesta 422
            Log::error("Error de validación al sincronizar Área: " . $e->getMessage(), ['errors' => $e->errors(), 'data' => $data]);
            return response()->json(['message' => 'Error de validación.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Captura cualquier otro error inesperado y devuelve una respuesta 500
            Log::error("Error inesperado al sincronizar Área: " . $e->getMessage(), ['data' => $data, 'trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Error interno del servidor al sincronizar Área.', 'error' => $e->getMessage()], 500);
        }
    }

}
