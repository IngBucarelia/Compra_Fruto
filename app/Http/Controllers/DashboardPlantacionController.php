<?php

namespace App\Http\Controllers;

use App\Models\Plantacion;
use Illuminate\Http\Request;

class DashboardPlantacionController extends Controller
{
public function index(Request $request)
{
    $buscar = $request->input('buscar');

    $plantacionesQuery = \App\Models\Plantacion::with([
        'proveedor', 
        'visitas', 
        'visitasSociales'
    ]);

    // Búsqueda optimizada
    if ($buscar) {
        $plantacionesQuery->where(function ($query) use ($buscar) {
            $query->where('nombre', 'like', "$buscar%")
                  ->orWhereHas('proveedor', function($q) use ($buscar) {
                      $q->where('proveedor_nombre', 'like', "$buscar%");
                  });
        });
    }

    $plantaciones = $plantacionesQuery->orderBy('nombre', 'asc')->paginate(10);

    return view('dashboard.plantaciones', compact('plantaciones', 'buscar'));
}

public function verVisitas($id)
    {
        $plantacion = Plantacion::with([
            'proveedor',
            'visitas.tecnico',
            'visitas.areas',
            'visitas.fertilizaciones',
            'visitas.sanidades',
            'visitas.suelo',
            'visitas.polinizaciones',
            'visitas.laboresCultivo',
            'visitas.evaluacionCosechaCampo',
            'visitas.cierreVisita',
            'visitasSociales.tecnico',
            'visitasSociales.datosPersonales',
            'visitasSociales.predio',
            'visitasSociales.fuerzaLaboral',
            'visitasSociales.organizacionSocial',
            'visitasSociales.miembros',
            'visitasSociales.cierreVisitaSocial'
        ])->findOrFail($id);

        return view('dashboard.detalle-visitas', compact('plantacion'));
    }
}
