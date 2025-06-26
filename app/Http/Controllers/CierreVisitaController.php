<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CierreVisita;
use App\Models\Visita;
use Illuminate\Support\Facades\Storage;

class CierreVisitaController extends Controller
{
    public function create(Request $request)
    {
        $visita_id = $request->query('visita_id');
        $visita = Visita::with('cierreVisita')->findOrFail($visita_id);

        // Verificar si ya existe un cierre para esta visita
        if ($visita->cierreVisita) {
            return redirect()->route('visitas.show', $visita->id)
                ->with('info', '⚠️ Esta visita ya fue finalizada anteriormente.');
        }

        return view('cierre_visitas.create', compact('visita'));
    }


    public function store(Request $request)
        {
            $data = $request->validate([
                'visita_id' => 'required|exists:visitas,id',
                'firma_responsable' => 'nullable|image|max:2048',
                'observaciones_finales' => 'nullable|string',
                'recomendaciones' => 'nullable|string',
                'imagenes' => 'nullable',
            ]);

            // Guardar firma
            if ($request->hasFile('firma_responsable')) {
                $data['firma_responsable'] = $request->file('firma_responsable')->store('firmas', 'public');
            }

            // Guardar imágenes
            $imagenes = [];
            if ($request->hasFile('imagenes')) {
                foreach ($request->file('imagenes') as $img) {
                    if ($img instanceof \Illuminate\Http\UploadedFile && $img->isValid()) {
                        $imagenes[] = $img->store('imagenes_visita', 'public');
                    }
                }
            }

            // Convertir a JSON antes de guardar
            $data['imagenes'] = json_encode($imagenes);

            $data['finalizada_en'] = now();

            // Crear el registro de cierre
            CierreVisita::create($data);

            // Actualizar el estado de la visita
            \App\Models\Visita::find($data['visita_id'])->update(['estado' => 'finalizada']);

            return redirect()->route('visitas.show', $data['visita_id'])
                ->with('success', '✅ Visita finalizada con éxito.');
        }

}
