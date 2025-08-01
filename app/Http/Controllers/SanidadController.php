<?php

namespace App\Http\Controllers;
use App\Models\Sanidad;
use App\Models\Visita;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // Necesario si usas transacciones o DB::beginTransaction



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
            'leptopharsa' => 'nullable|integer|min:0|max:100',
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
                'leptopharsa' => 'nullable|integer|min:0|max:100',
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

                // controllador offline


    public function syncOffline(Request $request)
    {
        $data = $request->json()->all();
        // ✅ CORRECCIÓN: Evaluar la expresión ?? fuera de la interpolación de cadena
        $logLocalId = $data['local_id'] ?? 'N/A';
        Log::info('Datos recibidos para sincronizar Sanidad:', ['data' => $data, 'local_id' => $logLocalId]);

        try {
            // ✅ AÑADIDO: Validación para 'local_id'
            $request->validate([
                'visita_id' => 'required|integer|exists:visitas,id', // Asegura que la visita exista
                'opsophanes' => 'nullable|integer|min:0|max:100',
                'pudricion_cogollo' => 'nullable|integer|min:0|max:100',
                'raspador' => 'nullable|integer|min:0|max:100',
                'palmarum' => 'nullable|integer|min:0|max:100',
                'strategus' => 'nullable|integer|min:0|max:100',
                'leptopharsa' => 'nullable|integer|min:0|max:100',
                'pestalotiopsis' => 'nullable|integer|min:0|max:100',
                'pudricion_basal' => 'nullable|integer|min:0|max:100',
                'pudricion_estipe' => 'nullable|integer|min:0|max:100',
                'otros' => 'nullable|string|max:255',
                'observaciones' => 'nullable|string',
            ]);

            DB::beginTransaction(); // Iniciar transacción para asegurar la atomicidad
            try {
                // ✅ CAMBIO CLAVE: Usar 'local_id' para buscar y actualizar o crear el registro
                // Esto permite manejar múltiples registros de sanidad por visita y la edición offline.
                Sanidad::updateOrCreate(
                     // Clave única para buscar
                    $data // Datos a crear o actualizar
                );

                // Opcional: Actualizar el estado de la visita a 'en_ejecucion'
                $visita = Visita::find($data['visita_id']);
                if ($visita && $visita->estado === 'pendiente') {
                    $visita->estado = 'en_ejecucion';
                    $visita->save();
                }

                DB::commit(); // Confirmar la transacción
                Log::info('Registro de Sanidad sincronizado con éxito.', ['visita_id' => $data['visita_id'], 'local_id' => $data['local_id']]);
                return response()->json(['message' => 'Sanidad sincronizada con éxito.', 'local_id' => $data['local_id']]);

            } catch (\Exception $e) {
                DB::rollBack(); // Revertir la transacción en caso de error
                // ✅ CORRECCIÓN: Evaluar la expresión ?? fuera de la interpolación de cadena
                $logLocalId = $data['local_id'] ?? 'N/A';
                Log::error("Error al guardar Sanidad (ID Local: {$logLocalId}): " . $e->getMessage(), ['trace' => $e->getTraceAsString(), 'data' => $data]);
                return response()->json(['message' => 'Error internoeeeeee del servidor al sincronizar Sanidad.', 'error' => $e->getMessage()], 500);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("Error de validación al sincronizar Sanidad: " . $e->getMessage(), ['errors' => $e->errors(), 'data' => $data]);
            return response()->json(['message' => 'Error de validación.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // ✅ CORRECCIÓN: Evaluar la expresión ?? fuera de la interpolación de cadena
            $logLocalId = $data['local_id'] ?? 'N/A';
            Log::error("Error inesperado al sincronizar Sanidad: " . $e->getMessage(), ['data' => $data, 'trace' => $e->getTraceAsString(), 'local_id' => $logLocalId]);
            return response()->json(['message' => 'Error interno del servidor al sincronizar Sanidad.', 'error' => $e->getMessage()], 500);
        }
    }



}
