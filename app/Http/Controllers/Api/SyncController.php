<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fertilizacion;

class SyncController extends Controller
{
    public function fertilizaciones(Request $request)
    {
        foreach ($request->datos as $item) {
            Fertilizacion::create([
                'visita_id' => $item['visita_id'],
                'fecha_fertilizacion' => $item['fecha_fertilizacion'],
                'fertilizantes' => json_encode($item['fertilizantes']),
            ]);
        }

        return response()->json(['mensaje' => 'Fertilizaciones sincronizadas correctamente']);
    }
}
