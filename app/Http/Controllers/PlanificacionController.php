<?php

namespace App\Http\Controllers;

use App\Models\Planificacion;
use App\Models\Proveedor;
use App\Models\Plantacion;
use App\Models\User;
use App\Models\Visita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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


   public function edit($id)
{
    try {
        $planificacion = Planificacion::with(['proveedor', 'plantacion', 'tecnico'])->findOrFail($id);
        
        $proveedores = Proveedor::all();
        $tecnicos = User::where('rol', 2)->get(); // Cambié 'tecnico_campo' por 2 según tu lógica anterior
        
        return view('planificaciones.edit', compact('planificacion', 'proveedores', 'tecnicos'));
        
    } catch (\Exception $e) {
        return redirect()->route('planificaciones.index')
            ->with('error', 'No se pudo cargar la planificación para editar: ' . $e->getMessage());
    }
}

public function update(Request $request, $id)
{
    try {
        $planificacion = Planificacion::findOrFail($id);

        $data = $request->validate([
            'fecha' => 'required|date',
            'tecnico_campo' => 'required|exists:users,id',
            'proveedor_id' => 'required|exists:proveedores,id',
            'plantacion_id' => 'required|exists:plantaciones,id',
            'tipo_visita' => 'required|string',
            'estado' => 'required|in:pendiente,realizada,cancelada',
        ]);

        DB::beginTransaction();

        // Actualizar la planificación
        $planificacion->update($data);

        // Si existe una visita asociada, actualizarla también
        if ($planificacion->visita_id) {
            $visita = Visita::find($planificacion->visita_id);
            if ($visita) {
                // Obtener la ubicación actualizada de la plantación
                $plantacionActualizada = Plantacion::find($data['plantacion_id']);
                $ubicacion = $plantacionActualizada->vereda . ', ' . $plantacionActualizada->municipio;
                
                $visita->update([
                    'fecha' => $data['fecha'],
                    'tecnico_campo' => $data['tecnico_campo'],
                    'proveedor_id' => $data['proveedor_id'],
                    'plantacion_id' => $data['plantacion_id'],
                    'tipo_visita' => $data['tipo_visita'],
                    'ubicacion' => $ubicacion
                ]);
            }
        }

        DB::commit();

        return redirect()->route('planificaciones.index')
            ->with('success', 'Planificación agronómica actualizada exitosamente.');

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        return redirect()->back()
            ->withErrors($e->validator)
            ->withInput()
            ->with('error', 'Error de validación: Por favor verifica los datos ingresados.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al actualizar planificación agronómica: ' . $e->getMessage());
        
        return redirect()->back()
            ->withInput()
            ->with('error', 'Error al actualizar la planificación: ' . $e->getMessage());
    }
}

   public function destroy($id)
{
    try {
        $planificacion = Planificacion::findOrFail($id);

        DB::beginTransaction();

        // Si existe una visita asociada, eliminarla también
        if ($planificacion->visita_id) {
            $visita = Visita::find($planificacion->visita_id);
            if ($visita) {
                $visita->delete();
            }
        }

        // Eliminar la planificación
        $planificacion->delete();

        DB::commit();

        return redirect()->route('planificaciones.index')
            ->with('success', 'Planificación agronómica eliminada exitosamente.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al eliminar planificación: ' . $e->getMessage());
        
        return redirect()->route('planificaciones.index')
            ->with('error', 'Error al eliminar la planificación: ' . $e->getMessage());
    }
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
