<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

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
        $data = $request->json()->all();
        Log::info('Datos recibidos para sincronizar Polinización:', $data);

        try {
            $request->validate([
                'visita_id' => 'required|integer',
                'n_pases' => 'required|integer',
                'ciclos_ronda' => 'required|integer',
                'ana' => 'required|numeric',
                'tipo_ana' => 'required|string',
                'talco' => 'required|numeric',
                // 'indexeddb_id' => 'nullable|string',
            ]);

            // Asumiendo que solo hay un registro de Polinización por Visita.
            // Si puede haber múltiples, necesitarías una clave única adicional.
            Polinizacion::updateOrCreate(
                ['visita_id' => $data['visita_id']],
                $data
            );

            Log::info('Registro de Polinización sincronizado con éxito.', ['visita_id' => $data['visita_id']]);
            return response()->json(['message' => 'Polinización sincronizada con éxito.']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("Error de validación al sincronizar Polinización: " . $e->getMessage(), ['errors' => $e->errors(), 'data' => $data]);
            return response()->json(['message' => 'Error de validación.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error("Error inesperado al sincronizar Polinización: " . $e->getMessage(), ['data' => $data, 'trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Error interno del servidor al sincronizar Polinización.', 'error' => $e->getMessage()], 500);
        }
    }

}
