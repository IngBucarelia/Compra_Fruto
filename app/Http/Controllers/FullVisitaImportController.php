<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\VisitasCompletasImport;

class FullVisitaImportController extends Controller
{
    public function showForm()
    {
        return view('visitas.full-import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new VisitasCompletasImport, $request->file('file'));
            return back()->with('success', '¡Todos los componentes se importaron correctamente!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}