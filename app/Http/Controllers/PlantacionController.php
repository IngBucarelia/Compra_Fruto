<?php

namespace App\Http\Controllers;

use App\Models\Plantacion;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PlantacionesImport;


class PlantacionController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $plantaciones = \App\Models\Plantacion::with('proveedor')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('nombre', 'like', "%{$buscar}%")
                            ->orWhere('vereda', 'like', "%{$buscar}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString(); // mantiene el filtro al paginar

        return view('plantaciones.index', compact('plantaciones', 'buscar'));
    }

    public function create()
    {
        $proveedores = Proveedor::all();
        return view('plantaciones.create', compact('proveedores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_proveedor' => 'required|exists:proveedores,id',
            'nombre' => 'required|string',
            'vereda' => 'required|string',
            'municipio' => 'required|string',
            'departamento' => 'required|string',
            'geolocalizacion' => 'required|string',
            'dia_creado' => 'required|date',
        ]);

        Plantacion::create($request->all());

        return redirect()->route('plantaciones.index')->with('success', 'Plantación creada correctamente.');
    }

   public function show($id)
        {
            $plantacion = Plantacion::with([
                'proveedor',
                'visitas.tecnico' // <-- Aquí se carga el nombre del técnico
            ])->findOrFail($id);

            return view('plantaciones.show', compact('plantacion'));
        }


    public function edit($id)
    {
        $plantacion = Plantacion::findOrFail($id);
        $proveedores = Proveedor::all();
        return view('plantaciones.edit', compact('plantacion', 'proveedores'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_proveedor' => 'required|exists:proveedores,id',
            'nombre' => 'required|string',
            'municipio' => 'required|string',
            'departamento' => 'required|string',
            'geolocalizacion' => 'required|string',
            'dia_creado' => 'required|date',
        ]);

        $plantacion = Plantacion::findOrFail($id);
        $plantacion->update($request->all());

        return redirect()->route('plantaciones.index')->with('success', 'Plantación actualizada correctamente.');
    }

    public function destroy($id)
    {
        $plantacion = Plantacion::findOrFail($id);
        $plantacion->delete();

        return redirect()->route('plantaciones.index')->with('success', 'Plantación eliminada.');
    }
    
    public function importForm()
    {
        return view('plantaciones.import');
    }

    /**
     * Procesa la importación del archivo CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ]);

        try {
            Excel::import(new PlantacionesImport, $request->file('csv_file'));
            return back()->with('status', 'Plantaciones importadas exitosamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Hubo un problema al importar el archivo. Revisa el formato y el ID de proveedor.']);
        }
    }
}
