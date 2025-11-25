<?php

namespace App\Http\Controllers;

use App\Models\EmisionesGei;
use Illuminate\Http\Request;

class EmisionesGeiController extends Controller
{
    public function create($visitaId)
    {
        return view('emisiones_gei.create', compact('visitaId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'visita_ambiental_id' => 'required|exists:visitas_ambientales,id',
            'cuantifica_emisiones' => 'required',
            'implementa_acciones_reduccion' => 'required',
        ]);

        EmisionesGei::create($request->all());

        return redirect()
            ->route('visitasAmbientales.show', $request->visita_ambiental_id)
            ->with('success', 'Registro guardado correctamente');
    }
}
