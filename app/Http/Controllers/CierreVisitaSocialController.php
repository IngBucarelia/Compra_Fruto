<?php

namespace App\Http\Controllers;

use App\Models\CierreVisitaSocial;
use App\Models\VisitaSocial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CierreVisitaSocialController extends Controller
{
    public function create(int $visita_social_id)
    {
        $visita = VisitaSocial::with([
            'proveedor',
            'plantacion',
            'cierreVisitaSocial'
        ])->findOrFail($visita_social_id);

        $cierreExistente = CierreVisitaSocial::where('visita_social_id', $visita_social_id)->first();
        if ($cierreExistente) {
            return redirect()->route('visitas_social.showSocial', $visita->id)
                ->with('info', '⚠️ Esta visita social ya fue finalizada.');
        }

        return view('cierre_visitas_social.create', compact('visita', 'cierreExistente'));
    }

    public function store(Request $request)
{
    try {
        $request->validate([
            'visita_social_id' => 'required|exists:visita_socials,id',
            'fecha_cierre' => 'required|date',
            'estado_visita' => 'required|string',
        ]);

        // Guardar firmas como archivos
        $firmaResponsable = null;
        if ($request->firma_responsable) {
            $firmaResponsable = $this->saveBase64Image($request->firma_responsable, 'firmas');
        }

        $firmaRecibe = null;
        if ($request->firma_recibe) {
            $firmaRecibe = $this->saveBase64Image($request->firma_recibe, 'firmas');
        }

        $firmaTestigo = null;
        if ($request->firma_testigo) {
            $firmaTestigo = $this->saveBase64Image($request->firma_testigo, 'firmas');
        }

        // Guardar imágenes (array de base64)
        $imagenesPaths = [];
        if ($request->imagenes && is_array($request->imagenes)) {
            foreach ($request->imagenes as $img64) {
                $imagenesPaths[] = $this->saveBase64Image($img64, 'visitas_social');
            }
        }

        $cierre = CierreVisitaSocial::create([
            'visita_social_id'    => $request->visita_social_id,
            'fecha_cierre'        => $request->fecha_cierre,
            'estado_visita'       => $request->estado_visita,
            'observaciones_finales' => $request->observaciones_finales,
            'recomendaciones'     => $request->recomendaciones,
            'firma_responsable'   => $firmaResponsable,
            'firma_recibe'        => $firmaRecibe,
            'firma_testigo'       => $firmaTestigo,
            'finalizada_en'       => now(),
            'imagenes' => $imagenesPaths,

        ]);

        return response()->json([
            'message' => 'Cierre de visita social guardado correctamente',
            'data' => $cierre
        ], 201);

    } catch (\Exception $e) {
        Log::error("Error en cierre de visita social: ".$e->getMessage());
        return response()->json([
            'message' => 'Error al guardar el cierre de visita social'
        ], 500);
    }
}

/**
 * Guardar imagen base64 como archivo físico en storage
 */
private function saveBase64Image($base64Image, $folder)
{
    $image = str_replace('data:image/png;base64,', '', $base64Image);
    $image = str_replace(' ', '+', $image);
    $imageName = $folder . '/' . uniqid() . '.png';
    Storage::disk('public')->put($imageName, base64_decode($image));
    return 'storage/' . $imageName;
}

}
