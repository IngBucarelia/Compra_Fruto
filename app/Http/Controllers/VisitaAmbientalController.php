<?php

namespace App\Http\Controllers;

use App\Helpers\AuditoriaHelper;
use App\Models\DatosPersonalesSocial;
use App\Models\Proveedor;
use App\Models\User;
use App\Models\VisitaAmbiental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VisitaAmbientalController extends Controller
{


    public function home()
    {
        return view('ambiental.home', ['user' => Auth::user()]);
    }



    public function index()
    {
        $visitas = VisitaAmbiental::orderBy('fecha_visita','desc')->paginate(20);
        return view('ambiental.index', compact('visitas'));
    }

   public function create()
{
    // Técnicos ambientales = Rol 4 (ejemplo, ajusta a tu rol real)
    $tecnicos = User::where('rol', 4)->get();

    // Proveedores igual que en Social
    $proveedores = Proveedor::all();

    return view('ambiental.create', compact('tecnicos', 'proveedores'));
}

public function store(Request $request)
{
    try {
        Log::info('=== INICIANDO STORE VISITA AMBIENTAL ===');
        Log::info('Datos recibidos:', $request->all());

        // VALIDACIONES
        $validator = validator()->make($request->all(), [
            'fecha_visita' => 'required|date',
            'tecnico_id' => 'required|exists:users,id',
            'proveedor_id' => 'required|exists:proveedores,id',
            'plantacion_id' => 'required|exists:plantaciones,id',
            'ubicacion' => 'required|string',
            'observaciones' => 'nullable|string',
            'tipo_visita_ambiental' => 'required|array',
        ]);

        // Validación de tipos permitidos
        $tiposValidos = [
            'Monitoreo',
            'Seguimiento',
            'Verificacion',
            'Biodiversidad',
            'Fauna',
            'Flora',
            'Impacto Ambiental'
        ];

        $tiposSeleccionados = $request->input('tipo_visita_ambiental', []);

        foreach ($tiposSeleccionados as $tipo) {
            if (!in_array($tipo, $tiposValidos)) {
                $validator->errors()->add('tipo_visita_ambiental', "Tipo de visita no válido: $tipo");
            }
        }

        if ($validator->fails()) {
            Log::error('Validación fallida en visita ambiental:', $validator->errors()->toArray());
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        DB::beginTransaction();

        Log::info('Creando visita ambiental...');

        $visita = \App\Models\VisitaAmbiental::create([
            'fecha_visita' => $request->fecha_visita,
            'tecnico_id' => $request->tecnico_id,
            'proveedor_id' => $request->proveedor_id,
            'plantacion_id' => $request->plantacion_id,
            'ubicacion' => $request->ubicacion,
            'observaciones' => $request->observaciones,
            'tipo_visita_ambiental' => implode(', ', $tiposSeleccionados),
            'estado' => 'pendiente'
        ]);

        DB::commit();

        Log::info('Visita ambiental creada correctamente:', ['id' => $visita->id]);

        AuditoriaHelper::registrar(
            'create',
            'Visita Ambiental',
            $visita->id,
            'Se creó una nueva visita ambiental al proveedor ID ' . $request->proveedor_id
        );

        return redirect()
            ->route('visitasAmbientales.index')
            ->with('success', 'Visita ambiental creada exitosamente.');

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        return redirect()->back()
            ->withErrors($e->validator)
            ->withInput()
            ->with('error', 'Por favor revisa los datos ingresados.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al crear la visita ambiental: ' . $e->getMessage());
        return redirect()->back()
            ->withInput()
            ->with('error', 'Error al crear la visita ambiental: ' . $e->getMessage());
    }
}


    public function show($id)
{
    // Traer la visita con relaciones (igual estructura que social)
    $visita = VisitaAmbiental::with([
        'plantacion',
        'tecnico',
        'aguaCaptacionLegal',
        'aguaUsoEficiente', 
        'sueloConservacion',
        'energia',
        'gobernanzaHidrica',
        'emisionesGei',
        'residuosManejo',
        'sustanciasManejo',
        'vertimientosManejo',
        'hmpManejo',
        'avcControl',
        'ecosistemaProteccion',
        'avcNoReemplazo',
        'deforestacionControl'
    ])->findOrFail($id);

    // Otras visitas ambientales (misma lógica que social)
    $otrasVisitasAmbientales = collect();
    if ($visita->plantacion && $visita->plantacion->id) {
        $otrasVisitasAmbientales = VisitaAmbiental::where('plantacion_id', $visita->plantacion->id)
            ->where('id', '!=', $visita->id)
            ->orderBy('fecha_visita', 'desc')
            ->get();
    }

    return view('ambiental.show', compact('visita', 'otrasVisitasAmbientales'));
}
    public function edit($id)
    {
        $visita = VisitaAmbiental::findOrFail($id);
        return view('ambiental.edit', compact('visita'));
    }

    public function update(Request $request, $id)
    {
        $visita = VisitaAmbiental::findOrFail($id);

        $data = $request->validate([
            'plantacion_id' => 'nullable|integer',
            'tecnico_id' => 'nullable|integer',
            'fecha_visita' => 'required|date',
            'ubicacion' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'estado' => 'nullable|in:pendiente,en_proceso,completada'
        ]);

        $visita->update($data);
        return redirect()->route('visitasAmbientales.show', $visita->id)->with('success','Visita actualizada');
    }

    public function destroy($id)
    {
        $visita = VisitaAmbiental::findOrFail($id);
        $visita->delete();
        return redirect()->route('visitasAmbientales.index')->with('success','Visita eliminada');
    }

    public function iniciarVisita(Request $request, $id)
    {
        $visita = VisitaAmbiental::findOrFail($id);

        // Si está pendiente, la cambiamos a en_ejecucion
        if ($visita->estado === 'pendiente') {
            $visita->estado = 'en_proceso';
            $visita->save();
        }

        // Redirige según lo seleccionado
        if ($request->seccion === 'datos_personales') {
            return redirect()->route('aguaCaptacion.create', ['visita_id' => $visita->id]);
        }

        return back()->with('info', 'Sección no válida.');
    }

    public function redirigirSeccion(Request $request, $visitaId)
{

    $seccion = $request->input('seccion');

     if ($seccion === 'agua_captacion_legal') {
        return redirect()->route('aguaCaptacion.create', $visitaId);
    }  
    if ($seccion === 'agua_uso_eficiente') {
        return redirect()->route('agua_uso_eficiente.create', $visitaId);
    }  
    if ($seccion === 'suelo_conservacion') {
        return redirect()->route('suelo_conservacion.create', $visitaId);
    }   
    if ($seccion === 'energia') {
        return redirect()->route('energia.create', $visitaId);
    }
    if ($seccion === 'gobernanza_hidrica') {
        return redirect()->route('gobernanza.create', $visitaId);
    }
    if ($seccion === 'emisiones_gei') {
        return redirect()->route('emisiones_gei.create', $visitaId);
    }
    if ($seccion === 'residuos_manejo') {
        return redirect()->route('residuos.create', $visitaId);
    }
    if ($seccion === 'sustancias_manejo') {
        return redirect()->route('sustancias.create', $visitaId);
    }
    if ($seccion === 'vertimientos_manejo') {
        return redirect()->route('vertimientos.create', $visitaId);
    }
    if ($seccion === 'hmp_manejo') {
        return redirect()->route('plantacion_hmp.create', $visitaId);
    }

     if ($seccion === 'avc_control') {
        return redirect()->route('plantacion_avc.create', $visitaId);
    }
    if ($seccion === 'ecosistema_proteccion') {
        return redirect()->route('plantacion_ecosistemas.create', $visitaId);
    }
    if ($seccion === 'avc_no_reemplazo') {
        return redirect()->route('pno_reemplazo.create', $visitaId);
    }


    return back()->with('info', 'Seleccione una opción válida');
}
}
