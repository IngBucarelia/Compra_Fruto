<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\VisitasImport;
use Maatwebsite\Excel\Facades\Excel;

class VisitaImportController extends Controller
{
    public function showForm()
    {
        return view('visitas.import');
    }



    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls'
            ]);
    
            Excel::import(new VisitasImport, $request->file('file'));
    
            return redirect()->back()->with('success', '✅ Archivo cargado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Error al procesar el archivo: ' . $e->getMessage());
        }
    }

}
