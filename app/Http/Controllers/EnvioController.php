<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use App\Models\EnvioEvidencia;
use App\Models\Planificacion;
use App\Models\Proveedor;
use App\Models\Plantacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class EnvioController extends Controller
{
    public function index()
    {
        $envios = Envio::with(['proveedor','plantacion','tecnico'])->latest()->paginate(12);
        return view('envios.index', compact('envios'));
    }

    public function create()
    {
        $proveedores = Proveedor::all();
        $plantaciones = Plantacion::all();
        $tecnicos = \App\Models\User::whereIn('rol', [2,3])->get(); // ajusta roles si corresponde
        return view('envios.create', compact('proveedores','plantaciones','tecnicos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proveedor_id' => 'required|exists:proveedores,id',
            'plantacion_id' => 'required|exists:plantaciones,id',
            'tecnico_id' => 'nullable|exists:users,id',
            'fecha_envio' => 'required|date',
            'descripcion_envio' => 'required|string|max:2000',
        ]);

        $envio = Envio::create($request->only([
            'proveedor_id','plantacion_id','tecnico_id','fecha_envio','descripcion_envio'
        ]) + ['estado' => 'planificado']);

        return redirect()->route('envios.index', $envio->id)->with('success', 'Envío planificado correctamente.');
    }

    public function show(Envio $envio)
    {
        $envio->load(['proveedor','plantacion','tecnico','evidencias']);
        return view('envios.show', compact('envio'));
    }

    public function edit(Envio $envio)
    {
        $proveedores = Proveedor::all();
        $plantaciones = Plantacion::all();
        $tecnicos = \App\Models\User::whereIn('rol', [2,3])->get();
        return view('envios.edit', compact('envio','proveedores','plantaciones','tecnicos'));
    }

    public function update(Request $request, Envio $envio)
    {
        $request->validate([
            'proveedor_id' => 'required|exists:proveedores,id',
            'plantacion_id' => 'required|exists:plantaciones,id',
            'tecnico_id' => 'nullable|exists:users,id',
            'fecha_envio' => 'required|date',
            'descripcion_envio' => 'required|string|max:2000',
        ]);

        $envio->update($request->only(['proveedor_id','plantacion_id','tecnico_id','fecha_envio','descripcion_envio']));

        return redirect()->route('envios.show', $envio->id)->with('success', 'Envío actualizado.');
    }

    public function destroy(Envio $envio)
    {
        $envio->delete();
        return redirect()->route('envios.index')->with('success', 'Envío eliminado.');
    }

    // Cambiar a en_ejecucion
    public function ejecutar(Request $request, Envio $envio)
    {
        $envio->update(['estado' => 'en_ejecucion']);
        return redirect()->route('envios.show', $envio->id)->with('success', 'Envío marcado como en ejecución.');
    }

    // Completar: recibir comentarios, firma en dataURL y marcar completado
    public function completar(Request $request, Envio $envio)
{
    try {
        // Validar los campos
        $request->validate([
            'comentarios_finales' => 'nullable|string',
            'firma_envio' => 'required|string',
        ]);

        // Procesar firma
        $firmaData = $request->firma_envio;
        if (preg_match('/^data:image\/(\w+);base64,/', $firmaData, $type)) {
            $firmaData = substr($firmaData, strpos($firmaData, ',') + 1);
            $type = strtolower($type[1]); // png, jpg, etc.

            $firmaData = base64_decode($firmaData);
            $fileName = 'firma_envio_' . time() . '.' . $type;
            $filePath = 'storage/firmas/' . $fileName;
            $fullPath = public_path($filePath);

            if (!file_exists(dirname($fullPath))) {
                mkdir(dirname($fullPath), 0777, true);
            }

            file_put_contents($fullPath, $firmaData);
            $envio->firma_envio = $filePath;
        }

        // Actualizar envío
        $envio->comentarios_finales = $request->comentarios_finales;
        $envio->estado = 'completado';
        $envio->save();

        // ✅ Respuesta JSON
        return response()->json([
            'message' => 'El envío ha sido completado exitosamente.',
            'envio' => $envio,
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error al completar el envío.',
            'error' => $e->getMessage()
        ], 500);
    }
}


    // Subida de evidencias (múltiples archivos)
    public function uploadEvidencias(Request $request, Envio $envio)
        {
            try {
                if ($request->hasFile('archivos')) {
                    $urls = [];

                    foreach ($request->file('archivos') as $archivo) {
                        $path = $archivo->store('public/envios'); // Guarda en storage/app/public/envios
                        $publicUrl = asset(str_replace('public', 'storage', $path));

                        $envio->evidencias()->create([
                            'archivo' => str_replace('public', 'storage', $path),
                        ]);

                        $urls[] = $publicUrl;
                    }

                    return response()->json([
                        'success' => true,
                        'message' => 'Imágenes subidas correctamente.',
                        'urls' => $urls
                    ]);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró ningún archivo para subir.'
                ], 400);

            } catch (\Throwable $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error interno: ' . $e->getMessage()
                ], 500);
            }
        }


    // Helper: guardar dataURL (base64) como archivo en storage/public
    protected function saveDataUrlImage(string $dataUrl, string $targetPath)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $dataUrl, $type)) {
            $data = substr($dataUrl, strpos($dataUrl, ',') + 1);
            $data = base64_decode($data);
            $fullPath = 'public/' . $targetPath;
            Storage::put($fullPath, $data);
            return str_replace('public/', 'storage/', $fullPath); // ruta usable con asset()
        }
        throw new \Exception('Formato de firma no reconocido');
    }

public function getPlantacionesByProveedor($proveedorId)
{
    try {
        // OJO: aquí usamos id_proveedor en lugar de proveedor_id
        $plantaciones = Plantacion::where('id_proveedor', $proveedorId)
            ->select('id', 'nombre', 'municipio')
            ->orderBy('nombre')
            ->get();

        return response()->json($plantaciones);
    } catch (\Exception $e) {
        Log::error("Error cargando plantaciones del proveedor {$proveedorId}: " . $e->getMessage());
        return response()->json(['error' => 'No se pudieron obtener las plantaciones'], 500);
    }
}



}
