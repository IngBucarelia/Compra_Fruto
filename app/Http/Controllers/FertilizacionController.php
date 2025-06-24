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

}
