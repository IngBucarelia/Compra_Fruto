<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvaluacionIbt;
use App\Models\Visita;
use App\Models\Proveedor;
use App\Models\Plantacion;
use App\Models\ComponenteIbt;
use App\Models\SubcomponenteIbt;
use App\Models\RespuestaIbt;
use App\Models\ProgresoComponenteIbt;
use App\Models\User;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class EvaluacionIbtController extends Controller
{
    // 🔹 PASO 1: Crear evaluación básica (como crear una visita)
    public function create()
    {
        $proveedores = Proveedor::orderBy('proveedor_nombre')->get();
        $plantaciones = Plantacion::orderBy('nombre')->get();
        $visitas = Visita::whereNotIn('estado', ['eliminado'])
            ->orderBy('fecha', 'desc')
            ->get();
        $tecnicos = User::whereIn('rol', [2,3])->orderBy('name')->get();
        
        return view('evaluaciones_ibt.create', compact(
            'proveedores', 'plantaciones', 'visitas', 'tecnicos'
        ));
    }
    
    // 🔹 PASO 1: Guardar evaluación básica
    public function store(Request $request)
    {
        $request->validate([
            'proveedor_id' => 'required|exists:proveedores,id',
            'plantacion_id' => 'required|exists:plantaciones,id',
            'fecha_inicio' => 'required|date',
            'tecnico_id' => 'required|exists:users,id',
        ]);
        
        // Crear evaluación en estado "borrador"
        $evaluacion = EvaluacionIbt::create([
            'visita_id' => $request->visita_id,
            'proveedor_id' => $request->proveedor_id,
            'plantacion_id' => $request->plantacion_id,
            'fecha_evaluacion' => $request->fecha_inicio,
            'fecha_inicio' => $request->fecha_inicio,
            'tecnico_id' => $request->tecnico_id,
            'puntaje_total' => 0,
            'calificacion' => 'Sin calificar',
            'observaciones' => $request->observaciones_generales,
            'estado' => 'borrador',
            'porcentaje_completado' => 0,
        ]);
        
        // Crear registros de progreso para cada componente
        $componentes = ComponenteIbt::all();
        foreach ($componentes as $componente) {
            ProgresoComponenteIbt::create([
                'evaluacion_id' => $evaluacion->id,
                'componente_id' => $componente->id,
                'puntaje_maximo' => $componente->puntaje_maximo,
                'completado' => false,
            ]);
        }
        
        return redirect()->route('evaluaciones-ibt.index', [$evaluacion->id, 1])
            ->with('success', 'Evaluación IBT creada. Ahora puede completar los componentes.');
    }
    
    // 🔹 PASO 2: Mostrar componente específico para evaluación
    public function showComponente($evaluacionId, $componenteOrden)
    {
        $evaluacion = EvaluacionIbt::with(['proveedor', 'plantacion', 'tecnico'])
            ->findOrFail($evaluacionId);
        
        // Obtener componente por orden
        $componente = ComponenteIbt::where('orden', $componenteOrden)
            ->with(['subcomponentes' => function($q) {
                $q->orderBy('orden');
            }])
            ->firstOrFail();
        
        // Obtener progreso del componente
        $progreso = ProgresoComponenteIbt::where('evaluacion_id', $evaluacionId)
            ->where('componente_id', $componente->id)
            ->first();
        
        // Obtener respuestas existentes para este componente
        $respuestasExistentes = [];
        if ($progreso && $progreso->completado) {
            $respuestas = RespuestaIbt::where('evaluacion_id', $evaluacionId)
                ->whereHas('subcomponente', function($q) use ($componente) {
                    $q->where('componente_id', $componente->id);
                })
                ->get();
            
            foreach ($respuestas as $respuesta) {
                $respuestasExistentes[$respuesta->subcomponente_id] = $respuesta->calificacion_actual;
            }
        }
        
        // Obtener todos los componentes para la navegación
        $componentes = ComponenteIbt::orderBy('orden')->get();
        
        return view('evaluaciones_ibt.componente', compact(
            'evaluacion', 'componente', 'progreso', 'respuestasExistentes', 'componentes'
        ));
    }
    
    // 🔹 PASO 3: Guardar componente específico
    public function storeComponente(Request $request, $evaluacionId, $componenteId)
    {
        $evaluacion = EvaluacionIbt::findOrFail($evaluacionId);
        $componente = ComponenteIbt::findOrFail($componenteId);
        
        // Validar que haya respuestas
        if (!$request->has('respuestas') || empty($request->respuestas)) {
            return back()->withErrors(['error' => 'Debe completar al menos un criterio del componente.']);
        }
        
        // Calcular puntaje del componente
        $puntajeComponente = 0;
        
        // Eliminar respuestas anteriores de este componente
        $subcomponentesIds = $componente->subcomponentes->pluck('id');
        RespuestaIbt::where('evaluacion_id', $evaluacionId)
            ->whereIn('subcomponente_id', $subcomponentesIds)
            ->delete();
        
        // Crear nuevas respuestas
        foreach ($request->respuestas as $subcomponenteId => $valor) {
            $subcomponente = SubcomponenteIbt::find($subcomponenteId);
            if ($subcomponente && $subcomponente->componente_id == $componenteId) {
                $calificacion = min(max(floatval($valor), 0), $subcomponente->puntaje_maximo);
                $puntajeComponente += $calificacion;
                
                RespuestaIbt::create([
                    'evaluacion_id' => $evaluacionId,
                    'subcomponente_id' => $subcomponenteId,
                    'calificacion_actual' => $calificacion,
                    'observaciones' => $request->observaciones[$subcomponenteId] ?? null,
                ]);
            }
        }
        
        // Actualizar progreso del componente
        $progreso = ProgresoComponenteIbt::where('evaluacion_id', $evaluacionId)
            ->where('componente_id', $componenteId)
            ->first();
        
        if ($progreso) {
            $progreso->update([
                'puntaje_obtenido' => $puntajeComponente,
                'completado' => true,
                'fecha_completado' => Carbon::now(),
                'observaciones' => $request->observaciones_componente,
            ]);
        }
        
        // Calcular puntaje total y porcentaje completado
        $this->actualizarEvaluacionCompleta($evaluacionId);
        
        // Determinar siguiente componente o finalizar
        $siguienteComponente = ComponenteIbt::where('orden', '>', $componente->orden)
            ->orderBy('orden')
            ->first();
        
        if ($siguienteComponente) {
            return redirect()->route('evaluaciones-ibt.componente', [$evaluacionId, $siguienteComponente->orden])
                ->with('success', "Componente '{$componente->nombre}' guardado. Continúe con el siguiente.");
        } else {
            // Si es el último componente, finalizar evaluación
            $evaluacion->update([
                'estado' => 'completada',
                'fecha_fin' => Carbon::now(),
            ]);
            
            return redirect()->route('evaluaciones-ibt.show', $evaluacionId)
                ->with('success', '¡Felicidades! Ha completado toda la evaluación IBT.');
        }
    }
    
    // 🔹 Método auxiliar para actualizar evaluación completa
    private function actualizarEvaluacionCompleta($evaluacionId)
    {
        $evaluacion = EvaluacionIbt::findOrFail($evaluacionId);
        
        // Calcular puntaje total
        $progresos = ProgresoComponenteIbt::where('evaluacion_id', $evaluacionId)->get();
        $puntajeTotal = $progresos->sum('puntaje_obtenido');
        
        // Calcular porcentaje completado
        $componentesCompletados = $progresos->where('completado', true)->count();
        $totalComponentes = $progresos->count();
        $porcentajeCompletado = $totalComponentes > 0 ? ($componentesCompletados / $totalComponentes) * 100 : 0;
        
        // Determinar calificación
        $calificacion = $this->determinarCalificacion($puntajeTotal);
        
        // Determinar estado
        $estado = $porcentajeCompletado == 100 ? 'completada' : 
                 ($porcentajeCompletado > 0 ? 'en_progreso' : 'borrador');
        
        // Actualizar evaluación
        $evaluacion->update([
            'puntaje_total' => $puntajeTotal,
            'calificacion' => $calificacion,
            'porcentaje_completado' => $porcentajeCompletado,
            'estado' => $estado,
            'fecha_fin' => $porcentajeCompletado == 100 ? Carbon::now() : null,
        ]);
        
        return $evaluacion;
    }
    
    // 🔹 Ver evaluación completa
    public function show($id)
{
    $evaluacion = EvaluacionIbt::with([
        'proveedor',
        'plantacion',
        'usuario'
    ])->findOrFail($id);

    return view('evaluaciones_ibt.show', compact('evaluacion'));
}

    
    // 🔹 Dashboard de evaluación (progreso)
    public function dashboardEvaluacion($id)
    {
        $evaluacion = EvaluacionIbt::with(['proveedor', 'plantacion', 'tecnico'])->findOrFail($id);
        
        $progresos = ProgresoComponenteIbt::where('evaluacion_id', $id)
            ->with('componente')
            ->orderBy('componente_id')
            ->get();
        
        $componentes = ComponenteIbt::orderBy('orden')->get();
        
        return view('evaluaciones_ibt.dashboard_evaluacion', compact(
            'evaluacion', 'progresos', 'componentes'
        ));
    }
    
    // 🔹 Método auxiliar para determinar calificación
    private function determinarCalificacion($puntaje)
    {
        if ($puntaje >= 90) return 'Excelente';
        if ($puntaje >= 80) return 'Muy Bueno';
        if ($puntaje >= 70) return 'Bueno';
        if ($puntaje >= 60) return 'Regular';
        if ($puntaje > 0) return 'Necesita Mejora';
        return 'Sin calificar';
    }
    
    // 🔹 Exportar PDF
    public function exportarPDF($id)
    {
        $evaluacion = EvaluacionIbt::with([
            'proveedor', 'plantacion', 'tecnico', 'visita',
            'respuestas.subcomponente.componente'
        ])->findOrFail($id);
        
        $componentesConRespuestas = [];
        $progresosComponentes = ProgresoComponenteIbt::where('evaluacion_id', $id)
            ->with('componente')
            ->get();
        
        foreach ($progresosComponentes as $progreso) {
            $componenteId = $progreso->componente_id;
            $componentesConRespuestas[$componenteId] = [
                'componente' => $progreso->componente,
                'progreso' => $progreso,
                'respuestas' => [],
                'puntaje_obtenido' => $progreso->puntaje_obtenido,
                'puntaje_maximo' => $progreso->puntaje_maximo,
            ];
        }
        
        foreach ($evaluacion->respuestas as $respuesta) {
            $componenteId = $respuesta->subcomponente->componente->id;
            if (isset($componentesConRespuestas[$componenteId])) {
                $componentesConRespuestas[$componenteId]['respuestas'][] = $respuesta;
            }
        }
        
        uasort($componentesConRespuestas, function($a, $b) {
            return $a['componente']->orden <=> $b['componente']->orden;
        });
        
        $pdf = Pdf::loadView('evaluaciones_ibt.pdf', compact('evaluacion', 'componentesConRespuestas'));
        
        $filename = "IBT_{$evaluacion->proveedor->proveedor_nombre}_{$evaluacion->fecha_evaluacion}.pdf";
        return $pdf->download($filename);
    }
    
    public function index(Request $request)
    {
        // 🔹 Listados para filtros (ESTO FALTABA)
        $proveedores = Proveedor::orderBy('proveedor_nombre')->get();
        $plantaciones = Plantacion::orderBy('nombre')->get();

        // 🔹 Query base
        $query = EvaluacionIbt::with([
            'proveedor',
            'plantacion',
            'tecnico',
            'visita'
        ]);

        // 🔹 Filtros existentes (no se toca tu lógica)
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_evaluacion', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_evaluacion', '<=', $request->fecha_hasta);
        }

        if ($request->filled('proveedor')) {
            $query->where('proveedor_id', $request->proveedor);
        }

        if ($request->filled('plantacion')) {
            $query->where('plantacion_id', $request->plantacion);
        }

        $evaluaciones = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // ✅ ENVIAMOS TODO LO QUE LA VISTA USA
        return view('evaluaciones_ibt.index', compact(
            'evaluaciones',
            'proveedores',
            'plantaciones'
        ));
    }


    
    public function edit($id)
    {
        // ... (tu código existente)
    }
    
    public function update(Request $request, $id)
    {
        // ... (tu código existente)
    }
    
    public function destroy($id)
    {
        // ... (tu código existente)
    }

    public function plantacionesPorProveedor($proveedorId)
    {
        $plantaciones = Plantacion::where('id_proveedor', $proveedorId)
            ->orderBy('nombre')
            ->get(['id', 'nombre']);

        return response()->json($plantaciones);
    }




}