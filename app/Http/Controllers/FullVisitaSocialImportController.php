<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\VisitasSocialCompletasImport;

class FullVisitaSocialImportController extends Controller
{
    public function showForm()
    {
        return view('visitas_social.full-import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new VisitasSocialCompletasImport, $request->file('file'));
            return back()->with('success', '✅ ¡Todos los componentes sociales se importaron correctamente!');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Error al procesar el archivo: ' . $e->getMessage());
        }
    }
}
