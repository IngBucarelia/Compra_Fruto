<?php

namespace App\Http\Controllers;

use App\Models\PlanificacionSocial;
use App\Models\Proveedor;
use App\Models\Plantacion;
use App\Models\User; 
use App\Models\VisitaSocial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlanificacionSocialController extends Controller
{
    public function index()
    {
        $planificaciones = PlanificacionSocial::with(['tecnico', 'proveedor', 'plantacion'])
            ->orderBy('fecha')
            ->paginate(10);
        
        return view('planificaciones_social.index', compact('planificaciones'));
    }

    public function create()
    {
        $proveedores = Proveedor::all();
        $tecnicos = User::where('rol', 2)->get(); // Ajusta según tu lógica de roles
        return view('planificaciones_social.create', compact('proveedores', 'tecnicos'));
    }

 public function store(Request $request)
{
    try {
        $data = $request->validate([
            'fecha' => 'required|date',
            'tecnico_campo' => 'required|exists:users,id',
            'proveedor_id' => 'required|exists:proveedores,id',
            'plantacion_id' => 'required|exists:plantaciones,id',
            'tipo_visita' => 'required',
        ]);

        DB::beginTransaction();

        $planificacion = PlanificacionSocial::create([
            ...$data,
            'estado' => 'pendiente'
        ]);

        $visita = VisitaSocial::create([
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

        $planificacion->update(['visita_social_id' => $visita->id]);

        DB::commit();

        // ✅ CAMBIO: Redirigir al formulario de creación con mensaje de éxito
        return redirect()->route('planificaciones_social.create')
            ->with('success', 'Planificación social y visita creadas y vinculadas correctamente.');

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        return redirect()->back()
            ->withErrors($e->validator)
            ->withInput()
            ->with('error', 'Error de validación: Por favor verifica los datos ingresados.');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()
            ->withInput()
            ->with('error', 'Error al crear la planificación: ' . $e->getMessage());
    }
}
    public function show(\App\Models\PlanificacionSocial $planificacionSocial)
{
    return view('planificaciones_social.show', compact('planificacionSocial'));
}


    // PlanificacionSocialController.php

public function edit($id)
{
    try {
        $planificacion = PlanificacionSocial::with(['proveedor', 'plantacion', 'tecnico'])->findOrFail($id);
        
        $proveedores = Proveedor::all();
        
        // CORRECCIÓN: Usar el mismo criterio que en create (rol = 2)
        $tecnicos = User::where('rol', 2)->get();
        
        return view('planificaciones_social.edit', compact('planificacion', 'proveedores', 'tecnicos'));
        
    } catch (\Exception $e) {
        return redirect()->route('planificaciones_social.index')
            ->with('error', 'No se pudo cargar la planificación para editar: ' . $e->getMessage());
    }
}

public function update(Request $request, $id)
{
    try {
        $planificacion = PlanificacionSocial::findOrFail($id);

        $data = $request->validate([
            'fecha' => 'required|date',
            'tecnico_campo' => 'required|exists:users,id',
            'proveedor_id' => 'required|exists:proveedores,id',
            'plantacion_id' => 'required|exists:plantaciones,id',
            'tipo_visita' => 'required|string',
        ]);

        DB::beginTransaction();

        // Actualizar la planificación
        $planificacion->update($data);

        // Si existe una visita asociada, actualizarla también
        if ($planificacion->visita_social_id) {
            $visita = VisitaSocial::find($planificacion->visita_social_id);
            if ($visita) {
                $ubicacion = $planificacion->plantacion->vereda . ', ' . $planificacion->plantacion->municipio;
                
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

        return redirect()->route('planificaciones_social.index')
            ->with('success', 'Planificación social actualizada exitosamente.');

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        return redirect()->back()
            ->withErrors($e->validator)
            ->withInput()
            ->with('error', 'Error de validación: Por favor verifica los datos ingresados.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al actualizar planificación social: ' . $e->getMessage());
        
        return redirect()->back()
            ->withInput()
            ->with('error', 'Error al actualizar la planificación: ' . $e->getMessage());
    }
}
    public function destroy(PlanificacionSocial $planificacionSocial)
    {
        $planificacionSocial->delete();
        return redirect()->route('planificaciones_social.index')
            ->with('success', 'Planificación social eliminada');
    }

    public function calendario()
    {
        return view('planificaciones_social.calendario');
    }

    public function apiPlanificacionesSociales()
    {
        return PlanificacionSocial::all()->map(function ($p) {
            return [
                'id' => $p->id,
                'title' => $p->tipo_visita . ' (Social)',
                'start' => $p->fecha,
                'color' => match($p->estado) {
                    'pendiente' => '#0dcaf0',     // Azul claro
                    'realizada' => '#20c997',     // Verde azulado
                    'cancelada' => '#ffc107',     // Amarillo
                    default => '#6c757d'          // Gris
                }
            ];
        });
    }



    public function eventos(Request $request)
{
    try {
        $query = \App\Models\PlanificacionSocial::with('proveedor');

        // Filtrar por rango de fechas si vienen de FullCalendar
        if ($request->has('start') && $request->has('end')) {
            $query->whereBetween('fecha', [$request->start, $request->end]);
        }

        $planificaciones = $query->get();

        $eventos = $planificaciones->map(function ($plan) {
            return [
                'id' => $plan->id,
                'title' => $plan->tipo_visita ?? 'Sin título',
                'start' => $plan->fecha,
                'estado' => $plan->estado ?? 'pendiente',
                'proveedor' => $plan->proveedor->proveedor_nombre ?? 'N/A',
                'url' => route('planificaciones_social.show', $plan->id),
            ];
        });

        return response()->json($eventos);

    } catch (\Exception $e) {
        // Registrar en logs y devolver error claro
        Log::error('Error cargando eventos: '.$e->getMessage());
        return response()->json(['error' => 'Error cargando eventos'], 500);
    }
}

}