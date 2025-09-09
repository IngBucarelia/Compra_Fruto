<?php

namespace App\Http\Controllers;

use App\Models\Sanidad;
use App\Models\Visita;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SanidadController extends Controller
{
    /**
     * Formulario para crear sanidad en una visita
     */
    public function create(Request $request)
    {
        $visita_id = $request->query('visita_id');

        $visita = Visita::with([
            'area',
            'fertilizaciones.fertilizantes',
            'polinizaciones',
            'sanidades.enfermedades',
            'sanidades.plagas',
            'sanidades.trampas'
        ])->findOrFail($visita_id);

        return view('sanidades.create', compact('visita'));
    }

    /**
     * Guardar una nueva sanidad con enfermedades, plagas y trampas
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'visita_id' => 'required|exists:visitas,id',
            'otros' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string|max:1000',
            'censo_enfermedades' => 'nullable|boolean',
            'ciclos_lectura_enfermedades' => 'nullable|string|max:255',
            'ciclos_lectura_plagas' => 'nullable|string|max:255',

            'enfermedades' => 'nullable|array',
            'enfermedades.*.nombre' => 'required|string|max:255',
            'enfermedades.*.estado' => 'nullable|string|max:255',

            'plagas' => 'nullable|array',
            'plagas.*.nombre' => 'required|string|max:255',
            'plagas.*.estado' => 'nullable|string|max:255',

            'trampas' => 'nullable|array',
            'trampas.*.ciclos' => 'nullable|string|max:255',
            'trampas.*.machos' => 'nullable|integer|min:0',
            'trampas.*.hembras' => 'nullable|integer|min:0',
        ]);

        $data['censo_enfermedades'] = $request->has('censo_enfermedades');

        DB::transaction(function () use ($data) {
            $sanidad = Sanidad::create($data);

            if (!empty($data['enfermedades'])) {
                foreach ($data['enfermedades'] as $enf) {
                    $sanidad->enfermedades()->create([
                        'nombre_enfermedad' => $enf['nombre'],
                        'estado' => $enf['estado'] ?? null,
                    ]);
                }
            }

            if (!empty($data['plagas'])) {
                foreach ($data['plagas'] as $pla) {
                    $sanidad->plagas()->create([
                        'nombre_plaga' => $pla['nombre'],
                        'estado' => $pla['estado'] ?? null,
                    ]);
                }
            }

            if (!empty($data['trampas'])) {
                foreach ($data['trampas'] as $trampaData) {
                    $sanidad->trampas()->create([
                        'ciclos' => $trampaData['ciclos'] ?? null,
                        'machos_capturados' => $trampaData['machos'] ?? null,
                        'hembras_capturadas' => $trampaData['hembras'] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('suelos.create', ['visita_id' => $data['visita_id']])
            ->with('success', 'Registro de sanidad guardado correctamente.');
    }


    /**
     * Eliminar sanidad
     */
    public function destroy($id)
    {
        $sanidad = Sanidad::findOrFail($id);
        $visita_id = $sanidad->visita_id;
        $sanidad->delete();

        return redirect()->route('sanidades.create', ['visita_id' => $visita_id])
            ->with('success', 'Sanidad eliminada correctamente.');
    }

    /**
     * Formulario de edición
     */
    public function edit($id)
    {
        $sanidad = Sanidad::with(['enfermedades', 'plagas', 'trampas'])->findOrFail($id);
        $visita = $sanidad->visita;

        return view('sanidades.edit', compact('sanidad', 'visita'));
    }

    /**
     * Actualizar sanidad con relaciones
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'visita_id' => 'required|exists:visitas,id',
            'otros' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string|max:1000',
            'censo_enfermedades' => 'nullable|boolean',
            'ciclos_lectura_enfermedades' => 'nullable|string|max:255',
            'ciclos_lectura_plagas' => 'nullable|string|max:255',

            'enfermedades' => 'nullable|array',
            'enfermedades.*.nombre' => 'required|string|max:255',
            'enfermedades.*.estado' => 'nullable|string|max:255',

            'plagas' => 'nullable|array',
            'plagas.*.nombre' => 'required|string|max:255',
            'plagas.*.estado' => 'nullable|string|max:255',

            'trampas' => 'nullable|array',
            'trampas.*.ciclos' => 'nullable|string|max:255',
            'trampas.*.machos' => 'nullable|integer|min:0',
            'trampas.*.hembras' => 'nullable|integer|min:0',
        ]);

        $data['censo_enfermedades'] = $request->has('censo_enfermedades');

        DB::transaction(function () use ($data, $id) {
            $sanidad = Sanidad::findOrFail($id);
            $sanidad->update($data);

            $sanidad->enfermedades()->delete();
            $sanidad->plagas()->delete();
            $sanidad->trampas()->delete();

            if (!empty($data['enfermedades'])) {
                foreach ($data['enfermedades'] as $enf) {
                    $sanidad->enfermedades()->create([
                        'nombre_enfermedad' => $enf['nombre'],
                        'estado' => $enf['estado'] ?? null,
                    ]);
                }
            }

            if (!empty($data['plagas'])) {
                foreach ($data['plagas'] as $pla) {
                    $sanidad->plagas()->create([
                        'nombre_plaga' => $pla['nombre'],
                        'estado' => $pla['estado'] ?? null,
                    ]);
                }
            }

            if (!empty($data['trampas'])) {
                foreach ($data['trampas'] as $trampa) {
                    $sanidad->trampas()->create([
                        'ciclos' => $trampa['ciclos'] ?? null,
                        'machos_capturados' => $trampa['machos'] ?? null,
                        'hembras_capturadas' => $trampa['hembras'] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('suelos.create', ['visita_id' => $data['visita_id']])
            ->with('success', '✅ Sanidad actualizada correctamente.');
}


    /**
     * Sincronización offline → online
     */
   public function syncOffline(Request $request)
    {
        $data = $request->json()->all();
        $logLocalId = $data['local_id'] ?? 'N/A';
        Log::info('Datos recibidos para sincronizar Sanidad:', ['data' => $data, 'local_id' => $logLocalId]);

        try {
            $request->validate([
                'local_id' => 'required',
                'visita_id' => 'required|integer|exists:visitas,id',
                'otros' => 'nullable|string|max:255',
                'observaciones' => 'nullable|string',
                'censo_enfermedades' => 'nullable|boolean',
                'ciclos_lectura_enfermedades' => 'nullable|string|max:255',
                'ciclos_lectura_plagas' => 'nullable|string|max:255',

                'enfermedades' => 'nullable|array',
                'enfermedades.*.nombre' => 'required|string|max:255',
                'enfermedades.*.estado' => 'nullable|string|max:255',

                'plagas' => 'nullable|array',
                'plagas.*.nombre' => 'required|string|max:255',
                'plagas.*.estado' => 'nullable|string|max:255',

                'trampas' => 'nullable|array',
                'trampas.*.ciclos' => 'nullable|string|max:255',
                'trampas.*.machos' => 'nullable|integer|min:0',
                'trampas.*.hembras' => 'nullable|integer|min:0',
            ]);

            DB::beginTransaction();

            $sanidad = Sanidad::updateOrCreate(
                ['local_id' => $data['local_id'], 'visita_id' => $data['visita_id']],
                $data
            );

            $sanidad->enfermedades()->delete();
            $sanidad->plagas()->delete();
            $sanidad->trampas()->delete();

            foreach ($data['enfermedades'] ?? [] as $enf) {
                $sanidad->enfermedades()->create([
                    'nombre_enfermedad' => $enf['nombre'],
                    'estado' => $enf['estado'] ?? null,
                ]);
            }

            foreach ($data['plagas'] ?? [] as $pla) {
                $sanidad->plagas()->create([
                    'nombre_plaga' => $pla['nombre'],
                    'estado' => $pla['estado'] ?? null,
                ]);
            }

            foreach ($data['trampas'] ?? [] as $trampa) {
                $sanidad->trampas()->create([
                    'ciclos' => $trampa['ciclos'] ?? null,
                    'machos_capturados' => $trampa['machos'] ?? null,
                    'hembras_capturadas' => $trampa['hembras'] ?? null,
                ]);
            }

            $visita = Visita::find($data['visita_id']);
            if ($visita && $visita->estado === 'pendiente') {
                $visita->estado = 'en_ejecucion';
                $visita->save();
            }

            DB::commit();

            Log::info('✅ Sanidad sincronizada correctamente.', [
                'visita_id' => $data['visita_id'],
                'local_id' => $data['local_id']
            ]);

            return response()->json(['message' => 'Sanidad sincronizada con éxito', 'local_id' => $data['local_id']]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("❌ Error al sincronizar Sanidad (local_id: {$logLocalId}): " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);
            return response()->json(['message' => 'Error interno al sincronizar Sanidad', 'error' => $e->getMessage()], 500);
        }
    }

}
