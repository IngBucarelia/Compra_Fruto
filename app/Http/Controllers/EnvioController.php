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
use Barryvdh\DomPDF\Facade\Pdf;

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
    

            // Subida de evidencias (múltiples archivos)
            public function stage(Envio $envio)
    {
        $envio->load(['proveedor', 'plantacion', 'tecnico']);
        return view('envios.stage', compact('envio'));
    }

    // Acción que marca "en_ejecucion" y redirige al show
    public function comenzar(Request $request, Envio $envio)
    {
        $envio->update(['estado' => 'en_ejecucion']);
        return redirect()->route('envios.show', $envio->id)->with('success', 'Envío comenzado y en ejecución.');
    }

    // Subida de evidencias (sin cambios importantes, guarda en public/storage/envios)
    public function uploadEvidencias(Request $request, Envio $envio)
    {
        try {
            if ($request->hasFile('archivos')) {
                $urls = [];
                $rutaPublica = public_path('storage/envios');

                if (!file_exists($rutaPublica)) {
                    mkdir($rutaPublica, 0777, true);
                }

                foreach ($request->file('archivos') as $archivo) {
                    $nombreArchivo = uniqid('envio_') . '.' . $archivo->getClientOriginalExtension();
                    $archivo->move($rutaPublica, $nombreArchivo);
                    $publicPath = 'storage/envios/' . $nombreArchivo;

                    $envio->evidencias()->create([
                        'archivo' => $publicPath,
                    ]);

                    $urls[] = asset($publicPath);
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
            Log::error('Error uploadEvidencias: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno: ' . $e->getMessage()
            ], 500);
        }
    }

    // Completar: guarda comentarios y DOS firmas (entrega + recibe). Ambas opcionales, pero una puede ser obligatoria según tu flujo
    public function completar(Request $request, Envio $envio)
    {
        try {
            // Validaciones: firma_entrega o firma_recibe pueden venir
            $request->validate([
                'comentarios_finales' => 'nullable|string',
                'firma_entrega' => 'nullable|string', // dataURL
                'firma_recibe' => 'nullable|string',  // dataURL
            ]);

            // Procesar firma_entrega si viene
            if ($request->filled('firma_entrega') && preg_match('/^data:image\/(\w+);base64,/', $request->firma_entrega)) {
                $pathEntrega = $this->saveDataUrlImage($request->firma_entrega, 'firmas/entrega_' . $envio->id . '_' . time() . '.png');
                $envio->firma_entrega = $pathEntrega;
            }

            // Procesar firma_recibe si viene
            if ($request->filled('firma_recibe') && preg_match('/^data:image\/(\w+);base64,/', $request->firma_recibe)) {
                $pathRecibe = $this->saveDataUrlImage($request->firma_recibe, 'firmas/recibe_' . $envio->id . '_' . time() . '.png');
                $envio->firma_recibe = $pathRecibe;
            }

            // Actualizar comentarios y estado final
            $envio->comentarios_finales = $request->comentarios_finales;
            // Si quieres que completar marque 'completado' directo (o depende de otra acción)
            $envio->estado = 'completado';
            $envio->save();

            return response()->json([
                'success' => true,
                'message' => 'Envío completado correctamente.',
                'envio' => $envio
            ]);
        } catch (\Throwable $e) {
            Log::error('Error completar envio: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al completar el envío: ' . $e->getMessage()
            ], 500);
        }
    }

    // Helper para guardar dataURL base64 como archivo en public/storage/...
    protected function saveDataUrlImage(string $dataUrl, string $targetRelativePath)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $dataUrl, $type)) {
            $data = substr($dataUrl, strpos($dataUrl, ',') + 1);
            $data = base64_decode($data);

            $fullPath = public_path('storage/' . $targetRelativePath);
            $dir = dirname($fullPath);
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }

            file_put_contents($fullPath, $data);
            return 'storage/' . $targetRelativePath;
        }

        throw new \Exception('Formato de imagen no válido.');
    }



    // Helper: guardar dataURL (base64) como archivo en storage/public
    

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

public function generarPDF($id)
{
    $envio = Envio::with('plantacion')->findOrFail($id);

    // Cargar la vista en PDF
    $pdf = Pdf::loadView('envios.pdf', compact('envio'))
        ->setPaper('a4', 'portrait');

    // Descargar el PDF
    return $pdf->download('Envio_'.$envio->id.'.pdf');
}

}
