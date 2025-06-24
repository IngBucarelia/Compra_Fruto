<?php

namespace App\Http\Controllers;

use App\Models\Planificacion;
use App\Models\Visita;
use Illuminate\Http\Request;

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
            $visita = \App\Models\Visita::with(['proveedor', 'tecnico', 'plantacion','polinizaciones', 'sanidad'])->findOrFail($id);
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
                'fertilizaciones.detalles' // detalles = FertilizanteFertilizacion
            ])->findOrFail($id);

            return view('visitas.detalle', compact('visita'));
        }


}
