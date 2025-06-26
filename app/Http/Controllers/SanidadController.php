<?php

namespace App\Http\Controllers;
use App\Models\Sanidad;
use App\Models\Visita;

use Illuminate\Http\Request;

class SanidadController extends Controller
{
public function create(Request $request)
    {
        $visita_id = $request->query('visita_id');
        
        $visita = Visita::with([
            'area',
            'fertilizaciones.fertilizantes',
            'polinizaciones',
            'sanidades' 
        ])->findOrFail($visita_id);

        return view('sanidades.create', compact('visita'));
    }


public function store(Request $request)
    {
        $data = $request->validate([
            'visita_id' => 'required|exists:visitas,id',
            'opsophanes' => 'nullable|integer|min:0|max:100',
            'pudricion_cogollo' => 'nullable|integer|min:0|max:100',
            'raspador' => 'nullable|integer|min:0|max:100',
            'palmarum' => 'nullable|integer|min:0|max:100',
            'strategus' => 'nullable|integer|min:0|max:100',
            'leptoparsha' => 'nullable|integer|min:0|max:100',
            'pestalotiopsis' => 'nullable|integer|min:0|max:100',
            'pudricion_basal' => 'nullable|integer|min:0|max:100',
            'pudricion_estipe' => 'nullable|integer|min:0|max:100',
            'otros' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string|max:1000',
        ]);


        Sanidad::create($data);

      return redirect()->route('suelos.create', ['visita_id' => $data['visita_id']])
    ->with('success', 'Registro de sanidad guardado correctamente.');
 }

    public function destroy($id)
    {
        $sanidad = Sanidad::findOrFail($id);
        $visita_id = $sanidad->visita_id;
        $sanidad->delete();

        return redirect()->route('sanidades.create', ['visita_id' => $visita_id])
            ->with('success', 'Sanidad eliminada correctamente.');
    }

    public function edit($id)
        {
            $sanidad = Sanidad::findOrFail($id);
            $visita = $sanidad->visita;

            return view('sanidades.edit', compact('sanidad', 'visita'));
        }

    public function update(Request $request, $id)
        {
            $data = $request->validate([
                'opsophanes' => 'nullable|integer|min:0|max:100',
                'pudricion_cogollo' => 'nullable|integer|min:0|max:100',
                'raspador' => 'nullable|integer|min:0|max:100',
                'palmarum' => 'nullable|integer|min:0|max:100',
                'strategus' => 'nullable|integer|min:0|max:100',
                'leptoparsha' => 'nullable|integer|min:0|max:100',
                'pestalotiopsis' => 'nullable|integer|min:0|max:100',
                'pudricion_basal' => 'nullable|integer|min:0|max:100',
                'pudricion_estipe' => 'nullable|integer|min:0|max:100',
                'otros' => 'nullable|string|max:255',
                'observaciones' => 'nullable|string|max:1000',
            ]);

            $sanidad = Sanidad::findOrFail($id);
            $sanidad->update($data);

            return redirect()->route('suelos.create', ['visita_id' => $sanidad->visita_id])
                ->with('success', '✅ Sanidad actualizada correctamente.');
        }




}
