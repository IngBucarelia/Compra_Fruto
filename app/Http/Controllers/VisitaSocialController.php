<?php

namespace App\Http\Controllers;

use App\Models\DatoPredioSocial;
use App\Models\DatosPersonalesSocial;
use App\Models\VisitaSocial;
use App\Models\Proveedor;
use App\Models\Plantacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;


class VisitaSocialController extends Controller
{
    public function home()
    {
        return view('visitas_social.home', ['user' => Auth::user()]);
    }

    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $visitas = VisitaSocial::with(['proveedor', 'tecnico', 'plantacion'])
            ->when($buscar, function ($query) use ($buscar) {
                return $query->where(function ($q) use ($buscar) {
                    $q->where('tipo_visita', 'like', "%$buscar%")
                    ->orWhere('ubicacion', 'like', "%$buscar%")
                    ->orWhereHas('proveedor', function ($q) use ($buscar) {
                        $q->where('proveedor_nombre', 'like', "%$buscar%");
                    })
                    ->orWhereHas('tecnico', function ($q) use ($buscar) {
                        $q->where('name', 'like', "%$buscar%");
                    })
                    ->orWhereHas('plantacion', function ($q) use ($buscar) {
                        $q->where('nombre', 'like', "%$buscar%");
                    });
                });
            })
            ->latest()
            ->paginate(10);

        return view('visitas_social.index', compact('visitas', 'buscar'));
    }

    public function create()
    {
        $tecnicos = User::where('rol', 2)->get(); // ⚡ ejemplo: rol 3 = técnico social
        $proveedores = Proveedor::all();
        return view('visitas_social.create', compact('tecnicos', 'proveedores'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fecha' => 'required|date',
            'tecnico_campo' => 'required|exists:users,id',
            'proveedor_id' => 'required|exists:proveedores,id',
            'plantacion_id' => 'required|exists:plantaciones,id',
            'ubicacion' => 'required|string',
            'tipo_visita' => 'required|string',
            'recibio_visita' => 'required|string',
        ]);

        $visita = VisitaSocial::create([...$data, 'estado' => 'pendiente']);

        return redirect()->route('visitas_social.indexSocial')->with('success', 'Visita Social creada con éxito.');
    }


public function show($id)
{
    // Traer la visita con relaciones que puedas usar en la vista
    $visita = VisitaSocial::with([
    'proveedor',
    'tecnico',
    'plantacion',
    'datosPersonales',
    'miembros',
    'predio',             
    'fuerzaLaboral',
    'organizacionSocial'
])->findOrFail($id);


    // Otras visitas sociales sobre la misma plantación (excepto la actual)
    $otrasVisitasSociales = collect();
    if ($visita->plantacion && $visita->plantacion->id) {
        $otrasVisitasSociales = VisitaSocial::where('plantacion_id', $visita->plantacion->id)
            ->where('id', '!=', $visita->id)
            ->orderBy('fecha', 'desc')
            ->get();
    }

    // Pasa todo a la vista
    return view('visitas_social.show', compact('visita', 'otrasVisitasSociales'));
}


    public function edit($id)
    {
        $visita = VisitaSocial::findOrFail($id);
        $proveedores = Proveedor::all();
        $plantaciones = Plantacion::where('id_proveedor', $visita->proveedor_id)->get();
        $tecnicos = User::where('rol', 2)->get();

        return view('visitas_social.edit', compact('visita', 'proveedores', 'plantaciones', 'tecnicos'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'fecha' => 'required|date',
            'ubicacion' => 'required|string',
            'tecnico_campo' => 'required|exists:users,id',
            'tipo_visita' => 'required|string',
            'proveedor_id' => 'required|exists:proveedores,id',
            'recibio_visita' => 'required|string',
        ]);

        $visita = VisitaSocial::findOrFail($id);
        $visita->update($data);

        return redirect()->route('visitas_social.indexSocial')->with('success', 'Visita Social actualizada.');
    }

    public function destroy($id)
    {
        $visita = VisitaSocial::findOrFail($id);
        $visita->delete();

        return redirect()->route('visitas_social.indexSocial')->with('success', 'Visita eliminada.');
    }

   public function exportarPDF($id)
{
    // Cargamos relaciones que usará la vista
    $visita = VisitaSocial::with([
        'miembros',
        'fuerzaLaboral',
        'organizacionSocial',
    ])->findOrFail($id);

    // Colección de plantaciones del proveedor (si necesitas listarlas)
    $plantaciones = Plantacion::where('id_proveedor', $visita->proveedor_id)->get();

    // 'datos' que vinculaste en la vista (datos del predio por visita)
    $datos = DatoPredioSocial::where('visita_social_id', $id)->get();

    $pdf = Pdf::loadView('visitas_social.exportar_pdf', compact('visita', 'plantaciones', 'datos'))
              ->setPaper('a4', 'portrait');

    $nombreArchivo = "Visita_Social_{$visita->id}.pdf";
    return $pdf->download($nombreArchivo);
}
    public function exportarExcel($id)
    {
        //return Excel::download(new \App\Exports\VisitaSocialExport($id), "detalle_visita_social_{$id}.xlsx");
    }

    public function updateStatus(Request $request, VisitaSocial $visita)
    {
        $visita->estado = 'finalizado';
        $visita->save();

        return response()->json([
            'message' => 'Estado actualizado a finalizado',
            'visita' => $visita,
        ]);
    }


    public function detalle($id)
    {
        $visita = VisitaSocial::with([
            
        ])->findOrFail($id);

        // Opcional: Para depurar los datos que recibes
        // Log::info('Datos de visita para detalle:', $visita->toArray());

        return view('visitas_social.detalle', compact('visita'));
    }

    public function iniciarVisita(Request $request, $id)
    {
        $visita = VisitaSocial::findOrFail($id);

        // Si está pendiente, la cambiamos a en_ejecucion
        if ($visita->estado === 'pendiente') {
            $visita->estado = 'en_ejecucion';
            $visita->save();
        }

        // Redirige según lo seleccionado
        if ($request->seccion === 'datos_personales') {
            return redirect()->route('datos_personales_sociales.create', ['visita_id' => $visita->id]);
        }

        return back()->with('info', 'Sección no válida.');
    }

    public function redirigirSeccion(Request $request, $visitaId)
{
    $visita = VisitaSocial::findOrFail($visitaId);

    $seccion = $request->input('seccion');

    if ($seccion === 'datos_personales') {
        // ✅ Reutiliza tu lógica actual de create()
        $datos = DatosPersonalesSocial::where('visita_social_id', $visitaId)->first();

        if ($datos) {
            return redirect()->route('datos_personales_sociales.create', $visita->id) ;
        } else {
            return redirect()->route('datos_personales_sociales.create', $visitaId);
        }
    }

    if ($seccion === 'miembros') {
        return redirect()->route('miembros_hogar.index', $visitaId);
    }

     if ($seccion === 'predio') {
        return redirect()->route('datos_predio_social.index', $visitaId);
    }

     if ($seccion === 'fuerza_laboral') {
        return redirect()->route('fuerza_laboral.index', $visitaId);
    }

    if ($seccion === 'organizacion_social') {
        return redirect()->route('organizacion_social.index', $visitaId);
    }

    if ($seccion === 'inicio') {
        return redirect()->route('visitas_social.showSocial', $visitaId);
    }
    if ($seccion === 'cierre_visita') {
        return redirect()->route('cierre-visitas-social.create', $visitaId);
    }

    return back()->with('info', 'Seleccione una opción válida');
}






}
