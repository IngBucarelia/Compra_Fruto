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
    public function edit(Area $area)
    {
        $visita = $area->visita()->with('proveedor')->first();
        return view('areas.edit', compact('area', 'visita'));
    }

    public function update(Request $request, Area $area)
    {
        $data = $request->validate([
            'material' => 'required|in:guinense,hibrido',
            'estado' => 'required|in:desarrollo,produccion',
            'anio_siembra' => 'required|date',
            'area' => 'required|integer',
            'orden_plantis_numero' => 'required|integer',
            'estado_oren_plantis' => 'required|in:desarrollo,produccion',
        ]);

        $area->update($data);

        return redirect()->route('areas.create', ['visita_id' => $area->visita_id])
            ->with('success', 'Área actualizada correctamente.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function syncOfflineData(Request $request)
    {
        $submissions = $request->input('submissions');
        $results = [];

        foreach ($submissions as $submission) {
            try {
                if ($submission['formName'] === 'area') {
                    $validated = validator($submission['formData'], [
                        'visita_id' => 'required|exists:visitas,id',
                        'material' => 'required|in:guinense,hibrido',
                        'estado' => 'required',
                        'anio_siembra' => 'required|date',
                        'area' => 'required|numeric',
                        'orden_plantis_numero' => 'required|numeric',
                        'estado_oren_plantis' => 'required'
                    ])->validate();

                    \App\Models\Area::updateOrCreate(
                        ['visita_id' => $validated['visita_id']],
                        $validated
                    );

                    $results[] = ['id' => $submission['formName'], 'success' => true];
                }
            } catch (\Exception $e) {
                $results[] = ['id' => $submission['formName'], 'success' => false, 'message' => $e->getMessage()];
            }
        }

        return response()->json(['results' => $results]);
    }

    // controllador offline
     

     public function syncOffline(Request $request)
    {
        foreach ($request->all() as $data) {
            Area::updateOrCreate(
                ['visita_id' => $data['visita_id']],
                $data
            );
        }
        return response()->json(['message' => 'Áreas sincronizadas correctamente']);
    }

}
