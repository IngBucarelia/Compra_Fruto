<?php

namespace App\Http\Controllers;

use App\Models\Planificacion;
use App\Models\Visita;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\VisitaExport;
use App\Models\Area;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log; 
use App\Imports\VisitasImport;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Imports\VisitasMultiSheetImport;
use Illuminate\Support\Facades\DB;

class VisitaController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function homeVisitas()
    {
        return view('visitas.home', [
            'user' => Auth::user()
        ]);
    }
    public function index(Request $request)
        {
            $buscar = $request->input('buscar');

            $visitas = \App\Models\Visita::with(['proveedor', 'tecnico'])
                ->whereHas('proveedor', fn($q) => $q->where('proveedor_nombre', 'like', "%$buscar%"))
                ->orWhereHas('tecnico', fn($q) => $q->where('name', 'like', "%$buscar%"))
                ->orWhere('tipo_visita', 'like', "%$buscar%")
                ->orWhere('ubicacion', 'like', "%$buscar%")
                ->latest()
                ->paginate(10);

            return view('visitas.index', compact('visitas', 'buscar'));
        }


    /**
     * Show the form for creating a new resource.
     */
   public function create()
        {
            $tecnicos = \App\Models\User::where('rol', 2)->get();
            $proveedores = \App\Models\Proveedor::all();
            return view('visitas.create', compact('tecnicos', 'proveedores'));
        }
    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'fecha' => 'required|date',
                'tecnico_campo' => 'required|exists:users,id',
                'proveedor_id' => 'required|exists:proveedores,id',
                'plantacion_id' => 'required|exists:plantaciones,id',
                'tipo_visita' => 'required|string',
            ]);

            DB::beginTransaction();

            // Crear la planificación
            $planificacion = Planificacion::create([
                ...$data,
                'estado' => 'pendiente'
            ]);

            // Obtener datos de la plantación para la ubicación
            $plantacion = \App\Models\Plantacion::find($data['plantacion_id']);
            $ubicacion = $plantacion->vereda . ', ' . $plantacion->municipio;

            // Crear la visita asociada
            $visita = Visita::create([
                'fecha' => $data['fecha'],
                'tecnico_campo' => $data['tecnico_campo'],
                'proveedor_id' => $data['proveedor_id'],
                'plantacion_id' => $data['plantacion_id'],
                'tipo_visita' => $data['tipo_visita'],
                'ubicacion' => $ubicacion,
                'recibio_visita' => 'SIN REGISTRAR',
                'estado' => 'pendiente',
                'planificacion_id' => $planificacion->id
            ]);

            // Actualizar la planificación con el ID de la visita
            $planificacion->update(['visita_id' => $visita->id]);

            DB::commit();

            return redirect()->route('planificaciones.create')
                ->with('success', 'Planificación agronómica y visita creadas y vinculadas correctamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Error de validación: Por favor verifica los datos ingresados.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear planificación agronómica: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear la planificación: ' . $e->getMessage());
        }
    }




    /**
     * Display the specified resource.
     */
    public function show($id)
        {
            $visita = \App\Models\Visita::with(['proveedor', 'tecnico', 'plantacion','polinizaciones', 'sanidad','evaluacionCosechaCampo'])->findOrFail($id);
            return view('visitas.show', compact('visita'));
        }


    /**
     * Show the form for editing the specified resource.
     */
   public function edit($id)
    {
        try {
            $visita = \App\Models\Visita::with(['proveedor', 'plantacion', 'tecnico'])->findOrFail($id);
            $proveedores = \App\Models\Proveedor::all();
            $tecnicos = \App\Models\User::where('rol', 2)->get();

            return view('visitas.edit', compact('visita', 'proveedores', 'tecnicos'));
            
        } catch (\Exception $e) {
            return redirect()->route('visitas.index')
                ->with('error', 'No se pudo cargar la visita para editar: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'fecha' => 'required|date',
                'tecnico_campo' => 'required|exists:users,id',
                'proveedor_id' => 'required|exists:proveedores,id',
                'plantacion_id' => 'required|exists:plantaciones,id',
                'ubicacion' => 'required|string|max:255',
                'tipo_visita' => 'required|string',
                'recibio_visita' => 'required|string|max:255',
            ]);

            DB::beginTransaction();

            $visita = \App\Models\Visita::findOrFail($id);
            $visita->update($data);

            DB::commit();

            return redirect()->route('visitas.index')
                ->with('success', 'Visita agronómica actualizada exitosamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Error de validación: Por favor verifica los datos ingresados.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar visita agronómica: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar la visita: ' . $e->getMessage());
        }
    }



    public function destroy($id)
        {
            $visita = \App\Models\Visita::findOrFail($id);
            $visita->delete();

            return redirect()->route('visitas.index')->with('success', 'Visita eliminada.');
        }

        

   public function detalle($id)
    {
        $visita = Visita::with([
            'areas', // ✅ CAMBIO: Cargar la relación 'areas' (plural)
            'fertilizaciones.detalles',
            'polinizaciones',
            'sanidad',
            'suelo',
            'laboresCultivo', // ✅ CAMBIO: Cargar la relación 'laboresCultivo' (plural)
            'evaluacionCosechaCampo', // ✅ CAMBIO: Cargar la relación 'evaluacionCosechaCampo' (plural)
            'cierreVisita',
            'tecnico' // Asegúrate de cargar la relación con el técnico si la usas
        ])->findOrFail($id);

        // Opcional: Para depurar los datos que recibes
        // Log::info('Datos de visita para detalle:', $visita->toArray());

        return view('visitas.detalle', compact('visita'));
    }


        
      public function exportarPDF($id)
    {
        $visita = Visita::with([
            'proveedor',
            'plantacion',
            'areas', // ✅ Asegúrate de que sea 'areas' (plural)
            'fertilizaciones.detalles',
            'polinizaciones',
            'sanidad',
            'suelo',
            'laboresCultivo',
            'evaluacionCosechaCampo',
            'cierreVisita',
            'tecnico'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('visitas.exportar_pdf', compact('visita'));
        return $pdf->download("detalle_visita_{$id}.pdf");
    }


        public function exportarExcel($id)
        {
            return Excel::download(new VisitaExport($id), "detalle_visita_{$id}.xlsx");
        }

        // para los formularios offline 

        public function syncOfflineData(Request $request)
{
    $submissions = $request->input('submissions');
    $results = [];
    
    foreach ($submissions as $submission) {
        try {
            switch ($submission['formName']) {
                case 'area':
                    $result = $this->syncArea($submission['formData']);
                    break;
                case 'fertilizacion':
                    $result = $this->syncFertilizacion($submission['formData']);
                    break;
                // Agregar más casos según sea necesario
                default:
                    throw new \Exception("Tipo de formulario no soportado");
            }
            
            $results[] = [
                'id' => $submission['id'],
                'success' => true,
                'message' => 'Sincronizado correctamente'
            ];
        } catch (\Exception $e) {
            $results[] = [
                'id' => $submission['id'],
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    return response()->json(['results' => $results]);
    }

    private function syncArea($data)
    {
        $validated = validator($data, [
            'visita_id' => 'required|exists:visitas,id',
            'material' => 'required|in:guinense,hibrido',
            // Agregar todas las validaciones
        ])->validate();
        
        $area = Area::create($validated);
        
        // Actualizar estado de la visita
        $visita = Visita::find($validated['visita_id']);
        if ($visita->estado === 'pendiente') {
            $visita->estado = 'en_ejecucion';
            $visita->save();
        }
        
        return $area;
    }

    private function syncFertilizacion($data)
    {
        // Implementar lógica similar para fertilización
    }

        public function updateStatus(Request $request, Visita $visita)
    {
        try {
            // Cambiar el estado siempre a "finalizado"
            $visita->estado = 'finalizado';
            $visita->save();

            Log::info("Estado de la visita {$visita->id} actualizado a: finalizado");

            return response()->json([
                'message' => 'Estado de la visita actualizado exitosamente.',
                'visita' => $visita,
            ], 200);

        } catch (\Exception $e) {
            Log::error("Error al actualizar el estado de la visita {$visita->id}: " . $e->getMessage());
            return response()->json([
                'message' => 'Error al actualizar el estado de la visita.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    
    
   public function importForm()
    {
        return view('visitas.import');
    }

    /**
     * Procesa la importación del archivo Excel.
     * @param Request $request
     */
public function import(Request $request)
{
    $request->validate([
        'excel_file' => 'mimes:xlsx,xls,csv|max:2048',
    ]);

    try {
        if ($request->hasFile('excel_file')) {
            $file = $request->file('excel_file');

            // 1️⃣ Importamos la hoja Visitas y se crean los registros
            Excel::import(new VisitasImport, $file, null, \Maatwebsite\Excel\Excel::XLSX);

            // 2️⃣ Obtenemos la última visita creada (o la que necesites)
            $visita = Visita::latest()->first();

            if ($visita) {
                // 3️⃣ Importamos las demás hojas pasando esa visita
                Excel::import(new VisitasMultiSheetImport($visita), $file, null, \Maatwebsite\Excel\Excel::XLSX);

                return back()->with('success', 'Archivo importado con éxito!');
            } else {
                return back()->with('error', 'No se creó ninguna visita desde la hoja principal.');
            }
        }

        return back()->with('status', 'No se ha subido ningún archivo.');
    } catch (Exception $e) {
        Log::error('Error en importación de visitas: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        return back()->with('error', 'Hubo un problema al importar el archivo: ' . $e->getMessage());
    }
}

public function iniciarAgronomica(Visita $visita)
{
    try {
        // Actualizar estado de la visita
        $visita->update([
            'estado' => 'en_ejecucion',
            'fecha_inicio' => now() // Si tienes este campo
        ]);

        // Redirigir a la primera sección (Área)
        return redirect()->route('redireccion_seccion_agronomica', [
            'visita' => $visita->id,
            'seccion' => 'area'
        ])->with('success', '¡Visita agronómica iniciada correctamente!');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error al iniciar la visita agronómica: ' . $e->getMessage());
    }
}

public function redireccionSeccionAgronomica(Visita $visita, Request $request)
{
    $seccion = $request->input('seccion');

    // Si la visita está pendiente y se intenta acceder a cualquier sección, iniciarla primero
    if ($visita->estado === 'pendiente' && $seccion !== 'inicio') {
        $visita->update(['estado' => 'en_ejecucion']);
    }

    switch ($seccion) {
        case 'area':
            return redirect()->route('areas.create', ['visita_id' => $visita->id]);
        case 'fertilizacion':
            return redirect()->route('fertilizaciones.create', ['visita_id' => $visita->id]);
        case 'polinizacion':
            return redirect()->route('polinizaciones.create', ['visita_id' => $visita->id]);
        case 'sanidad':
            return redirect()->route('sanidades.create', ['visita_id' => $visita->id]);
        case 'suelo':
            return redirect()->route('suelos.create', ['visita_id' => $visita->id]);
        case 'labores_cultivo':
            return redirect()->route('labores_cultivo.create', ['visita_id' => $visita->id]);
        case 'evaluacion_cosecha':
            return redirect()->route('evaluacion.create', ['visita_id' => $visita->id]);
        case 'inicio':
        default:
            return redirect()->route('visitas.show', $visita->id);
    }
}


}
