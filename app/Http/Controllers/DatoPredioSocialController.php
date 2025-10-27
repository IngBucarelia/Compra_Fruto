<?php

namespace App\Http\Controllers;

use App\Models\DatoPredioSocial;
use App\Models\DatosPersonalesSocial;
use App\Models\MiembroHogar;
use App\Models\VisitaSocial;
use App\Models\Plantacion;
use Illuminate\Http\Request;

class DatoPredioSocialController extends Controller
{
    public function index($visitaId)
    {
        $visita = VisitaSocial::with('plantacion.proveedor.plantaciones')->findOrFail($visitaId);

        // Plantaciones del proveedor
        $plantaciones = $visita->plantacion->proveedor->plantaciones;

        // Datos de predio ya registrados para esa visita
        $datos = DatoPredioSocial::where('visita_social_id', $visitaId)->get();
        $datosPersonales = DatosPersonalesSocial::where('visita_social_id', $visitaId)->first();
        $miembros = MiembroHogar::where('visita_social_id', $visitaId)->get();

        return view('datos_predio_social.index', compact('visita', 'plantaciones', 'datos', 
        'datosPersonales', 
        'miembros'));
    }

    public function create($visitaId, $plantacionId)
    {
        $visita = VisitaSocial::findOrFail($visitaId);
        $plantacion = Plantacion::findOrFail($plantacionId);

        return view('datos_predio_social.create', compact('visita', 'plantacion'));
    }

        public function store(Request $request, $visitaId, $plantacionId)
    {
        $request->validate([
            'nombre_finca' => 'required|string',
        ]);

        // ✅ CORREGIDO: Asegurar que infraestructura_vial sea un array antes de convertirlo a JSON
        $infraestructuraVial = $request->infraestructura_vial;
        
        // Si es null, convertir a array vacío
        if (is_null($infraestructuraVial)) {
            $infraestructuraVial = [];
        }
        // Si es string, convertir a array
        elseif (is_string($infraestructuraVial)) {
            $infraestructuraVial = [$infraestructuraVial];
        }
        // Si ya es array, dejarlo como está
        // ✅ Asegurar que forma_tenencia sea array
           $formaTenencia = $request->forma_tenencia;

            if (is_null($formaTenencia)) {
                $formaTenencia = [];
            } elseif (is_string($formaTenencia)) {
                $formaTenencia = [$formaTenencia];
            }

            DatoPredioSocial::create([
                'visita_social_id' => $visitaId,
                'plantacion_id' => $plantacionId,
                'nombre_finca' => $request->nombre_finca,
                'forma_tenencia' => json_encode($formaTenencia),
                'municipio' => $request->municipio,
                'vereda' => $request->vereda,
                'registrado_ica' => $request->registrado_ica,
                'vive_predio' => $request->vive_predio,
                'infraestructura_vial' => json_encode($infraestructuraVial),
                'infraestructura_predio' => $request->infraestructura_predio,
            ]);


        return redirect()->route('datos_predio_social.index', $visitaId)
            ->with('success', 'Datos del predio guardados correctamente.');
    }

    public function edit($visitaId, $datoId)
{
    $visita = VisitaSocial::findOrFail($visitaId);
    $dato = DatoPredioSocial::findOrFail($datoId);
    $plantacion = Plantacion::find($dato->plantacion_id);

    // ✅ Aseguramos que sean arrays sin decodificar doble
    $dato->forma_tenencia = is_string($dato->forma_tenencia)
        ? json_decode($dato->forma_tenencia, true)
        : ($dato->forma_tenencia ?? []);

    $dato->infraestructura_vial = is_string($dato->infraestructura_vial)
        ? json_decode($dato->infraestructura_vial, true)
        : ($dato->infraestructura_vial ?? []);

    return view('datos_predio_social.edit', compact('visita', 'dato', 'plantacion'));
}

    public function update(Request $request, $visitaId, $id)
{
    $request->validate([
        'nombre_finca' => 'required|string',
    ]);

    $dato = DatoPredioSocial::findOrFail($id);

    // ✅ MISMA LÓGICA PARA UPDATE
    function ensureArray($data) {
        if (is_null($data)) {
            return [];
        }
        if (is_string($data)) {
            return [$data];
        }
        if (is_array($data)) {
            return $data;
        }
        return [];
    }

    $dato->update([
        'nombre_finca' => $request->nombre_finca,
        'forma_tenencia' => $request->forma_tenencia,
        'municipio' => $request->municipio,
        'vereda' => $request->vereda,
        'registrado_ica' => $request->registrado_ica,
        'vive_predio' => $request->vive_predio,
        'infraestructura_vial' => json_encode(ensureArray($request->infraestructura_vial)),
        'infraestructura_predio' => $request->infraestructura_predio,
    ]);

    return redirect()->route('datos_predio_social.index', $visitaId)
        ->with('success', 'Datos del predio actualizados correctamente.');
}

    public function destroy($visitaId, $id)
    {
        $dato = DatoPredioSocial::findOrFail($id);
        $dato->delete();

        return redirect()->route('datos_predio_social.index', $visitaId)
            ->with('success', 'Datos del predio eliminados correctamente.');
    }
}
