<?php

namespace App\Http\Controllers;

use App\Models\ManejoResiduo;
use App\Models\ResiduosManejo;
use App\Models\VisitaAmbiental;
use Illuminate\Http\Request;

class ResiduosManejoController extends Controller
{
    public function create($visitaId)
    {
        $visita = VisitaAmbiental::with([
            'aguaCaptacionLegal',
            'aguaUsoEficiente',
            'sueloConservacion'
        ])->findOrFail($visitaId);

        return view('residuos.create', [
            'visita' => $visita,  // Pasar $visita, no $visitaId
            'visitaId' => $visita->id
        ]);
    }

    public function store(Request $request)
    {   
        $data = $request->validate([
            'visita_ambiental_id' => 'required|exists:visita_ambientals,id',

            'personas_manipulan' => 'required|integer|min:1',

            'capacita_personal' => 'required|boolean',
            'personas_capacitadas' => 'nullable|integer|min:0',
            'porcentaje_capacitadas' => 'nullable|numeric|min:0|max:100',

            'conoce_diferencias' => 'required|boolean',

            'certificado_final_respel' => 'required|boolean',
            'peso_respel' => 'nullable|numeric|min:0',
            'imagen_certificado_respel' => 'nullable|image|max:4096',

            'manifiesto_transporte_respel' => 'required|boolean',
            'imagen_manifiesto_respel' => 'nullable|image|max:4096',

            'puntos_ecologicos' => 'required|boolean',
            'entrega_transportador_aut' => 'required|boolean',
            'disposicion_empresa_aut' => 'required|boolean',
            'acciones_minimizar_impacto' => 'required|boolean',
            'certificado_relleno_sanitario' => 'required|boolean',
            'residuos_aprovechables_gestion' => 'required|boolean',
            'pesa_y_registra' => 'required|boolean',

            'observaciones' => 'nullable|string',
        ]);

        /* ============================
        MANEJO DE IMÁGENES
        ============================ */

        if ($request->hasFile('imagen_certificado_respel')) {
            $data['imagen_certificado_respel'] =
                $request->file('imagen_certificado_respel')
                        ->store('residuos/certificados', 'public');
        }

        if ($request->hasFile('imagen_manifiesto_respel')) {
            $data['imagen_manifiesto_respel'] =
                $request->file('imagen_manifiesto_respel')
                        ->store('residuos/manifiestos', 'public');
        }

        /* ============================
        NORMALIZACIÓN DE CAMPOS
        ============================ */

        if ($data['capacita_personal'] == 0) {
            $data['personas_capacitadas'] = null;
            $data['porcentaje_capacitadas'] = null;
        }

        if ($data['certificado_final_respel'] == 0) {
            $data['peso_respel'] = null;
            $data['imagen_certificado_respel'] = null;
        }

        if ($data['manifiesto_transporte_respel'] == 0) {
            $data['imagen_manifiesto_respel'] = null;
        }

        ManejoResiduo::create($data);

        return redirect()
            ->route('sustancias.create', $request->visita_ambiental_id)
            ->with('success', 'Registro de manejo de residuos guardado correctamente');
    }


    public function edit($id)
    {
        $registro = ResiduosManejo::findOrFail($id);
        return view('residuos.edit', compact('registro'));
    }

    public function update(Request $request, $id)
    {
        $registro = ResiduosManejo::findOrFail($id);

        $registro->update($request->all());

        return redirect()->route('visitasAmbientales.show', $registro->visita_ambiental_id)
            ->with('success', 'Registro actualizado correctamente');
    }

    public function destroy($id)
    {
        $registro = ResiduosManejo::findOrFail($id);
        $visita = $registro->visita_ambiental_id;

        $registro->delete();

        return redirect()->route('visitasAmbientales.show', $visita)
            ->with('success', 'Registro eliminado');
    }
}
