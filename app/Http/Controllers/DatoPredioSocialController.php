<?php

namespace App\Http\Controllers;

use App\Models\DatoPredioSocial;
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

        return view('datos_predio_social.index', compact('visita', 'plantaciones', 'datos'));
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

        DatoPredioSocial::create([
            'visita_social_id' => $visitaId,
            'plantacion_id' => $plantacionId,
            'nombre_finca' => $request->nombre_finca,
            'forma_tenencia' => $request->forma_tenencia,
            'municipio' => $request->municipio,
            'vereda' => $request->vereda,
            'registrado_ica' => $request->registrado_ica,
            'vive_predio' => $request->vive_predio,
            'infraestructura_vial' => json_encode($request->infraestructura_vial),
            'infraestructura_predio' => $request->infraestructura_predio,
        ]);

        return redirect()->route('datos_predio_social.index', $visitaId)
            ->with('success', 'Datos del predio guardados correctamente.');
    }

    public function edit($visitaId, $id)
    {
        $dato = DatoPredioSocial::findOrFail($id);
        $visita = VisitaSocial::findOrFail($visitaId);

        return view('datos_predio_social.edit', compact('dato', 'visita'));
    }

    public function update(Request $request, $visitaId, $id)
    {
        $dato = DatoPredioSocial::findOrFail($id);

        $dato->update([
            'nombre_finca' => $request->nombre_finca,
            'forma_tenencia' => json_encode($request->forma_tenencia),
            'municipio' => $request->municipio,
            'vereda' => $request->vereda,
            'registrado_ica' => $request->registrado_ica,
            'vive_predio' => $request->vive_predio,
            'infraestructura_vial' => json_encode($request->infraestructura_vial),
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
