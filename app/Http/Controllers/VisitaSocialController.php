<?php

namespace App\Http\Controllers;

use App\Models\VisitaSocial;
use App\Models\Proveedor;
use App\Models\Plantacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class VisitaSocialController extends Controller
{
    public function home()
    {
        return view('visitas_social.home', ['user' => Auth::user()]);
    }

    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $visitas = VisitaSocial::with(['proveedor', 'tecnico'])
            ->whereHas('proveedor', fn($q) => $q->where('proveedor_nombre', 'like', "%$buscar%"))
            ->orWhereHas('tecnico', fn($q) => $q->where('name', 'like', "%$buscar%"))
            ->orWhere('tipo_visita', 'like', "%$buscar%")
            ->orWhere('ubicacion', 'like', "%$buscar%")
            ->latest()
            ->paginate(10);

        return view('visitas_social.index', compact('visitas', 'buscar'));
    }

    public function create()
    {
        $tecnicos = User::where('rol', 3)->get(); // ⚡ ejemplo: rol 3 = técnico social
        $proveedores = Proveedor::all();
        return view('visitas_social.create', compact('tecnicos', 'proveedores'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fecha' => 'required|date',
            'tecnico_campo' => 'required|exists:users,id',
            'proveedor_id' => 'required|exists:proveedores,id',
            'plantacion_id' => 'required|exists:plantaciones,id',
            'ubicacion' => 'required|string',
            'tipo_visita' => 'required|string',
            'recibio_visita' => 'required|string',
        ]);

        $visita = VisitaSocial::create([...$data, 'estado' => 'pendiente']);

        return redirect()->route('visitas_social.index')->with('success', 'Visita Social creada con éxito.');
    }

    public function show($id)
    {
        $visita = VisitaSocial::with(['proveedor', 'tecnico', 'plantacion'])->findOrFail($id);
        return view('visitas_social.show', compact('visita'));
    }

    public function edit($id)
    {
        $visita = VisitaSocial::findOrFail($id);
        $proveedores = Proveedor::all();
        $plantaciones = Plantacion::where('id_proveedor', $visita->proveedor_id)->get();
        $tecnicos = User::where('rol', 3)->get();

        return view('visitas_social.edit', compact('visita', 'proveedores', 'plantaciones', 'tecnicos'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'fecha' => 'required|date',
            'ubicacion' => 'required|string',
            'tecnico_campo' => 'required|exists:users,id',
            'tipo_visita' => 'required|string',
            'proveedor_id' => 'required|exists:proveedores,id',
            'recibio_visita' => 'required|string',
        ]);

        $visita = VisitaSocial::findOrFail($id);
        $visita->update($data);

        return redirect()->route('visitas_social.index')->with('success', 'Visita Social actualizada.');
    }

    public function destroy($id)
    {
        $visita = VisitaSocial::findOrFail($id);
        $visita->delete();

        return redirect()->route('visitas_social.index')->with('success', 'Visita eliminada.');
    }

    public function exportarPDF($id)
    {
        $visita = VisitaSocial::with(['proveedor', 'tecnico', 'plantacion'])->findOrFail($id);
        $pdf = Pdf::loadView('visitas_social.exportar_pdf', compact('visita'));
        return $pdf->download("detalle_visita_social_{$id}.pdf");
    }

    public function exportarExcel($id)
    {
        //return Excel::download(new \App\Exports\VisitaSocialExport($id), "detalle_visita_social_{$id}.xlsx");
    }

    public function updateStatus(Request $request, VisitaSocial $visita)
    {
        $visita->estado = 'finalizado';
        $visita->save();

        return response()->json([
            'message' => 'Estado actualizado a finalizado',
            'visita' => $visita,
        ]);
    }
}
