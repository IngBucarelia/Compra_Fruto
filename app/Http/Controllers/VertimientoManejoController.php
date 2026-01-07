<?php

namespace App\Http\Controllers;

use App\Models\VertimientoManejo;
use App\Models\VisitaAmbiental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VertimientoManejoController extends Controller
{
    public function create($visitaId)
    {

         $visita = VisitaAmbiental::with([
            'aguaCaptacionLegal',
            'aguaUsoEficiente',
            'sueloConservacion'
        ])->findOrFail($visitaId);
        
        return view('vertimientos.create', [
            'visita' => $visita,  // Pasar $visita, no $visitaId
            'visitaId' => $visita->id
        ]);
        
    }

    public function store(Request $request)
    { Log::info('Request data recibido:', $request->all());

        // Validación básica
        $request->validate([
            'visita_ambiental_id' => 'required|exists:visita_ambientals,id',
            'permiso_vertimientos' => 'required|in:1,0',
            'sistema_agua_domestica' => 'required|in:1,0',
            'sistema_agroquimicos' => 'required|in:1,0',
            'cumple_permiso' => 'required|in:1,0',
            'gestion_permiso' => 'required|in:1,0',
            'triple_lavado' => 'required|in:1,0',
        ]);

        // **CONVERTIR EXPLÍCITAMENTE A BOOLEAN/INTEGER**
        $data = [
            'visita_ambiental_id' => (int) $request->visita_ambiental_id,
            'permiso_vertimiento' => (int) $request->permiso_vertimientos, // Convertir a int
            'sistemas_tratamiento_domestico' => (int) $request->sistema_agua_domestica,
            'sistemas_tratamiento_agroquimicos' => (int) $request->sistema_agroquimicos,
            'cumple_obligacion_permiso' => (int) $request->cumple_permiso,
            'gestion_permiso_vertimiento' => (int) $request->gestion_permiso,
            'realiza_triplelavado' => (int) $request->triple_lavado,
            'observaciones' => $request->observaciones ?: null,
        ];

        // Manejo específico para campos numéricos
        if ($request->permiso_vertimientos == '1') {
            $request->validate([
                'numero_vertimientos_permitidos' => 'required|integer|min:0',
                'numero_vertimientos_totales' => 'required|integer|min:0',
            ]);

            $data['numero_vertimientos_permitidos'] = (int) $request->numero_vertimientos_permitidos;
            $data['numero_vertimientos_totales'] = (int) $request->numero_vertimientos_totales;

            if ($data['numero_vertimientos_permitidos'] > $data['numero_vertimientos_totales']) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'numero_vertimientos_permitidos' => 'Los vertimientos con permiso no pueden ser mayores al total.',
                    ]);
            }
        } else {
            // Forzar null explícitamente
            $data['numero_vertimientos_permitidos'] = null;
            $data['numero_vertimientos_totales'] = null;
        }

        Log::info('Data procesada para guardar:', $data);

        try {
            // **FORZAR INSERT MANUALMENTE SI ES NECESARIO**
            $vertimiento = new VertimientoManejo();
            
            foreach ($data as $key => $value) {
                $vertimiento->{$key} = $value;
            }
            
            $vertimiento->save();
            
            Log::info('Registro creado exitosamente:', $vertimiento->toArray());
            
            return redirect()
                ->route('plantacion_hmp.create', $request->visita_ambiental_id)
                ->with('success', 'Registro guardado correctamente.');
                
        } catch (\Exception $e) {
            Log::error('Error al crear vertimiento:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Hubo un problema al guardar: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $registro = VertimientoManejo::findOrFail($id);
        return view('ambiental.vertimientos.edit', compact('registro'));
    }

    public function update(Request $request, $id)
    {
        $registro = VertimientoManejo::findOrFail($id);

        $data = $request->validate([
            'permiso_vertimiento' => 'nullable|boolean',
            'sistemas_tratamiento_domestico' => 'nullable|boolean',
            'sistemas_tratamiento_agroquimicos' => 'nullable|boolean',
            'cumple_obligacion_permiso' => 'nullable|boolean',
            'gestion_permiso_vertimiento' => 'nullable|boolean',
            'realiza_triplelavado' => 'nullable|boolean',
            'observaciones' => 'nullable|string'
        ]);

        $registro->update($data);

        return back()->with('success','Registro actualizado');
    }
}
