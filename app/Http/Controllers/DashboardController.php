<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visita;
use App\Models\Area;
use App\Models\Fertilizacion;
use App\Models\Polinizacion;
use App\Models\Sanidad;
use App\Models\SanidadEnfermedad;
use App\Models\SanidadPlaga;
use App\Models\TrampaPalmarum;
use App\Models\LaboresCultivo;
use App\Models\EvaluacionCosechaCampo;
use App\Models\FertilizanteFertilizacion;
use App\Models\Proveedor;
use App\Models\Plantacion;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    // =========================
    // VISTA PRINCIPAL
    // =========================
    public function visitasAgro()
    {
        $proveedores  = Proveedor::orderBy('proveedor_nombre')->get();
        $plantaciones = Plantacion::orderBy('nombre')->get();
        $tecnicos     = User::whereIn('rol', [2,3])->orderBy('name')->get();

        return view(
            'visitas.dashboard_visitas_agro',
            compact('proveedores','plantaciones','tecnicos')
        );
    }

    // ============================================================
    // INDICADORES GENERALES (SEGÚN PDF PÁGINA 1)
    // ============================================================
    public function dataVisitasAgro(Request $request)
{
    $query = Visita::whereNotIn('estado',['eliminado']);
    $queryAnual = (clone $query);

    if ($request->from)       $query->whereDate('fecha','>=',$request->from);
    if ($request->to)         $query->whereDate('fecha','<=',$request->to);
    if ($request->proveedor)  $query->where('proveedor_id',$request->proveedor);
    if ($request->plantacion) $query->where('plantacion_id',$request->plantacion);
    if ($request->tecnico)    $query->where('tecnico_campo',$request->tecnico);

    // Para anual (desde enero hasta hoy)
    $anualDesde = Carbon::now()->startOfYear()->format('Y-m-d');
    $queryAnual->whereDate('fecha','>=',$anualDesde);
    if ($request->to) $queryAnual->whereDate('fecha','<=',$request->to);

    // 🔹 NUEVO: Visitas en el mes y acumulado año
    $mesActual = Carbon::now()->month;
    $anioActual = Carbon::now()->year;
    
    $visitasMes = (clone $query)
        ->whereMonth('fecha', $mesActual)
        ->whereYear('fecha', $anioActual)
        ->count();
        
    $visitasAnio = (clone $queryAnual)->count();
    
    // 🔹 NUEVO: Proveedores visitados en el mes (únicos)
    $proveedoresMes = (clone $query)
        ->whereMonth('fecha', $mesActual)
        ->whereYear('fecha', $anioActual)
        ->distinct('proveedor_id')
        ->count('proveedor_id');

    // 🔹 NUEVO: Obtener nombres de proveedores y plantaciones
    $visitasConNombres = (clone $query)
        ->with(['proveedor', 'plantacion'])
        ->get();

    $proveedoresUnicos = $visitasConNombres
        ->pluck('proveedor')
        ->filter()
        ->unique('id')
        ->mapWithKeys(fn($p) => [$p->id => $p->proveedor_nombre])
        ->toArray();

    $plantacionesUnicas = $visitasConNombres
        ->pluck('plantacion')
        ->filter()
        ->unique('id')
        ->mapWithKeys(fn($pl) => [$pl->id => $pl->nombre])
        ->toArray();

    // 🔹 NUEVO: Proveedores del mes con nombres
    $proveedoresMesNombres = (clone $query)
        ->whereMonth('fecha', $mesActual)
        ->whereYear('fecha', $anioActual)
        ->with('proveedor')
        ->get()
        ->pluck('proveedor.proveedor_nombre')
        ->filter()
        ->unique()
        ->values()
        ->toArray();

    return response()->json([
        // 🔹 NUEVO MÉTRICAS PÁGINA 1
        'resumenVisitas' => [
            'visitas_mes' => $visitasMes,
            'visitas_anio' => $visitasAnio,
            'proveedores_mes' => $proveedoresMes,
            'proveedores_mes_nombres' => $proveedoresMesNombres
        ],

        'visitasPorEstado' => (clone $query)
            ->selectRaw('estado, COUNT(*) total')
            ->groupBy('estado')
            ->pluck('total','estado'),

        'visitasPorProveedor' => (clone $query)
            ->selectRaw('proveedor_id, COUNT(*) total')
            ->groupBy('proveedor_id')
            ->get()
            ->mapWithKeys(fn($r)=>[
                optional($r->proveedor)->proveedor_nombre ?? 'Sin proveedor' => $r->total
            ]),

        'visitasPorPlantacion' => (clone $query)
            ->selectRaw('plantacion_id, COUNT(*) total')
            ->groupBy('plantacion_id')
            ->get()
            ->mapWithKeys(fn($r)=>[
                optional($r->plantacion)->nombre ?? 'Sin plantación' => $r->total
            ]),

        'produccionPorPlantacion' => Area::whereHas('visita')
            ->selectRaw('visita_id, AVG(produccion_toneladas_por_mes) prom')
            ->groupBy('visita_id')
            ->get()
            ->groupBy(fn($a)=> optional($a->visita->plantacion)->nombre ?? 'Sin plantación')
            ->map(fn($g)=> round($g->avg('prom'),2)),

        // 🔹 NUEVO: Información de nombres
        'nombres' => [
            'proveedores' => $proveedoresUnicos,
            'plantaciones' => $plantacionesUnicas,
            'total_proveedores' => count($proveedoresUnicos),
            'total_plantaciones' => count($plantacionesUnicas)
        ]
    ]);
}
    // ============================================================
    // MÓDULOS + PDF (MODIFICADO COMPLETAMENTE)
    // ============================================================
 public function dataModulos(Request $request)
{
    $visitas = Visita::whereNotIn('estado',['eliminado'])
        ->when($request->from, fn($q)=>$q->whereDate('fecha','>=',$request->from))
        ->when($request->to, fn($q)=>$q->whereDate('fecha','<=',$request->to))
        ->when($request->proveedor, fn($q)=>$q->where('proveedor_id',$request->proveedor))
        ->when($request->plantacion, fn($q)=>$q->where('plantacion_id',$request->plantacion))
        ->when($request->tecnico, fn($q)=>$q->where('tecnico_campo',$request->tecnico))
        ->pluck('id');

    if ($visitas->isEmpty()) {
        return response()->json([
            'fertilizacion' => [
                'productores_mes' => 0,
                'productores_anio' => 0,
                'kilos_mes' => 0,
                'kilos_anio' => 0
            ],
            'polinizacion' => [
                'total_proveedores' => 0,
                'pases' => ['1 pase' => 0, '2 pases' => 0, '3 o más' => 0],
                'clases' => ['Clase 1' => 0, 'Clase 2' => 0, 'Clase 3' => 0]
            ],
            'sanidad' => [
                'Censo PC' => 0,
                'Monitoreo Plagas' => 0,
                'Trampas RP' => 0
            ],
            'labores' => [
                'total_labores' => 0,
                'evaluaciones' => 0
            ],
            'rendimiento' => []
        ]);
    }

    $mesActual = Carbon::now()->month;
    $anioActual = Carbon::now()->year;
    $anualDesde = Carbon::now()->startOfYear()->format('Y-m-d');

    // 🔹 OBTENER NOMBRES DE PROVEEDORES Y PLANTACIONES
    $visitasConRelaciones = Visita::whereIn('id', $visitas)
        ->with(['proveedor', 'plantacion'])
        ->get();

    // Lista de proveedores únicos
    $proveedoresUnicos = $visitasConRelaciones
        ->pluck('proveedor')
        ->filter()
        ->unique('id')
        ->mapWithKeys(fn($p) => [$p->id => $p->proveedor_nombre])
        ->toArray();

    // Lista de plantaciones únicas
    $plantacionesUnicas = $visitasConRelaciones
        ->pluck('plantacion')
        ->filter()
        ->unique('id')
        ->mapWithKeys(fn($pl) => [$pl->id => $pl->nombre])
        ->toArray();

    // 🔹 FERTILIZACIÓN
    $productoresMes = Fertilizacion::whereIn('visita_id', $visitas)
        ->whereMonth('fecha_fertilizacion', $mesActual)
        ->whereYear('fecha_fertilizacion', $anioActual)
        ->distinct('visita_id')
        ->count('visita_id');

    $productoresAnio = Fertilizacion::whereIn('visita_id', $visitas)
        ->whereYear('fecha_fertilizacion', $anioActual)
        ->distinct('visita_id')
        ->count('visita_id');

    // Kilos en el mes
    $kilosMes = FertilizanteFertilizacion::whereHas('fertilizacion', function($query) use ($visitas, $mesActual, $anioActual) {
            $query->whereIn('visita_id', $visitas)
                  ->whereMonth('fecha_fertilizacion', $mesActual)
                  ->whereYear('fecha_fertilizacion', $anioActual);
        })
        ->sum('cantidad');

    // Kilos en el año
    $kilosAnio = FertilizanteFertilizacion::whereHas('fertilizacion', function($query) use ($visitas, $anioActual) {
            $query->whereIn('visita_id', $visitas)
                  ->whereYear('fecha_fertilizacion', $anioActual);
        })
        ->sum('cantidad');

    // 🔹 POLINIZACIÓN
    $polinizaciones = Polinizacion::whereIn('visita_id', $visitas)->get();
    
    // Total proveedores que realizan polinización
    $totalProveedoresPolinizacion = Visita::whereIn('id', $visitas)
        ->whereHas('polinizaciones')
        ->distinct('proveedor_id')
        ->count('proveedor_id');

    // Obtener nombres de proveedores con polinización
    $proveedoresPolinizacion = Visita::whereIn('id', $visitas)
        ->whereHas('polinizaciones')
        ->with('proveedor')
        ->get()
        ->pluck('proveedor.proveedor_nombre')
        ->filter()
        ->unique()
        ->values()
        ->toArray();

    // Pases de polinización por visita
    $pasesPorVisita = $polinizaciones->groupBy('visita_id')->map->count();
    
    $pasesCategorias = [
        '1 pase' => $pasesPorVisita->filter(fn($c) => $c == 1)->count(),
        '2 pases' => $pasesPorVisita->filter(fn($c) => $c == 2)->count(),
        '3 o más' => $pasesPorVisita->filter(fn($c) => $c >= 3)->count()
    ];

    // Clases de racimo
    $clase1Count = $polinizaciones->where('clase_racimo', 1)->count();
    $clase2Count = $polinizaciones->where('clase_racimo', 2)->count();
    $clase3Count = $polinizaciones->where('clase_racimo', 3)->count();
    
    // Si no existe el campo clase_racimo, usar distribución por pases
    if ($polinizaciones->isNotEmpty() && $clase1Count + $clase2Count + $clase3Count === 0) {
        $clase1Count = $pasesCategorias['1 pase'];
        $clase2Count = $pasesCategorias['2 pases'];
        $clase3Count = $pasesCategorias['3 o más'];
    }

    // 🔹 SANIDAD
    // Censo PC (proveedores únicos con enfermedades)
    $censoPC = Visita::whereIn('id', $visitas)
        ->whereHas('sanidad.enfermedades')
        ->distinct('proveedor_id')
        ->count('proveedor_id');

    // Proveedores con Censo PC (nombres)
    $proveedoresCensoPC = Visita::whereIn('id', $visitas)
        ->whereHas('sanidad.enfermedades')
        ->with('proveedor')
        ->get()
        ->pluck('proveedor.proveedor_nombre')
        ->filter()
        ->unique()
        ->values()
        ->toArray();

    // Monitoreo de plagas (proveedores únicos con plagas)
    $monitoreoPlagas = Visita::whereIn('id', $visitas)
        ->whereHas('sanidad.plagas')
        ->distinct('proveedor_id')
        ->count('proveedor_id');

    // Proveedores con Monitoreo de Plagas (nombres)
    $proveedoresMonitoreo = Visita::whereIn('id', $visitas)
        ->whereHas('sanidad.plagas')
        ->with('proveedor')
        ->get()
        ->pluck('proveedor.proveedor_nombre')
        ->filter()
        ->unique()
        ->values()
        ->toArray();

    // Trampas RP (proveedores únicos con trampas)
    $trampasRP = Visita::whereIn('id', $visitas)
        ->whereHas('sanidad.trampas')
        ->distinct('proveedor_id')
        ->count('proveedor_id');

    // Proveedores con Trampas RP (nombres)
    $proveedoresTrampas = Visita::whereIn('id', $visitas)
        ->whereHas('sanidad.trampas')
        ->with('proveedor')
        ->get()
        ->pluck('proveedor.proveedor_nombre')
        ->filter()
        ->unique()
        ->values()
        ->toArray();

    // 🔹 LABORES DE CULTIVO
    $laboresCount = LaboresCultivo::whereIn('visita_id', $visitas)->count();
    $evaluacionesCount = EvaluacionCosechaCampo::whereIn('visita_id', $visitas)->count();

    // 🔹 FERTILIZACIÓN - Proveedores con nombres
    $proveedoresFertilizacionMes = Visita::whereIn('id', $visitas)
        ->whereHas('fertilizaciones', function($q) use ($mesActual, $anioActual) {
            $q->whereMonth('fecha_fertilizacion', $mesActual)
              ->whereYear('fecha_fertilizacion', $anioActual);
        })
        ->with('proveedor')
        ->get()
        ->pluck('proveedor.proveedor_nombre')
        ->filter()
        ->unique()
        ->values()
        ->toArray();

    $proveedoresFertilizacionAnio = Visita::whereIn('id', $visitas)
        ->whereHas('fertilizaciones', function($q) use ($anioActual) {
            $q->whereYear('fecha_fertilizacion', $anioActual);
        })
        ->with('proveedor')
        ->get()
        ->pluck('proveedor.proveedor_nombre')
        ->filter()
        ->unique()
        ->values()
        ->toArray();

    return response()->json([
        // FERTILIZACIÓN
        'fertilizacion' => [
            'productores_mes' => $productoresMes,
            'productores_anio' => $productoresAnio,
            'kilos_mes' => round($kilosMes, 2),
            'kilos_anio' => round($kilosAnio, 2),
            'proveedores_mes_nombres' => $proveedoresFertilizacionMes,
            'proveedores_anio_nombres' => $proveedoresFertilizacionAnio
        ],

        // POLINIZACIÓN
        'polinizacion' => [
            'total_proveedores' => $totalProveedoresPolinizacion,
            'pases' => $pasesCategorias,
            'clases' => [
                'Clase 1' => $clase1Count,
                'Clase 2' => $clase2Count,
                'Clase 3' => $clase3Count
            ],
            'proveedores_nombres' => $proveedoresPolinizacion
        ],

        // SANIDAD
        'sanidad' => [
            'Censo PC' => $censoPC,
            'Monitoreo Plagas' => $monitoreoPlagas,
            'Trampas RP' => $trampasRP,
            'proveedores_censo_pc' => $proveedoresCensoPC,
            'proveedores_monitoreo' => $proveedoresMonitoreo,
            'proveedores_trampas' => $proveedoresTrampas
        ],

        // LABORES
        'labores' => [
            'total_labores' => $laboresCount,
            'evaluaciones' => $evaluacionesCount
        ],

        // RENDIMIENTO HISTÓRICO
        'rendimiento' => $this->getRendimientoHistorico($request),

        // 🔹 INFORMACIÓN ADICIONAL DE NOMBRES
        'nombres' => [
            'proveedores' => $proveedoresUnicos,
            'plantaciones' => $plantacionesUnicas,
            'total_proveedores' => count($proveedoresUnicos),
            'total_plantaciones' => count($plantacionesUnicas)
        ]
    ]);
}

    // 🔹 NUEVO MÉTODO: Rendimiento histórico
    private function getRendimientoHistorico($request)
    {
        // Simulación - deberías reemplazar con tus datos reales
        return [
            'labels' => ['2021', '2022', '2023', '2024', '2025'],
            'rendimiento_proyectado' => [18, 19, 20, 21, 22],
            'cpo' => [15, 16, 17, 18, 19],
            'tea' => [12, 13, 14, 15, 16]
        ];
    }
}