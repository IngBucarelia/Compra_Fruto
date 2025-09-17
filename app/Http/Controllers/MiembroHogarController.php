<?php

namespace App\Http\Controllers;

use App\Models\MiembroHogar;
use App\Models\VisitaSocial;
use Illuminate\Http\Request;

class MiembroHogarController extends Controller
{
    public function index($visitaId)
    {
        $visita = VisitaSocial::findOrFail($visitaId);
        $miembros = MiembroHogar::where('visita_social_id', $visitaId)->get();

        return view('miembros_hogar.show', compact('visita', 'miembros'));
    }

    public function create($visitaId)
    {
        $visita = VisitaSocial::findOrFail($visitaId);
        return view('miembros_hogar.create', compact('visita'));
    }

    public function store(Request $request, $visitaId)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'documento' => 'required|string|max:50',
            'sexo' => 'required|string',
            'parentezco' => 'required|string',
            'reside_predio' => 'required|boolean',
            'sabe_leer' => 'required|boolean',
            'nivel_estudio' => 'required|string',
            'participa_labores' => 'required|boolean',
        ]);

        MiembroHogar::create([
            'visita_social_id' => $visitaId,
            'nombre' => $request->nombre,
            'documento' => $request->documento,
            'sexo' => $request->sexo,
            'parentezco' => $request->parentezco,
            'reside_predio' => $request->reside_predio,
            'sabe_leer' => $request->sabe_leer,
            'nivel_estudio' => $request->nivel_estudio,
            'participa_labores' => $request->participa_labores,
        ]);

        return redirect()->route('datos_predio_social.index', $visitaId)
                         ->with('success', 'Miembro agregado correctamente');
    }

    public function edit($visitaId, $id)
    {
        $visita = VisitaSocial::findOrFail($visitaId);
        $miembro = MiembroHogar::findOrFail($id);

        return view('miembros_hogar.edit', compact('visita', 'miembro'));
    }

    public function update(Request $request, $visitaId, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'documento' => 'required|string|max:50',
            'sexo' => 'required|string',
            'parentezco' => 'required|string',
            'reside_predio' => 'required|boolean',
            'sabe_leer' => 'required|boolean',
            'nivel_estudio' => 'required|string',
            'participa_labores' => 'required|boolean',
        ]);

        $miembro = MiembroHogar::findOrFail($id);
        $miembro->update($request->all());

        return redirect()->route('miembros_hogar.index', $visitaId)
                         ->with('success', 'Miembro actualizado correctamente');
    }

    public function destroy($visitaId, $id)
    {
        $miembro = MiembroHogar::findOrFail($id);
        $miembro->delete();

        return redirect()->route('miembros_hogar.index', $visitaId)
                         ->with('success', 'Miembro eliminado correctamente');
    }
}
