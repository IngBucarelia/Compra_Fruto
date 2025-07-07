<?php

namespace App\Http\Controllers;

use App\Models\Planificacion;
use App\Models\Visita;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\VisitaExport;
use App\Models\Area;
use Maatwebsite\Excel\Facades\Excel;



class VisitaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
            $data = $request->validate([
                'fecha' => 'required|date',
                'tecnico_campo' => 'required|exists:users,id',
                'proveedor_id' => 'required|exists:proveedores,id',
                'plantacion_id' => 'required|exists:plantaciones,id',
                'ubicacion' => 'required|string',
                'tipo_visita' => 'required',
                'recibio_visita' => 'required|string',
            ]);

            $visita = Visita::create([
                ...$data,
                'estado' => 'pendiente'
            ]);

            if ($request->input('es_planificada') == 1) {
                $planificacion = Planificacion::create([
                    'fecha' => $data['fecha'],
                    'tecnico_campo' => $data['tecnico_campo'],
                    'proveedor_id' => $data['proveedor_id'],
                    'plantacion_id' => $data['plantacion_id'],
                    'tipo_visita' => $data['tipo_visita'],
                    'estado' => 'pendiente',
                    'visita_id' => $visita->id
                ]);

                $visita->update(['planificacion_id' => $planificacion->id]);
            }



            return redirect()->route('visitas.index')->with('success', 'Visita y planificación creadas y vinculadas.');
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
            $visita = \App\Models\Visita::with(['plantacion'])->findOrFail($id);
            $proveedores = \App\Models\Proveedor::all();
            $plantaciones = \App\Models\Plantacion::where('id_proveedor', $visita->proveedor_id)->get();
            $tecnicos = \App\Models\User::where('rol', 2)->get();

            return view('visitas.edit', compact('visita', 'proveedores', 'plantaciones', 'tecnicos'));
        }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
        {
            $request->validate([
                'fecha' => 'required|date',
                'ubicacion' => 'required|string',
                'tecnico_campo' => 'required|exists:users,id',
                'tipo_visita' => 'required|string',
                'proveedor_id' => 'required|exists:proveedores,id',
                'recibio_visita' => 'required|string',
            ]);

            $visita = \App\Models\Visita::findOrFail($id);
            $visita->update($request->all());

            return redirect()->route('visitas.index')->with('success', 'Visita actualizada correctamente.');
        }

    public function destroy($id)
        {
            $visita = \App\Models\Visita::findOrFail($id);
            $visita->delete();

            return redirect()->route('visitas.index')->with('success', 'Visita eliminada.');
        }

   public function detalle($id)
        {
            $visita = \App\Models\Visita::with([
                'area',
                'fertilizaciones.detalles',
                'polinizaciones',
                'sanidad',
                'suelo',
                'laboresCultivo',          
                'evaluacionCosechaCampo',
                 'cierreVisita' 
            ])->findOrFail($id);

            return view('visitas.detalle', compact('visita'));
        }


        
        public function exportarPDF($id)
        {
            $visita = Visita::with([
                'proveedor', 'plantacion', 'area',
                'fertilizaciones.fertilizantes',
                'polinizaciones', 'sanidad',
                'suelo', 'laboresCultivo',
                'evaluacionCosechaCampo', 'cierreVisita'
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



}
