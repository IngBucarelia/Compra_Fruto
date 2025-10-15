<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaOfflineController extends Controller
{
    public function sync(Request $request)
    {
        $datos = $request->input('datos', []);

        foreach ($datos as $item) {
            Area::create([
                'visita_id' => $item['visita_id'],
                'material' => $item['material'],
                'estado' => $item['estado'],
                'anio_siembra' => $item['anio_siembra'],
                'area' => $item['area'],
                'orden_plantis_numero' => $item['orden_plantis_numero'],
                'estado_orden_plantis' => $item['estado_orden_plantis'],
            ]);
        }

        return response()->json(['success' => true]);
    }
}

