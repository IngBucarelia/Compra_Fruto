<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
        {
            $visita_id = $request->query('visita_id');
            $visita = \App\Models\Visita::findOrFail($visita_id);

            return view('areas.create', compact('visita'));
        }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
        {
            $data = $request->validate([
                'visita_id' => 'required|exists:visitas,id',
                'material' => 'required|in:guinense,hibrido',
                'estado' => 'required|in:desarrollo,produccion',
                'anio_siembra' => 'required|date',
                'area' => 'required|integer',
                'orden_plantis_numero' => 'required|integer',
                'estado_oren_plantis' => 'required|in:desarrollo,produccion',
            ]);

            Area::create($data);

            $visita = \App\Models\Visita::find($data['visita_id']);
            if ($visita) {
                $visita->estado = 'en_ejecucion';
                $visita->save();
            }

            // Redirige al siguiente formulario (fertilización)
            return redirect()->route('fertilizaciones.create', ['visita_id' => $data['visita_id']])
                ->with('success', 'Área registrada. Continúa con la fertilización.');
        }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
