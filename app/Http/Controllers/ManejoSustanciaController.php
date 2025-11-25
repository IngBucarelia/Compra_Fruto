<?php

namespace App\Http\Controllers;

use App\Models\ManejoSustancia;
use Illuminate\Http\Request;

class ManejoSustanciaController extends Controller
{
    public function create($visitaId)
    {
        return view('ambiental.manejo_sustancias.create', compact('visitaId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'visita_ambiental_id' => 'required|integer',
            'cuenta_con_poes' => 'nullable|boolean',
            'personal_capacitado_certificado' => 'nullable|boolean',
            'almacenamiento_adecuado' => 'nullable|boolean',
            'observaciones' => 'nullable|string'
        ]);

        ManejoSustancia::create($data);

        return back()->with('success','Registro guardado');
    }

    public function edit($id)
    {
        $registro = ManejoSustancia::findOrFail($id);
        return view('ambiental.manejo_sustancias.edit', compact('registro'));
    }

    public function update(Request $request, $id)
    {
        $registro = ManejoSustancia::findOrFail($id);

        $data = $request->validate([
            'cuenta_con_poes' => 'nullable|boolean',
            'personal_capacitado_certificado' => 'nullable|boolean',
            'almacenamiento_adecuado' => 'nullable|boolean',
            'observaciones' => 'nullable|string'
        ]);

        $registro->update($data);

        return back()->with('success','Registro actualizado');
    }
}
