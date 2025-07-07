<?php

namespace App\Http\Controllers;

use App\Models\Polinizacion;
use App\Models\Visita;
use Illuminate\Http\Request;

class PolinizacionController extends Controller
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
        $visita = \App\Models\Visita::with(['proveedor', 'area', 'fertilizaciones.fertilizantes'])->findOrFail($visita_id);
        return view('polinizaciones.create', compact('visita'));
    }



   public function store(Request $request)
    {
        $data = $request->validate([
            'visita_id' => 'required|exists:visitas,id',
            'fecha' => 'required|date',
            'n_pases' => 'required|integer',
            'ciclos_ronda' => 'required|integer',
            'ana' => 'required|numeric',
            'tipo_ana' => 'required|in:solido,liquido',
            'talco' => 'required|numeric',
        ]);

        Polinizacion::create($data);

        // Redirigir a Sanidad
        return redirect()->route('sanidades.create', ['visita_id' => $data['visita_id']])
            ->with('success', '✅ Polinización registrada. Continúa con el registro de sanidad.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Polinizacion $polinizacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
        public function edit($id)
        {
            $polinizacion = Polinizacion::findOrFail($id);
            $visita = Visita::with(['area', 'fertilizaciones.fertilizantes'])->findOrFail($polinizacion->visita_id);

            return view('polinizaciones.edit', compact('polinizacion', 'visita'));
        }

        public function update(Request $request, $id)
        {
            $polinizacion = Polinizacion::findOrFail($id);

            $data = $request->validate([
                'fecha'         => 'required|date',
                'n_pases'       => 'required|integer',
                'ciclos_ronda'  => 'required|integer',
                'ana'           => 'required|numeric',
                'tipo_ana'      => 'required|in:solido,liquido',
                'talco'         => 'required|numeric',
            ]);

            $polinizacion->update($data);

            return redirect()->route('visitas.show', $polinizacion->visita_id)
                ->with('success', '✅ Polinización actualizada correctamente.');
        }

        public function destroy($id)
        {
            $polinizacion = Polinizacion::findOrFail($id);
            $visita_id = $polinizacion->visita_id;
            $polinizacion->delete();

            return redirect()->route('polinizaciones.create', ['visita_id' => $visita_id])
                ->with('success', '❌ Polinización eliminada correctamente.');
        }
        // controllador offline
         


        public function syncOffline(Request $request)
    {
        foreach ($request->all() as $data) {
            Polinizacion::create($data);
        }
        return response()->json(['message' => 'Polinizaciones sincronizadas']);
    }

}
