<?php

namespace App\Http\Controllers;

use App\Models\Planificacion;
use App\Models\Proveedor;
use App\Models\Plantacion;
use App\Models\User;
use App\Models\Visita;
use Illuminate\Http\Request;

class PlanificacionController extends Controller
{
    public function index()
            {
                $planificaciones = Planificacion::with(['tecnico', 'proveedor', 'plantacion'])->orderBy('fecha')->paginate(10);
                return view('planificaciones.index', compact('planificaciones'));
            }

    public function create()
            {
                $proveedores = Proveedor::all();
                $tecnicos = User::where('rol', 2)->get();
                return view('planificaciones.create', compact('proveedores', 'tecnicos'));
            }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fecha' => 'required|date',
            'tecnico_campo' => 'required|exists:users,id',
            'proveedor_id' => 'required|exists:proveedores,id',
            'plantacion_id' => 'required|exists:plantaciones,id',
            'tipo_visita' => 'required',
        ]);

        $planificacion = Planificacion::create([
            ...$data,
            'estado' => 'pendiente'
        ]);

        $visita = Visita::create([
            'fecha' => $data['fecha'],
            'tecnico_campo' => $data['tecnico_campo'],
            'proveedor_id' => $data['proveedor_id'],
            'plantacion_id' => $data['plantacion_id'],
            'tipo_visita' => $data['tipo_visita'],
            'ubicacion' => $planificacion->plantacion->vereda . ', ' . $planificacion->plantacion->municipio,
            'recibio_visita' => 'SIN REGISTRAR',
            'estado' => 'pendiente',
            'planificacion_id' => $planificacion->id
        ]);

        $planificacion->update(['visita_id' => $visita->id]);

        return redirect()->route('planificaciones.index')->with('success', 'Planificación y visita creadas y vinculadas.');
    }



    public function show($id)
    {
        $planificacion = Planificacion::with([
            'proveedor',
            'plantacion',
            'tecnico',
            'visita.area',
            'visita.fertilizaciones.fertilizantes'
        ])->findOrFail($id);

        return view('planificaciones.show', compact('planificacion'));
    }


    public function edit(Planificacion $planificacion)
            {
                $proveedores = Proveedor::all();
                $tecnicos = User::where('rol', 'tecnico_campo')->get();
                $plantaciones = Plantacion::where('id_proveedor', $planificacion->proveedor_id)->get();

                return view('planificaciones.edit', compact('planificacion', 'proveedores', 'tecnicos', 'plantaciones'));
            }

    public function update(Request $request, Planificacion $planificacion)
            {
                $request->validate([
                    'fecha' => 'required|date',
                    'tecnico_campo' => 'required|exists:users,id',
                    'proveedor_id' => 'required|exists:proveedores,id',
                    'plantacion_id' => 'required|exists:plantaciones,id',
                    'tipo_visita' => 'required|string',
                    'estado' => 'required|in:pendiente,realizada,cancelada',
                ]);

                $planificacion->update($request->all());
                return redirect()->route('planificaciones.index')->with('success', 'Planificación actualizada');
            }

    public function destroy(Planificacion $planificacion)
            {
                $planificacion->delete();
                return redirect()->route('planificaciones.index')->with('success', 'Planificación eliminada');
            }

    public function calendario()
            {
                return view('planificaciones.calendario');
            }

    public function apiPlanificaciones()
        {
            return Planificacion::all()->map(function ($p) {
                return [
                    'id' => $p->id,
                    'title' => $p->tipo_visita,
                    'start' => $p->fecha,
                    'color' => match($p->estado) {
                        'pendiente' => '#0d6efd',     // Azul Bootstrap
                        'realizada' => '#198754',     // Verde
                        'cancelada' => '#dc3545',     // Rojo
                        default => '#6c757d'          // Gris por defecto
                    }
                ];
            });
        }

}
