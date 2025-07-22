<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log; // Para registrar errores

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
        $data = $request->json()->all();
        Log::info('Datos recibidos para sincronizar Fertilización (offline):', $data);

        try {
            // Validar los datos. Esperamos que 'fertilizantes' sea un array de objetos.
            $request->validate([
                'visita_id' => 'required|integer',
                'fecha_fertilizacion' => 'required|date',
                'fertilizantes' => 'required|array', // Espera un array de detalles de fertilizantes
                'fertilizantes.*.nombre' => 'required|string',
                'fertilizantes.*.cantidad' => 'required|numeric|min:1',
            ]);

            // Separar los detalles de fertilizantes del registro principal de Fertilización
            $fertilizantesDetalles = $data['fertilizantes'];
            unset($data['fertilizantes']); // Eliminar 'fertilizantes' del array principal para el create

            // Crear o actualizar el registro principal de Fertilización
            // Usamos 'visita_id' y 'fecha_fertilizacion' como clave para updateOrCreate.
            // Si una visita puede tener varias fertilizaciones en la misma fecha,
            // considera usar un 'id_offline' generado en el frontend para la unicidad.
            $fertilizacion = Fertilizacion::updateOrCreate(
                [
                    'visita_id' => $data['visita_id'],
                    'fecha_fertilizacion' => $data['fecha_fertilizacion']
                ],
                $data // Solo los campos de la tabla fertilizaciones
            );

            // Eliminar los detalles de fertilización existentes para esta fertilización
            // antes de volver a crearlos, para evitar duplicados en cada sincronización.
            $fertilizacion->detalles()->delete();

            // Crear los registros de detalles de fertilizantes en la tabla relacionada
            foreach ($fertilizantesDetalles as $detalle) {
                $fertilizacion->detalles()->create([
                    'fertilizante' => $detalle['nombre'], // Asumo que la columna se llama 'fertilizante'
                    'cantidad' => $detalle['cantidad'],
                ]);
            }

            Log::info('Registro de Fertilización y sus detalles sincronizados con éxito.', ['visita_id' => $data['visita_id']]);
            return response()->json(['message' => 'Fertilización y detalles sincronizados con éxito.']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("Error de validación al sincronizar Fertilización: " . $e->getMessage(), ['errors' => $e->errors(), 'data' => $data]);
            return response()->json(['message' => 'Error de validación.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error("Error inesperado al sincronizar Fertilización: " . $e->getMessage(), ['data' => $data, 'trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Error interno del servidor al sincronizar Fertilización.', 'error' => $e->getMessage()], 500);
        }
    }


}
