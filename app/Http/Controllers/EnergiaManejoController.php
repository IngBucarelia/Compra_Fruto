<?php

namespace App\Http\Controllers;

use App\Models\EnergiaManejo;
use Illuminate\Http\Request;

class EnergiaManejoController extends Controller
{
    public function create($visitaId)
    {
        return view('energia.create', compact('visitaId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'visita_ambiental_id' => 'required|exists:visita_ambientals,id',
        ]);

        EnergiaManejo::create($request->all());

        return redirect()
            ->route('visitas_ambiental.show', $request->visita_ambiental_id)
            ->with('success', 'Registro de energía guardado correctamente');
    }
}
