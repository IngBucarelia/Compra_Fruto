<?php

namespace App\Http\Controllers;

use App\Models\VisitaSocial;
use App\Models\MiembroHogar;
use App\Models\FuerzaLaboral;
use App\Models\DatoPredioSocial;
use App\Models\OrganizacionSocial;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardVisitaSocialController extends Controller
{
    public function index(Request $request)
{
    // --- FILTROS ---
    $desde = $request->input('desde');
    $hasta = $request->input('hasta');
    $proveedor = $request->input('proveedor');

    // Query base de visitas (única tabla que tiene proveedor_id)
    $queryVisitas = VisitaSocial::query();

    if ($desde) {
        $queryVisitas->whereDate('fecha', '>=', $desde);
    }
    if ($hasta) {
        $queryVisitas->whereDate('fecha', '<=', $hasta);
    }
    if ($proveedor) {
        $queryVisitas->where('proveedor_id', $proveedor);
    }

    // Guardamos los IDs de visitas filtradas
    $visitaIds = $queryVisitas->pluck('id');

    // --- Totales ---
    $totalVisitas = $visitaIds->count();
    $totalProveedores = $queryVisitas->distinct('proveedor_id')->count('proveedor_id');

    $totalTrabajadores = FuerzaLaboral::whereIn('visita_social_id', $visitaIds)
        ->sum('num_trabajadores');

    $totalMiembros = MiembroHogar::whereIn('visita_social_id', $visitaIds)->count();

    // --- Gráficos ---
    // Visitas por mes
    $visitasPorMes = (clone $queryVisitas)
        ->select(DB::raw('MONTH(fecha) as mes'), DB::raw('COUNT(*) as total'))
        ->groupBy('mes')
        ->orderBy('mes')
        ->get();

    // Distribución de sexo en hogares
    $sexoHogar = MiembroHogar::whereIn('visita_social_id', $visitaIds)
        ->select('sexo', DB::raw('COUNT(*) as total'))
        ->groupBy('sexo')
        ->get();

    // Nivel educativo
    $niveles = MiembroHogar::whereIn('visita_social_id', $visitaIds)
        ->select('nivel_estudio', DB::raw('COUNT(*) as total'))
        ->groupBy('nivel_estudio')
        ->orderBy('total', 'desc')
        ->get();

    // Tipos de contrato laboral
    $contratos = FuerzaLaboral::whereIn('visita_social_id', $visitaIds)
        ->select('tipo_contrato', DB::raw('COUNT(*) as total'))
        ->groupBy('tipo_contrato')
        ->orderBy('total', 'desc')
        ->get();

    // Seguridad social
    $seguridad = FuerzaLaboral::whereIn('visita_social_id', $visitaIds)
        ->select('seguridad_social', DB::raw('COUNT(*) as total'))
        ->groupBy('seguridad_social')
        ->get();

    // Forma de tenencia
    $tenencia = DatoPredioSocial::whereIn('visita_social_id', $visitaIds)
        ->select('forma_tenencia', DB::raw('COUNT(*) as total'))
        ->groupBy('forma_tenencia')
        ->get();

    // Participación social
    $participacion = [
        'jac' => OrganizacionSocial::whereIn('visita_id', $visitaIds)
            ->where('pertenece_jac', 'si')
            ->count(),
        'asociacion' => OrganizacionSocial::whereIn('visita_id', $visitaIds)
            ->where('pertenece_asociacion', 'si')
            ->count(),
    ];

    // Promedio de género
    $fuerzaGenero = FuerzaLaboral::whereIn('visita_social_id', $visitaIds)
        ->select(DB::raw('AVG(num_hombres) as hombres'), DB::raw('AVG(num_mujeres) as mujeres'))
        ->first();

    // Lista de proveedores
    $proveedores = Proveedor::select('id', 'proveedor_nombre')
        ->orderBy('proveedor_nombre')
        ->get();

    return view('visitas_social.dashboard_visitas_social', compact(
        'totalVisitas',
        'totalProveedores',
        'totalTrabajadores',
        'totalMiembros',
        'visitasPorMes',
        'sexoHogar',
        'niveles',
        'contratos',
        'seguridad',
        'tenencia',
        'participacion',
        'fuerzaGenero',
        'proveedores'
    ));
}
}
