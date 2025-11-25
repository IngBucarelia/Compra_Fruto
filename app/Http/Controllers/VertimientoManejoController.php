<?php

namespace App\Http\Controllers;

use App\Models\VertimientoManejo;
use Illuminate\Http\Request;

class VertimientoManejoController extends Controller
{
    public function create($visitaId)
    {
        return view('vertimientos.create', compact('visitaId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'visita_ambiental_id' => 'required|integer',

            'permiso_vertimiento' => 'nullable|boolean',
            'sistemas_tratamiento_domestico' => 'nullable|boolean',
            'sistemas_tratamiento_agroquimicos' => 'nullable|boolean',
            'cumple_obligacion_permiso' => 'nullable|boolean',
            'gestion_permiso_vertimiento' => 'nullable|boolean',
            'realiza_triplelavado' => 'nullable|boolean',

            'observaciones' => 'nullable|string'
        ]);

        VertimientoManejo::create($data);

        return back()->with('success','Registro guardado correctamente');
    }

    public function edit($id)
    {
        $registro = VertimientoManejo::findOrFail($id);
        return view('ambiental.vertimientos.edit', compact('registro'));
    }

    public function update(Request $request, $id)
    {
        $registro = VertimientoManejo::findOrFail($id);

        $data = $request->validate([
            'permiso_vertimiento' => 'nullable|boolean',
            'sistemas_tratamiento_domestico' => 'nullable|boolean',
            'sistemas_tratamiento_agroquimicos' => 'nullable|boolean',
            'cumple_obligacion_permiso' => 'nullable|boolean',
            'gestion_permiso_vertimiento' => 'nullable|boolean',
            'realiza_triplelavado' => 'nullable|boolean',
            'observaciones' => 'nullable|string'
        ]);

        $registro->update($data);

        return back()->with('success','Registro actualizado');
    }
}
