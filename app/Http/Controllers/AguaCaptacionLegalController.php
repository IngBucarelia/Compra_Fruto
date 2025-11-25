<?php

namespace App\Http\Controllers;

use App\Models\AguaCaptacionLegal;
use App\Models\VisitaAmbiental;
use Illuminate\Http\Request;

class AguaCaptacionLegalController extends Controller
{
    public function create($visitaId)
    {
        $visita = VisitaAmbiental::findOrFail($visitaId);
        return view('agua_captacion_legal.create', compact('visita'));
    }

    public function store(Request $request, $visitaId)
    {
        $visita = VisitaAmbiental::findOrFail($visitaId);

        $data = $request->validate([
            'permiso_concesion' => 'nullable|boolean',
            'permiso_ocupacion_cauce' => 'nullable|boolean',
            'permisos_captacion' => 'nullable|boolean',
            'registro_agua' => 'nullable|boolean',
            'cumple_manejo_construccion' => 'nullable|boolean',
            'gestion_permiso_ocupacion' => 'nullable|boolean',
            'gestion_permiso_captacion' => 'nullable|boolean',
            'observaciones' => 'nullable|string'
        ]);

        // Normaliza checkboxes (si vienen 'on' o 1)
        foreach ([
            'permiso_concesion','permiso_ocupacion_cauce','permisos_captacion','registro_agua',
            'cumple_manejo_construccion','gestion_permiso_ocupacion','gestion_permiso_captacion'
        ] as $f) {
            $data[$f] = $request->has($f) ? (bool)$request->input($f) : null;
        }

        $data['visita_ambiental_id'] = $visita->id;

        AguaCaptacionLegal::create($data);

        // Actualiza estado de la visita si se desea
        $visita->update(['estado' => 'en_proceso']);
               
         return view('agua_uso_eficiente.create', compact('visita'))->with('success', '✅ Agua Captacion Legal  registradas correctamente. Continúa con Agua Uso Eficiente.');

       
    }

    public function edit($visitaId)
    {
        $visita = VisitaAmbiental::findOrFail($visitaId);
        $modelo = AguaCaptacionLegal::where('visita_ambiental_id', $visitaId)->first();
        return view('componentes.agua_captacion_legal.edit', compact('visita','modelo'));
    }

    public function update(Request $request, $visitaId)
    {
        $modelo = AguaCaptacionLegal::where('visita_ambiental_id', $visitaId)->firstOrFail();

        $data = $request->validate([
            'permiso_concesion' => 'nullable|boolean',
            'permiso_ocupacion_cauce' => 'nullable|boolean',
            'permisos_captacion' => 'nullable|boolean',
            'registro_agua' => 'nullable|boolean',
            'cumple_manejo_construccion' => 'nullable|boolean',
            'gestion_permiso_ocupacion' => 'nullable|boolean',
            'gestion_permiso_captacion' => 'nullable|boolean',
            'observaciones' => 'nullable|string'
        ]);

        foreach ([
            'permiso_concesion','permiso_ocupacion_cauce','permisos_captacion','registro_agua',
            'cumple_manejo_construccion','gestion_permiso_ocupacion','gestion_permiso_captacion'
        ] as $f) {
            $data[$f] = $request->has($f) ? (bool)$request->input($f) : null;
        }

        $modelo->update($data);

        return redirect()->route('visitasAmbientales.show', $visitaId)->with('success','Componente actualizado');
    }

    public function destroy($visitaId)
    {
        $modelo = AguaCaptacionLegal::where('visita_ambiental_id', $visitaId)->first();
        if($modelo) { $modelo->delete(); }
        return redirect()->route('visitasAmbientales.show', $visitaId)->with('success','Componente eliminado');
    }
}
