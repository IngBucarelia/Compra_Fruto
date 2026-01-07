<?php

namespace App\Http\Controllers;

use App\Models\CierreVisitaAmbiental;
use App\Models\VisitaAmbiental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CierreVisitaAmbientalController extends Controller
{
    public function create(int $visita_ambiental_id)
    {
        $visita = VisitaAmbiental::with([
            'proveedor',
            'plantacion',
            'cierreVisitaAmbiental',
            'aguaCaptacionLegal',
            'aguaUsoEficiente',
            'sueloConservacion',
            'energiaManejo',
            'gobernanzaHidrica',
            'emisionesGei',
            'residuosManejo',
            'sustanciasQuimicasBiologicas',
            'vertimientoManejo',
            'plantacionHmp',
            'plantacionAvc',
            'plantacionEcosistema'
        ])->findOrFail($visita_ambiental_id);

        $cierreExistente = CierreVisitaAmbiental::where('visita_ambiental_id', $visita_ambiental_id)->first();
        if ($cierreExistente) {
            return redirect()->route('visitasAmbientales.show', $visita->id)
                ->with('info', '⚠️ Esta visita ambiental ya fue finalizada.');
        }

        return view('cierre_visitas_ambiental.create', compact('visita', 'cierreExistente'));
    }

    public function store(Request $request)
    {
        Log::info('Iniciando store de cierre visita ambiental');
        
        try {
            // Si los datos vienen como JSON, debemos obtenerlos así
            if ($request->isJson()) {
                $datos = $request->json()->all();
            } else {
                $datos = $request->all();
            }

            Log::info('Datos recibidos:', ['keys' => array_keys($datos)]);

            // Validación manual para JSON
            $validator = Validator::make($datos, [
                'visita_ambiental_id' => 'required|exists:visita_ambientals,id',
                'fecha_cierre' => 'required|date',
                'estado_visita' => 'required|string',
                'firma_responsable' => 'required|string',
                'firma_recibe' => 'required|string',
            ]);

            if ($validator->fails()) {
                Log::error('Error validación:', $validator->errors()->toArray());
                return response()->json([
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            Log::info('Validación pasada, procesando firmas...');

            // Guardar firmas como archivos
            $firmaResponsable = null;
            if (isset($datos['firma_responsable']) && !empty($datos['firma_responsable'])) {
                $firmaResponsable = $this->saveBase64Image($datos['firma_responsable'], 'firmas_ambiental');
                Log::info('Firma responsable guardada');
            }

            $firmaRecibe = null;
            if (isset($datos['firma_recibe']) && !empty($datos['firma_recibe'])) {
                $firmaRecibe = $this->saveBase64Image($datos['firma_recibe'], 'firmas_ambiental');
                Log::info('Firma recibe guardada');
            }

            $firmaTestigo = null;
            if (isset($datos['firma_testigo']) && !empty($datos['firma_testigo'])) {
                $firmaTestigo = $this->saveBase64Image($datos['firma_testigo'], 'firmas_ambiental');
                Log::info('Firma testigo guardada');
            }

            // Guardar imágenes (array de base64)
            $imagenesPaths = [];
            if (isset($datos['imagenes']) && is_array($datos['imagenes'])) {
                Log::info('Procesando imágenes:', ['count' => count($datos['imagenes'])]);
                foreach ($datos['imagenes'] as $index => $img64) {
                    if (!empty($img64)) {
                        $imagenesPaths[] = $this->saveBase64Image($img64, 'visitas_ambiental');
                    }
                }
            }

            Log::info('Creando registro de cierre en BD...');

            $cierre = CierreVisitaAmbiental::create([
                'visita_ambiental_id'  => $datos['visita_ambiental_id'],
                'fecha_cierre'         => $datos['fecha_cierre'],
                'estado_visita'        => $datos['estado_visita'],
                'observaciones_finales' => $datos['observaciones_finales'] ?? null,
                'recomendaciones'      => $datos['recomendaciones'] ?? null,
                'firma_responsable'    => $firmaResponsable,
                'firma_recibe'         => $firmaRecibe,
                'firma_testigo'        => $firmaTestigo,
                'finalizada_en'        => $datos['finalizada_en'] ?? now(),
                'imagenes'             => $imagenesPaths,
            ]);

            Log::info('Cierre creado exitosamente:', ['id' => $cierre->id]);

            // ✅ ACTUALIZAR ESTADO DE LA VISITA A "finalizado"
            Log::info('Actualizando estado de la visita a finalizado...');
            
            // Obtener la visita
            $visita = VisitaAmbiental::find($datos['visita_ambiental_id']);
            
            if ($visita) {
                // Actualizar estado directamente
                $visita->estado = 'finalizada';
                $visita->save();
                
                Log::info('Estado de visita actualizado a finalizado:', ['visita_id' => $visita->id]);
            } else {
                Log::warning('No se encontró la visita para actualizar estado:', ['visita_id' => $datos['visita_ambiental_id']]);
            }

            return response()->json([
                'message' => 'Cierre de visita ambiental guardado correctamente y visita finalizada',
                'data' => $cierre,
                'visita_actualizada' => $visita ?? null
            ], 201);

        } catch (\Exception $e) {
            Log::error("Error en cierre de visita ambiental: ".$e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'message' => 'Error al guardar el cierre de visita ambiental: ' . $e->getMessage()
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