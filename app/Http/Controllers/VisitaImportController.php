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
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new VisitasImport, $request->file('file'));

        return back()->with('success', 'Visitas importadas correctamente.');
    }
}
