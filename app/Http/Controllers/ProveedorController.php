<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use App\Models\Plantacion;
use App\Imports\ProveedoresImport;
use Maatwebsite\Excel\Facades\Excel;



class ProveedorController extends Controller
{
    public function index(Request $request)
        {
            $buscar = $request->input('buscar');

            $proveedores = \App\Models\Proveedor::when($buscar, function ($query, $buscar) {
                    return $query->where('proveedor_nombre', 'like', "%{$buscar}%")
                                ->orWhere('nit', 'like', "%{$buscar}%");
                })
                ->orderBy('id', 'desc')
                ->paginate(10)
                ->withQueryString(); // Esto mantiene el filtro al cambiar de página

            return view('proveedores.index', compact('proveedores', 'buscar'));
        }

    public function show(Proveedor $proveedor)
    {
        return view('proveedores.show', compact('proveedor'));
    }

    public function create()
    {
        return view('proveedores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'proveedor_nombre' => 'required',
            'nit' => 'required|numeric',
        ]);

        Proveedor::create($request->all() + ['dia_creado' => now()]);

        return redirect()->route('proveedores.index');
    }

    public function edit(Proveedor $proveedor)
    {
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'proveedor_nombre' => 'required|string|max:255',
            'nit' => 'required|numeric|unique:proveedores,nit,' . $proveedor->id,
        ]);

        $proveedor->update($request->only('proveedor_nombre', 'nit'));

        return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();

        return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado correctamente.');
    }

    public function plantacionesIndex($id)
            {
                $proveedor = Proveedor::findOrFail($id);
                $plantaciones = Plantacion::where('id_proveedor', $id)->paginate(10);

                return view('proveedores.plantaciones', compact('proveedor', 'plantaciones'));
            }
            
            
    public function importForm()
    {
        return view('proveedores.import');
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
            Excel::import(new ProveedoresImport, $request->file('csv_file'));
            return back()->with('status', 'Proveedores importados exitosamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Hubo un problema al importar el archivo. Revisa el formato.']);
        }
    }
}