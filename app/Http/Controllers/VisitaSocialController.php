<?php

namespace App\Http\Controllers;

use App\Helpers\AuditoriaHelper;
use App\Models\DatoPredioSocial;
use App\Models\DatosPersonalesSocial;
use App\Models\PlanificacionSocial;
use App\Models\VisitaSocial;
use App\Models\Proveedor;
use App\Models\Plantacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\VisitasSocialImport;


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
        // Filtra solo los estados válidos
        ->whereIn('estado', ['pendiente', 'en_ejecucion', 'finalizada'])
        // Aplica búsqueda si hay texto
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

   public function iniciar(VisitaSocial $visita)
{
    try {
        // Validar que la visita esté pendiente
        if ($visita->estado !== 'pendiente') {
            return redirect()->back()->with('error', 'La visita no puede ser iniciada en su estado actual');
        }

        // Actualizar estado de la visita
        $visita->update([
            'estado' => 'en_ejecucion',
            'fecha_inicio' => now()
        ]);

        // Redirigir CORRECTAMENTE a la primera sección
        return redirect()->route('redireccion_seccion_social', [
            'id' => $visita->id,  // Cambiar 'visita' por 'id'
            'seccion' => 'datos_personales'
        ])->with('success', '¡Visita social iniciada correctamente!');

    } catch (\Exception $e) {
        Log::error("Error iniciando visita social {$visita->id}: " . $e->getMessage());
        return redirect()->back()->with('error', 'Error al iniciar la visita: ' . $e->getMessage());
    }
}

    public function create()
    {
        $tecnicos = User::where('rol', 3)->get(); // ⚡ ejemplo: rol 3 = técnico social
        $proveedores = Proveedor::all();
        return view('visitas_social.create', compact('tecnicos', 'proveedores'));
    }

    public function store(Request $request)
    {
        try {
            Log::info('=== INICIANDO STORE VISITA SOCIAL ===');
            Log::info('Datos recibidos:', $request->all());

            // 🔹 Validación inicial
            $validator = validator()->make($request->all(), [
                'fecha' => 'required|date',
                'tecnico_campo' => 'required|exists:users,id',
                'proveedor_id' => 'required|exists:proveedores,id',
                'plantacion_id' => 'required|exists:plantaciones,id',
                'recibio_visita' => 'required|string',
                'tipo_visita' => 'required|array', // Acepta múltiples opciones
            ]);

            $tipoVisitaArray = $request->input('tipo_visita', []);
            $tipoVisita = implode(', ', $tipoVisitaArray); // 🔸 Convertimos el array a string

            $tiposValidos = [
                'Inicial',
                'Seguimiento',
                'Capacitacion',
                'Poa',
                'Estudio Credito',
                'Inclusion a Pequeños',
                'Solidaridad',
                'caracterizacion',
                'Aps'
            ];

            // 🔹 Validar los tipos seleccionados
            foreach ($tipoVisitaArray as $tipo) {
                if (!in_array($tipo, $tiposValidos)) {
                    $validator->errors()->add('tipo_visita', "Tipo de visita no válido: $tipo");
                }
            }

            if ($validator->fails()) {
                Log::error('Validación fallida en visita social:', $validator->errors()->toArray());
                throw new \Illuminate\Validation\ValidationException($validator);
            }

            DB::beginTransaction();

            Log::info('Creando planificación social...');

            // 🔹 Crear la planificación social
            $planificacion = \App\Models\PlanificacionSocial::create([
                'fecha' => $request->fecha,
                'tecnico_campo' => $request->tecnico_campo,
                'proveedor_id' => $request->proveedor_id,
                'plantacion_id' => $request->plantacion_id,
                'estado' => 'pendiente',
                'tipo_visita' => $tipoVisita // Guardamos como string
            ]);

            Log::info('Planificación social creada:', ['id' => $planificacion->id]);

            // 🔹 Obtener ubicación desde la plantación
            $plantacion = \App\Models\Plantacion::find($request->plantacion_id);
            if (!$plantacion) {
                throw new \Exception('No se encontró la plantación seleccionada.');
            }

            $ubicacion = $plantacion->vereda . ', ' . $plantacion->municipio . ', ' . $plantacion->departamento;

            Log::info('Creando visita social...');

            // 🔹 Crear la visita social
            $visita = \App\Models\VisitaSocial::create([
                'fecha' => $request->fecha,
                'proveedor_id' => $request->proveedor_id,
                'plantacion_id' => $request->plantacion_id,
                'ubicacion' => $ubicacion,
                'tecnico_campo' => $request->tecnico_campo,
                'tipo_visita' => $tipoVisita, // Guardamos igual como string
                'recibio_visita' => $request->recibio_visita,
                'planificacion_id' => $planificacion->id,
                'estado' => 'pendiente'
            ]);

            Log::info('Visita social creada correctamente:', ['id' => $visita->id]);

            // 🔹 Vincular planificación con visita
            $planificacion->update(['visita_id' => $visita->id]);

            DB::commit();

            Log::info('=== VISITA SOCIAL CREADA EXITOSAMENTE ===');
            
            AuditoriaHelper::registrar(
                'create',
                'Visita Social',
                $visita->id,
                'Se creó una nueva visita Social al proveedor ID ' . $request->proveedor_id
            );

            return redirect()->route('visitas_social.indexSocial')
                ->with('success', 'Visita social y planificación creadas exitosamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Error de validación en visita social:', $e->errors());
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Por favor verifica los datos ingresados.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear la visita social: ' . $e->getMessage());
            Log::error('Stack trace:', ['trace' => $e->getTraceAsString()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear la visita social: ' . $e->getMessage());
        }
    }


    public function eliminadas(Request $request)
        {
            $buscar = $request->input('buscar');

            $visitas = \App\Models\VisitaSocial::with(['proveedor', 'tecnico', 'plantacion'])
                ->where('estado', 'eliminada')
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

            return view('visitas_social.eliminadas', compact('visitas', 'buscar'));
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


            // PlanificacionSocialController.php

   

        // VisitaSocialController.php

public function edit($id)
{
    try {
        // Cambiar PlanificacionSocial por VisitaSocial
        $visita = VisitaSocial::with(['proveedor', 'plantacion', 'tecnico'])->findOrFail($id);
        
        $proveedores = Proveedor::all();
        $tecnicos = User::where('rol', 3)->get(); // Usando rol = 2 como en create
        
        return view('visitas_social.edit', compact('visita', 'proveedores', 'tecnicos'));
        
    } catch (\Exception $e) {
        return redirect()->route('visitas_social.indexSocial')
            ->with('error', 'No se pudo cargar la visita para editar: ' . $e->getMessage());
    }
}

public function update(Request $request, $id)
{
    try {
        // Cambiar PlanificacionSocial por VisitaSocial
        $visita = VisitaSocial::findOrFail($id);

        $data = $request->validate([
            'fecha' => 'required|date',
            'tecnico_campo' => 'required|exists:users,id',
            'proveedor_id' => 'required|exists:proveedores,id',
            'plantacion_id' => 'required|exists:plantaciones,id',
            'ubicacion' => 'required|string|max:255',
            'tipo_visita' => 'required|string',
            'recibio_visita' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        // Actualizar la visita social
        $visita->update($data);

        // Si existe una planificación asociada, actualizarla también (si aplica)
        if ($visita->planificacion_id) {
            $planificacion = PlanificacionSocial::find($visita->planificacion_id);
            if ($planificacion) {
                $planificacion->update([
                    'fecha' => $data['fecha'],
                    'tecnico_campo' => $data['tecnico_campo'],
                    'proveedor_id' => $data['proveedor_id'],
                    'plantacion_id' => $data['plantacion_id'],
                    'tipo_visita' => $data['tipo_visita']
                ]);
            }
        }

        DB::commit();
        AuditoriaHelper::registrar(
                'edit',
                'Visita Social',
                $visita->id,
                'Se edito una visita Social al proveedor ID ' . $request->proveedor_id
            );
        return redirect()->route('visitas_social.indexSocial')
            ->with('success', 'Visita social actualizada exitosamente.');

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        return redirect()->back()
            ->withErrors($e->validator)
            ->withInput()
            ->with('error', 'Error de validación: Por favor verifica los datos ingresados.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al actualizar visita social: ' . $e->getMessage());
        
        return redirect()->back()
            ->withInput()
            ->with('error', 'Error al actualizar la visita: ' . $e->getMessage());
    }
}
   public function destroy($id)
    {
        try {
            $visita = \App\Models\VisitaSocial::findOrFail($id);
            
            // Cambiamos el estado en lugar de eliminar
            $visita->estado = 'eliminada';
            $visita->save();

            // Registrar en auditoría
            \App\Helpers\AuditoriaHelper::registrar(
                'delete',
                'Visita Social',
                $visita->id,
                'Se eliminó una visita social.'
            );

            return redirect()->route('visitas_social.indexSocial')
                ->with('success', 'La visita social fue marcada como eliminada correctamente.');

        } catch (\Exception $e) {
            return redirect()->route('visitas_social.indexSocial')
                ->with('error', 'Error al eliminar la visita social: ' . $e->getMessage());
        }
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



public function importForm()
{
    return view('visitas_social.import');
}

public function import(Request $request)
{
    try {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new VisitasSocialImport, $request->file('file'));

        return redirect()->back()->with('success', '✅ Archivo cargado correctamente.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', '❌ Error al procesar el archivo: ' . $e->getMessage());
    }
}


}
