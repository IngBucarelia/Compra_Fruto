<?php

namespace App\Http\Controllers;

use App\Models\Fertilizacion;
use App\Models\Visita;
use Illuminate\Http\Request;

class FertilizacionController extends Controller
{
   public function create(Request $request)
        {
            $visita_id = $request->query('visita_id');
            $visita = \App\Models\Visita::with(['proveedor', 'fertilizaciones.fertilizantes', 'area'])->findOrFail($visita_id);

            return view('fertilizaciones.create', compact('visita'));
        }


public function store(Request $request)
        {
            $data = $request->validate([
                'visita_id' => 'required|exists:visitas,id',
                'fecha_fertilizacion' => 'required|date',
                'fertilizantes.*.nombre' => 'required|string',
                'fertilizantes.*.cantidad' => 'required|integer|min:1'
            ]);

            $fertilizacion = Fertilizacion::create([
                'visita_id' => $data['visita_id'],
                'fecha_fertilizacion' => $data['fecha_fertilizacion'],
            ]);

            foreach ($data['fertilizantes'] as $fertil) {
                $fertilizacion->detalles()->create([
                    'fertilizante' => $fertil['nombre'],
                    'cantidad' => $fertil['cantidad'],
                ]);
            }

            return redirect()->route('polinizaciones.create', ['visita_id' => $data['visita_id']]);

        }
        public function edit($id)
        {
            $fertilizacion = Fertilizacion::with('fertilizantes')->findOrFail($id);
            $visita = $fertilizacion->visita;

            return view('fertilizaciones.edit', compact('fertilizacion', 'visita'));
        }

        public function update(Request $request, $id)
        {
            $data = $request->validate([
                'fecha_fertilizacion' => 'required|date',
                'fertilizantes' => 'required|array',
                'fertilizantes.*.nombre' => 'required|string',
                'fertilizantes.*.cantidad' => 'required|numeric|min:0',
            ]);

            $fertilizacion = Fertilizacion::findOrFail($id);
            $fertilizacion->update([
                'fecha_fertilizacion' => $data['fecha_fertilizacion']
            ]);

            // Eliminar anteriores
            $fertilizacion->fertilizantes()->delete();

            // Insertar nuevos
            foreach ($data['fertilizantes'] as $fertilizante) {
                $fertilizacion->fertilizantes()->create([
                    'fertilizante' => $fertilizante['nombre'],
                    'cantidad' => $fertilizante['cantidad'],
                ]);
            }

            return redirect()->route('fertilizaciones.create', ['visita_id' => $fertilizacion->visita_id])
                ->with('success', 'Fertilización actualizada correctamente.');
        }

        public function destroy($id)
    {
        $fertilizacion = Fertilizacion::findOrFail($id);
        $visitaId = $fertilizacion->visita_id;

        $fertilizacion->fertilizantes()->delete();
        $fertilizacion->delete();

        return redirect()->route('fertilizaciones.create', ['visita_id' => $visitaId])
            ->with('success', 'Fertilización eliminada.');
    }

        // controllador offline 


     public function syncOffline(Request $request)
    {
        foreach ($request->all() as $data) {
            Fertilizacion::create($data);
        }
        return response()->json(['message' => 'Fertilizaciones sincronizadas']);
    }



}
