<?php

namespace App\Http\Controllers;

use App\Models\DatoPredioSocial;
use App\Models\DatosPersonalesSocial;
use App\Models\FuerzaLaboral;
use App\Models\MiembroHogar;
use App\Models\VisitaSocial;
use Illuminate\Http\Request;

class FuerzaLaboralController extends Controller
{
    public function index($visita)
    {
        $visita = VisitaSocial::findOrFail($visita);
        $fuerzas = FuerzaLaboral::where('visita_social_id', $visita->id)->get(); // Cambié a $fuerzas (plural)
        $datosPersonales = DatosPersonalesSocial::where('visita_social_id', $visita->id)->first();
        $miembros = MiembroHogar::where('visita_social_id', $visita->id)->get();
        $datosPredio = DatoPredioSocial::where('visita_social_id', $visita->id)->get();

        return view('fuerza_laboral.index', compact(
            'visita', 
            'fuerzas', // Cambié a plural
            'datosPersonales',
            'miembros',
            'datosPredio'
        ));
    }

    public function create($visita)
    {
        $visita = VisitaSocial::findOrFail($visita);
        return view('fuerza_laboral.create', compact('visita'));
    }

    public function store(Request $request, $visita)
    {
        $validated = $request->validate([
            'forma_contratacion' => 'nullable|array',
            'num_trabajadores' => 'nullable|integer',
            'num_hombres' => 'nullable|integer',
            'num_mujeres' => 'nullable|integer',
            'contrato_formal' => 'nullable|string',
            'seguridad_social' => 'nullable|string',
            'tipo_contrato' => 'nullable|string',
            'contrato_firmado' => 'nullable|string',
            'sg_sst' => 'nullable|string',
            'examenes_medicos' => 'nullable|string',
            'trabajadores_migrantes' => 'nullable|string',
            'comprobantes_pago' => 'nullable|string',
            'dotacion' => 'nullable|string',
        ]);

        $validated['visita_social_id'] = $visita;
        FuerzaLaboral::create($validated);

        return redirect()->route('fuerza_laboral.index', $visita)
            ->with('success', 'Fuerza laboral registrada correctamente.');
    }

    public function edit($visita, $id)
    {
        $visita = VisitaSocial::findOrFail($visita);
        $fuerza = FuerzaLaboral::findOrFail($id);

        return view('fuerza_laboral.edit', compact('visita', 'fuerza'));
    }

    public function update(Request $request, $visita, $id)
    {
        $fuerza = FuerzaLaboral::findOrFail($id);

        $validated = $request->validate([
            'forma_contratacion' => 'nullable|array',
            'num_trabajadores' => 'nullable|integer',
            'num_hombres' => 'nullable|integer',
            'num_mujeres' => 'nullable|integer',
            'contrato_formal' => 'nullable|string',
            'seguridad_social' => 'nullable|string',
            'tipo_contrato' => 'nullable|string',
            'contrato_firmado' => 'nullable|string',
            'sg_sst' => 'nullable|string',
            'examenes_medicos' => 'nullable|string',
            'trabajadores_migrantes' => 'nullable|string',
            'comprobantes_pago' => 'nullable|string',
            'dotacion' => 'nullable|string',
        ]);

        $fuerza->update($validated);

        return redirect()->route('fuerza_laboral.index', $visita)
            ->with('success', 'Fuerza laboral actualizada correctamente.');
    }

    public function show($visita, $id)
    {
        $visita = VisitaSocial::findOrFail($visita);
        $fuerza = FuerzaLaboral::findOrFail($id);

        return view('fuerza_laboral.show', compact('visita', 'fuerza'));
    }

    public function destroy($visita, $id)
    {
        $fuerza = FuerzaLaboral::findOrFail($id);
        $fuerza->delete();

        return redirect()->route('fuerza_laboral.index', $visita)
            ->with('success', 'Registro de fuerza laboral eliminado correctamente.');
    }


}
