<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\DatosPersonalesSocial;
use App\Models\MiembroHogar;
use App\Models\DatoPredioSocial;
use App\Models\FuerzaLaboral;
use App\Models\OrganizacionSocial;
use App\Models\CierreVisitaSocial;

class SocialSyncController extends Controller
{
    // Mapeo común de campo (visita_id de la app a visita_social_id del modelo)
    private const ID_MAPPING = ['visita_id' => 'visita_social_id'];

    /**
     * Sincronizar datos personales sociales (soporta lote o registro único).
     */
    public function syncDatosPersonales(Request $request)
    {
        $rules = [
            'visita_social_id' => 'required|exists:visita_socials,id',
            'telefono' => 'nullable|string|max:20',
            'sexo' => 'nullable|string|max:10',
            'fecha_nacimiento' => 'nullable|date',
            'rnp' => 'nullable|string|max:50',
            'fedepalma' => 'nullable|string|max:50',
            'alfabetizado' => 'nullable|boolean',
            'nivel_estudio' => 'nullable|string|max:100',
            'grupo_poblacional' => 'nullable|string|max:100',
            'anios_palmicultura' => 'nullable|integer',
            'regimen_salud' => 'nullable|string|max:100',
            'reside_predio' => 'nullable|boolean',
            'administra_cultivo' => 'nullable|boolean',
            'internet' => 'nullable|string|max:40',
            'red_social' => 'nullable|string|max:100',
            'numero_rnp' => 'nullable|string|max:50',
            'oferta_mercantil' => 'nullable|string|max:50',
            'hace_cuanto' => 'nullable|string|max:50',           
            
        ];

        return $this->handleSyncRequest($request, DatosPersonalesSocial::class, $rules);
    }

    /**
     * Sincronizar miembros del hogar (soporta lote o registro único).
     */
    public function syncMiembrosHogar(Request $request)
    {
        $rules = [
            'visita_social_id' => 'required|exists:visita_socials,id',
            'nombre' => 'required|string|max:255',
            'documento' => 'nullable|string|max:50',
            'sexo' => 'nullable|string|max:10',
            'parentezco' => 'nullable|string|max:100',
            'reside_predio' => 'nullable|boolean',
            'nivel_estudio' => 'nullable|string|max:100',
            'participa_labores' => 'nullable|boolean'
        ];

        return $this->handleSyncRequest($request, MiembroHogar::class, $rules);
    }

    /**
     * Sincronizar datos del predio social (soporta lote o registro único).
     */
    public function syncDatosPredio(Request $request)
    {
        $rules = [
            'visita_social_id' => 'required|exists:visita_socials,id',
            'nombre_finca' => 'nullable|string|max:255',
            'forma_tenencia' => 'nullable|string|max:100',
            'municipio' => 'nullable|string|max:100',
            'vereda' => 'nullable|string|max:100',
            'registrado_ica' => 'nullable|boolean',
            'vive_predio' => 'nullable|boolean',
            'infraestructura_predio' => 'nullable|string|max:255',
            'infraestructura_vial' => 'nullable|array',
            'servicios_publicos' => 'nullable|array',
            'observaciones' => 'nullable|string'
        ];
        $jsonFields = ['infraestructura_vial', 'servicios_publicos'];

        return $this->handleSyncRequest($request, DatoPredioSocial::class, $rules, $jsonFields);
    }

    /**
     * Sincronizar fuerza laboral social (soporta lote o registro único).
     */
    public function syncFuerzaLaboral(Request $request)
    {
        $rules = [
            'visita_social_id' => 'required|exists:visita_socials,id',
            'num_trabajadores' => 'nullable|integer',
            'num_hombres' => 'nullable|integer',
            'num_mujeres' => 'nullable|integer',
            'forma_contratacion' => 'nullable|array',
            'contrato_formal' => 'nullable|boolean',
            'seguridad_social' => 'nullable|boolean',
            'sg_sst' => 'nullable|boolean',
            'dotacion' => 'nullable|boolean',
            'observaciones' => 'nullable|string'
        ];
        $jsonFields = ['forma_contratacion'];

        return $this->handleSyncRequest($request, FuerzaLaboral::class, $rules, $jsonFields);
    }

    /**
     * Sincronizar organización social (soporta lote o registro único).
     */
    public function syncOrganizacion(Request $request)
    {
        $rules = [
            'visita_id' => 'required|exists:visita_socials,id',
            'pertenece_jac' => 'nullable|boolean',
            'pertenece_asociacion' => 'nullable|boolean',
            'nombre_asociacion' => 'nullable|string|max:255',
            'participa_otras_organizaciones' => 'nullable|boolean',
            'cargos_directivos' => 'nullable|string|max:255',
            'frecuencia_participacion' => 'nullable|string|max:100',
            'beneficios_participacion' => 'nullable|array',
            'descripcion_cargos' => 'nullable|string',
            'observaciones' => 'nullable|string'
        ];
        $jsonFields = ['beneficios_participacion'];
        // Nota: La organización usa directamente 'visita_id' en sus reglas, 
        // pero el mapeo interno lo maneja para la columna 'visita_social_id'.

        return $this->handleSyncRequest($request, OrganizacionSocial::class, $rules, $jsonFields);
    }

    /**
     * Sincronizar cierre de visita social (soporta lote o registro único).
     */
    /**
 * Sincronizar cierre de visita social (soporta lote o registro único).
 */
public function syncCierreVisita(Request $request)
{
    $rules = [
        'visita_social_id' => 'required|exists:visita_socials,id',
        'fecha_cierre' => 'nullable|date',
        'estado_visita' => 'nullable|string|max:50',
        'firma_responsable' => 'nullable|string',
        'firma_recibe' => 'nullable|string',
        'firma_testigo' => 'nullable|string',
        'imagenes' => 'nullable|array',
        'observaciones_finales' => 'nullable|string',
        'recomendaciones' => 'nullable|string',
        'timestamp' => 'nullable|date'
    ];
    $jsonFields = ['imagenes'];

    // Llamar al método handleSyncRequest pero con callback para actualizar estado
    return $this->handleSyncRequestWithStatusUpdate($request, CierreVisitaSocial::class, $rules, $jsonFields);
}

/**
 * Manejar sincronización con actualización de estado de visita
 */
protected function handleSyncRequestWithStatusUpdate(Request $request, string $modelClass, array $rules, array $jsonFields = [])
{
    DB::beginTransaction();
    
    try {
        $data = $request->all();
        
        // 1. Armonizar la estructura de datos
        if (isset($data['submissions']) && is_array($data['submissions'])) {
            $submissions = $data['submissions'];
        } else if (is_array($data) && array_keys($data) === range(0, count($data) - 1) && count($data) > 0) {
            $submissions = $data;
        } else {
            $submissions = [$data];
        }
        
        $results = [];
        $sincronizados = 0;
        $entityName = class_basename($modelClass);

        // 2. Procesar cada registro
        foreach ($submissions as $submission) {
            $result = $this->processSubmission($submission, $modelClass, $rules, $jsonFields, self::ID_MAPPING);
            $results[] = $result;
            
            if ($result['success']) {
                $sincronizados++;
                
                // ✅ ACTUALIZAR ESTADO DE LA VISITA CUANDO EL CIERRE SE SINCRONIZA EXITOSAMENTE
                $this->actualizarEstadoVisita($submission['visita_social_id'] ?? $submission['visita_id'] ?? null);
            }
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => "Datos de {$entityName} sincronizados correctamente",
            'sincronizados' => $sincronizados,
            'total' => count($results),
            'results' => $results
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Error en sincronización de {$modelClass}: " . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => "Error al sincronizar datos de {$entityName}: " . $e->getMessage()
        ], 500);
    }
}

/**
 * Actualizar estado de la visita a "finalizado"
 */
/**
 * Actualizar estado de la visita a "finalizado" usando la ruta existente
 */
private function actualizarEstadoVisita($visitaId)
{
    try {
        if (!$visitaId) {
            Log::warning('No se pudo actualizar estado: ID de visita no proporcionado');
            return false;
        }

        // Buscar la visita usando Route Model Binding como en tu método updateStatus
        $visita = \App\Models\VisitaSocial::find($visitaId);
        
        if (!$visita) {
            Log::warning("No se encontró visita con ID: {$visitaId} para actualizar estado");
            return false;
        }

        Log::info("🔍 Estado actual de visita {$visitaId}: {$visita->estado}");

        // Usar el mismo método que usas en tu VisitaSocialController
        $visita->estado = 'finalizado';
        $visita->save();

        Log::info("✅ Estado de visita {$visitaId} actualizado a 'finalizado'");

        return true;

    } catch (\Exception $e) {
        Log::error("Error actualizando estado de visita {$visitaId}: " . $e->getMessage());
        Log::error($e->getTraceAsString());
        return false;
    }
}  /**
     * Lógica central de sincronización por lotes con manejo de transacciones.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $modelClass Clase del modelo a sincronizar.
     * @param array $rules Reglas de validación.
     * @param array $jsonFields Campos que deben ser codificados a JSON (si son arrays).
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleSyncRequest(Request $request, string $modelClass, array $rules, array $jsonFields = [])
    {
        DB::beginTransaction();
        
        try {
            $data = $request->all();
            
            // 1. Armonizar la estructura de datos: acepta 'submissions', array simple o registro único.
            if (isset($data['submissions']) && is_array($data['submissions'])) {
                $submissions = $data['submissions'];
            } else if (is_array($data) && array_keys($data) === range(0, count($data) - 1) && count($data) > 0) {
                // Es un array de registros (como MiembrosHogar o CierreVisita originales)
                $submissions = $data;
            } else {
                // Es un registro único
                $submissions = [$data];
            }
            
            $results = [];
            $sincronizados = 0;
            $entityName = class_basename($modelClass);

            // 2. Procesar cada registro
            foreach ($submissions as $submission) {
                $result = $this->processSubmission($submission, $modelClass, $rules, $jsonFields, self::ID_MAPPING);
                $results[] = $result;
                
                if ($result['success']) {
                    $sincronizados++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Datos de {$entityName} sincronizados correctamente",
                'sincronizados' => $sincronizados,
                'total' => count($results),
                'results' => $results
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error en sincronización de {$modelClass}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Error al sincronizar datos de {$entityName}: " . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lógica interna para procesar un solo registro de datos.
     *
     * @param array $data Los datos del registro.
     * @param string $modelClass Clase del modelo a sincronizar.
     * @param array $rules Reglas de validación.
     * @param array $jsonFields Campos que deben ser codificados a JSON (si son arrays).
     * @param array $idMapping Mapeo de campos de ID (source => target).
     * @return array Resultado del proceso (success, id, local_id, message/error).
     */
    private function processSubmission(array $data, string $modelClass, array $rules, array $jsonFields, array $idMapping): array
    {
        try {
            // 1. Validación
            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                $entityName = class_basename($modelClass);
                Log::warning("Validación fallida {$entityName}: " . $validator->errors()->first());
                return [
                    'success' => false,
                    'error' => 'Validación fallida: ' . $validator->errors()->first(),
                    'local_id' => $data['local_id'] ?? null
                ];
            }

            // 2. Mapeo de ID (visita_id -> visita_social_id)
            foreach ($idMapping as $source => $target) {
                if (isset($data[$source]) && !isset($data[$target])) {
                    $data[$target] = $data[$source];
                    // No hacemos unset si $target está en las reglas de validación
                }
            }

            // 3. Buscar o Crear por local_id
            $record = $modelClass::where('local_id', $data['local_id'] ?? null)->first();

            if (!$record) {
                $record = new $modelClass();
            }

            // 4. Codificación Array a JSON
            foreach ($jsonFields as $field) {
                if (isset($data[$field]) && is_array($data[$field])) {
                    $data[$field] = json_encode($data[$field]);
                }
            }
            
            // Asegurar que el local_id exista si no se encontró el registro
            if (isset($data['local_id'])) {
                $record->local_id = $data['local_id'];
            }

            // 5. Guardar
            $record->fill($data);
            $record->save();

            Log::info("Registro {$modelClass} guardado: ID " . $record->id);

            return [
                'success' => true,
                'id' => $record->id,
                'local_id' => $data['local_id'] ?? null,
                'message' => "Registro {$modelClass} guardado correctamente"
            ];

        } catch (\Exception $e) {
            Log::error("Error procesando {$modelClass}: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'local_id' => $data['local_id'] ?? null
            ];
        }
    }
}
