<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visita;
use App\Models\Area;
use App\Models\Fertilizacion;
use App\Models\Polinizacion;
use App\Models\Sanidad;
use App\Models\LaboresCultivo;
use App\Models\EvaluacionCosechaCampo;
use App\Models\Proveedor;
use App\Models\Plantacion;
use App\Models\User;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }
    // Vista principal
    public function visitasAgro(Request $request)
    {
        // Datos para selects
        $proveedores = Proveedor::orderBy('proveedor_nombre')->get();
        $plantaciones = Plantacion::orderBy('nombre')->get();
        $tecnicos = User::whereIn('rol', [2,3])->orderBy('name')->get();

        // Valores iniciales (puedes opcionalmente pasarlos)
        return view('visitas.dashboard_visitas_agro', compact('proveedores','plantaciones','tecnicos'));
    }

    // Endpoint JSON: datos agregados segun filtros (visitas por estado, por proveedor, por plantación)
    public function dataVisitasAgro(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $proveedor = $request->input('proveedor');
        $plantacion = $request->input('plantacion');
        $tecnico = $request->input('tecnico');

        $query = Visita::query();

        // excluir eliminadas si usas ese estado
        $query->whereNotIn('estado', ['eliminado']);

        if ($from) {
            $query->whereDate('fecha', '>=', Carbon::parse($from));
        }
        if ($to) {
            $query->whereDate('fecha', '<=', Carbon::parse($to));
        }
        if ($proveedor) {
            $query->where('proveedor_id', $proveedor);
        }
        if ($plantacion) {
            $query->where('plantacion_id', $plantacion);
        }
        if ($tecnico) {
            $query->where('tecnico_campo', $tecnico);
        }

        // Visitas por estado
        $visitasPorEstado = (clone $query)
            ->selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado');

        // Visitas por proveedor (top 10 para no sobrecargar)
        $visitasPorProveedor = (clone $query)
            ->selectRaw('proveedor_id, COUNT(*) as total')
            ->groupBy('proveedor_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->mapWithKeys(function($r){
                return [ optional($r->proveedor)->proveedor_nombre ?? 'Sin proveedor' => (int)$r->total ];
            });

        // Visitas por plantacion (top 10)
        $visitasPorPlantacion = (clone $query)
            ->selectRaw('plantacion_id, COUNT(*) as total')
            ->groupBy('plantacion_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->mapWithKeys(function($r){
                return [ optional($r->plantacion)->nombre ?? 'Sin plantación' => (int)$r->total ];
            });

        // Producción promedio por plantación (desde areas relacionadas)
        $produccionPorPlantacion = Area::query()
            ->when($from, fn($q) => $q->whereHas('visita', fn($q2) => $q2->whereDate('fecha','>=', $from)))
            ->when($to, fn($q) => $q->whereHas('visita', fn($q2) => $q2->whereDate('fecha','<=', $to)))
            ->selectRaw('visita_id, AVG(produccion_toneladas_por_mes) as promedio')
            ->groupBy('visita_id')
            ->get()
            ->groupBy(function($a){
                return optional($a->visita->plantacion)->nombre ?? 'Sin plantación';
            })
            ->map(fn($group) => round($group->avg('promedio'),2))
            ->take(10);

        return response()->json([
            'visitasPorEstado' => $visitasPorEstado,
            'visitasPorProveedor' => $visitasPorProveedor,
            'visitasPorPlantacion' => $visitasPorPlantacion,
            'produccionPorPlantacion' => $produccionPorPlantacion
        ]);
    }

    // Endpoint JSON: totales por módulos (fertilizaciones, polinizaciones, etc.) con filtros
    public function dataModulos(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $proveedor = $request->input('proveedor');
        $plantacion = $request->input('plantacion');
        $tecnico = $request->input('tecnico');

        // 🔹 Filtrar visitas activas
        $visitas = \App\Models\Visita::query()
            ->whereNotIn('estado', ['eliminado'])
            ->when($from, fn($q) => $q->whereDate('fecha', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('fecha', '<=', $to))
            ->when($proveedor, fn($q) => $q->where('proveedor_id', $proveedor))
            ->when($plantacion, fn($q) => $q->where('plantacion_id', $plantacion))
            ->when($tecnico, fn($q) => $q->where('tecnico_campo', $tecnico))
            ->with('plantacion')
            ->get(['id', 'plantacion_id']);

        if ($visitas->isEmpty()) {
            return response()->json([
                'fertilizaciones' => [],
                'polinizaciones' => [],
                'sanidad' => [],
                'labores_cultivo' => [],
                'evaluacion_cosecha' => [],
                'produccion_plantacion' => []
            ]);
        }

        $visitaIds = $visitas->pluck('id');
        $mapPlantaciones = $visitas->pluck('plantacion.nombre', 'id'); // [visita_id => nombre_plantacion]

        // 🔹 Función helper para agrupar por plantación
        $agruparPorPlantacion = function ($modelo, $visitaIds, $mapPlantaciones) {
            return $modelo::whereIn('visita_id', $visitaIds)
                ->get(['visita_id'])
                ->groupBy(fn($m) => $mapPlantaciones[$m->visita_id] ?? 'Sin plantación')
                ->map(fn($g) => $g->count())
                ->sortKeys();
        };

        // 🔹 Agrupamos cada módulo
        $fertilizaciones = $agruparPorPlantacion(\App\Models\Fertilizacion::class, $visitaIds, $mapPlantaciones);
        $polinizaciones  = $agruparPorPlantacion(\App\Models\Polinizacion::class, $visitaIds, $mapPlantaciones);
        $sanidad         = $agruparPorPlantacion(\App\Models\Sanidad::class, $visitaIds, $mapPlantaciones);
        $labores_cultivo = $agruparPorPlantacion(\App\Models\LaboresCultivo::class, $visitaIds, $mapPlantaciones);
        $evaluacion_cosecha = $agruparPorPlantacion(\App\Models\EvaluacionCosechaCampo::class, $visitaIds, $mapPlantaciones);

        // 🔹 Producción por plantación (de la tabla areas)
        $produccion_plantacion = \App\Models\Area::whereIn('visita_id', $visitaIds)
            ->selectRaw('visita_id, AVG(produccion_toneladas_por_mes) as promedio')
            ->groupBy('visita_id')
            ->get()
            ->groupBy(fn($a) => $mapPlantaciones[$a->visita_id] ?? 'Sin plantación')
            ->map(fn($group) => round($group->avg('promedio'), 2))
            ->sortKeys();

        return response()->json([
            'fertilizaciones' => $fertilizaciones,
            'polinizaciones' => $polinizaciones,
            'sanidad' => $sanidad,
            'labores_cultivo' => $labores_cultivo,
            'evaluacion_cosecha' => $evaluacion_cosecha,
            'produccion_plantacion' => $produccion_plantacion
        ]);
    }
}