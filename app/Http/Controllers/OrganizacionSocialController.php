<?php

namespace App\Http\Controllers;

use App\Models\OrganizacionSocial;
use App\Models\VisitaSocial;
use Illuminate\Http\Request;

class OrganizacionSocialController extends Controller
{
    public function index($visita_id)
    {
        $visita = VisitaSocial::findOrFail($visita_id);
        $organizaciones = OrganizacionSocial::where('visita_id', $visita_id)->get();

        return view('organizacion_social.index', compact('visita', 'organizaciones'));
    }

    public function create($visita_id)
    {
        $visita = VisitaSocial::findOrFail($visita_id);
        return view('organizacion_social.create', compact('visita'));
    }

    public function store(Request $request, $visita_id)
    {
        $request->validate([
            'pertenece_jac' => 'required',
            'pertenece_asociacion' => 'required',
            'nombre_asociacion' => 'nullable|string|max:255',
        ]);

        OrganizacionSocial::create([
            'visita_id' => $visita_id,
            'pertenece_jac' => $request->pertenece_jac,
            'pertenece_asociacion' => $request->pertenece_asociacion,
            'nombre_asociacion' => $request->nombre_asociacion,
        ]);

        return redirect()->route('organizacion_social.index', $visita_id)
                         ->with('success', 'Registro creado exitosamente ✅');
    }

    public function show($visita_id, $id)
    {
        $visita = VisitaSocial::findOrFail($visita_id);
        $organizacion = OrganizacionSocial::findOrFail($id);

        return view('organizacion_social.show', compact('visita', 'organizacion'));
    }

    public function edit($visita_id, $id)
    {
        $visita = VisitaSocial::findOrFail($visita_id);
        $organizacion = OrganizacionSocial::findOrFail($id);

        return view('organizacion_social.edit', compact('visita', 'organizacion'));
    }

    public function update(Request $request, $visita_id, $id)
    {
        $request->validate([
            'pertenece_jac' => 'required',
            'pertenece_asociacion' => 'required',
            'nombre_asociacion' => 'nullable|string|max:255',
        ]);

        $organizacion = OrganizacionSocial::findOrFail($id);
        $organizacion->update($request->all());

        return redirect()->route('organizacion_social.index', $visita_id)
                         ->with('success', 'Registro actualizado ✅');
    }
}
