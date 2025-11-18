<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log; // Para registrar errores

use App\Models\Fertilizacion;
use App\Models\Visita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FertilizacionController extends Controller
{
    public function create(Request $request)
        {
            $visita_id = $request->query('visita_id');
            // Asegúrate de cargar las relaciones anidadas correctamente para la vista
            $visita = Visita::with([
                'proveedor',
                'plantacion',
                'areas', // Para mostrar la información del área
                'fertilizaciones.fertilizantes' // Para mostrar los fertilizantes registrados
            ])->findOrFail($visita_id);

            return view('fertilizaciones.create', compact('visita'));
        }

        public function store(Request $request)
        {
            $data = $request->validate([
                'visita_id' => 'required|exists:visitas,id',
                'fecha_fertilizacion' => 'required|date',
                'fertilizantes' => 'required|array|min:1',
                'fertilizantes.*.nombre' => 'required|string|max:255',
                'fertilizantes.*.otro_fertilizante' => 'nullable|string|max:255|required_if:fertilizantes.*.nombre,otro',
                'fertilizantes.*.cantidad' => 'required|numeric|min:0',
                'fertilizantes.*.fecha_aplicacion' => 'required|date',
                'fertilizantes.*.unidad_medida' => 'required|string',
            ], [
                'fertilizantes.*.nombre.required' => 'El nombre del fertilizante es obligatorio para cada entrada.',
                'fertilizantes.*.otro_fertilizante.required_if' => 'Debe especificar el nombre del fertilizante cuando selecciona "Otro".',
                'fertilizantes.*.cantidad.required' => 'La cantidad del fertilizante es obligatoria para cada entrada.',
                'fertilizantes.*.cantidad.numeric' => 'La cantidad debe ser un número.',
                'fertilizantes.*.cantidad.min' => 'La cantidad debe ser al menos :min.',
                'fertilizantes.*.fecha_aplicacion.required' => 'La fecha de aplicación es obligatoria para cada fertilizante.',
                'fertilizantes.*.fecha_aplicacion.date' => 'La fecha de aplicación debe ser una fecha válida.',
                'fertilizantes.*.unidad_medida.required' => 'La unidad de medida es obligatoria para cada fertilizante.',
                'fertilizantes.*.unidad_medida.in' => 'La unidad de medida seleccionada no es válida.',
            ]);
        
            DB::beginTransaction();
            try {
                $fertilizacion = Fertilizacion::create([
                    'visita_id' => $data['visita_id'],
                    'fecha_fertilizacion' => $data['fecha_fertilizacion'],
                ]);
        
                foreach ($data['fertilizantes'] as $fertil) {
                    // Determinar el nombre del fertilizante a guardar
                    $nombreFertilizante = ($fertil['nombre'] === 'otro' && !empty($fertil['otro_fertilizante'])) 
                        ? $fertil['otro_fertilizante'] 
                        : $fertil['nombre'];
        
                    $fertilizacion->fertilizantes()->create([
                        'fertilizante' => $nombreFertilizante,
                        'cantidad' => $fertil['cantidad'],
                        'fecha_aplicacion' => $fertil['fecha_aplicacion'],
                        'unidad_medida' => $fertil['unidad_medida'],
                    ]);
                }
        
                // Actualizar estado de la visita si es necesario
                $visita = Visita::find($data['visita_id']);
                if ($visita && $visita->estado === 'pendiente') {
                    $visita->estado = 'en_ejecucion';
                    $visita->save();
                }
        
                DB::commit();
        
                return redirect()->route('polinizaciones.create', ['visita_id' => $data['visita_id']])
                    ->with('success', '✅ Fertilización registrada exitosamente. Continúa con la polinización.');
        
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Error al guardar fertilización: " . $e->getMessage(), [
                    'trace' => $e->getTraceAsString(), 
                    'request' => $request->all()
                ]);
                
                return redirect()->back()
                    ->with('error', 'Ocurrió un error al guardar la fertilización: ' . $e->getMessage())
                    ->withInput();
            }
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
                    'fecha_aplicacion' => $detalle['fecha_aplicacion'] ?? null,
                    'unidad_medida' => $detalle['unidad_medida'] ?? null
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
