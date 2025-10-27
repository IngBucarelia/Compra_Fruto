<?php

namespace App\Http\Controllers;

use App\Models\VisitaSocial;
use App\Models\DatosPersonalesSocial;
use Illuminate\Http\Request;

class DatosPersonalesSocialController extends Controller
{
    // Mostrar formulario de creación o datos si ya existen
    public function create($visitaId)
    {
        $visita = VisitaSocial::findOrFail($visitaId);

        // Buscar si ya existe un registro
        $datos = DatosPersonalesSocial::where('visita_social_id', $visitaId)->first();

        if ($datos) {
            // Si ya hay registro, mostrar vista con datos
            return view('datos_personales_social.show', compact('datos', 'visita'));
        }

        // Si no hay registro, mostrar formulario de creación
        return view('datos_personales_social.create', compact('visita'));
    }

    // Guardar nuevo registro
   public function store(Request $request, $visitaId)
{
    $validated = $request->validate([
        'telefono' => 'nullable|string|max:20',
        'sexo' => 'nullable|string',
        'rnp' => 'nullable|string',
        'fedepalma' => 'nullable|string',
        'alfabetizado' => 'nullable|string',
        'nivel_estudio' => 'nullable|string',
        'otras_lineas' => 'nullable|string',
        'fecha_nacimiento' => 'nullable|date',
        'grupo_poblacional' => 'nullable|string',
        'reside_predio' => 'nullable|string',
        'administra_cultivo' => 'nullable|string',
        'supervisa_cultivo' => 'nullable|string',
        'realiza_cultivo' => 'nullable|string',
        'anios_palmicultura' => 'nullable|integer',
        'internet' => 'nullable|string',
        'tipo_persona' => 'nullable|string',
        'red_social' => 'nullable|string',
        'regimen_salud' => 'nullable|string',
        'numero_rnp' => 'nullable|string|max:50',
        'oferta_mercantil' => 'nullable|string|max:50',
        'hace_cuanto' => 'nullable|string|max:50',
    ]);
     if ($request->rnp === 'NO') {
        $validated['numero_rnp'] = null;
    }
    // 🔹 Obtenemos la visita
    $visita = VisitaSocial::with('plantacion')->findOrFail($visitaId);

    // 🔹 Guardamos visita y proveedor
    $validated['visita_social_id'] = $visita->id;
    $validated['proveedor_id'] = $visita->plantacion->id_proveedor;

    DatosPersonalesSocial::create($validated);

    return redirect()->route('miembros_hogar.create', $visitaId)
        ->with('success', 'Datos personales guardados correctamente. Ahora registre los miembros del hogar.');
}



   // Editar registro existente
public function edit($visita, Request $request)
{
    $id = $request->query('id'); // el id lo pasamos como ?id=XX
    $datos = DatosPersonalesSocial::findOrFail($id);
    $visita = $datos->visitaSocial;

    return view('datos_personales_social.edit', compact('datos', 'visita'));
}

// Actualizar registro existente
public function update(Request $request, $visita)
{
    $id = $request->input('id'); // lo recibimos por hidden input
    $datos = DatosPersonalesSocial::findOrFail($id);

    $validated = $request->validate([
        'telefono' => 'nullable|string|max:20',
        'sexo' => 'nullable|string',
        'rnp' => 'nullable|string',
        'fedepalma' => 'nullable|string',
        'alfabetizado' => 'nullable|string',
        'nivel_estudio' => 'nullable|string',
        'otras_lineas' => 'nullable|string',
        'fecha_nacimiento' => 'nullable|date',
        'grupo_poblacional' => 'nullable|string',
        'reside_predio' => 'nullable|string',
        'administra_cultivo' => 'nullable|string',
        'supervisa_cultivo' => 'nullable|string',
        'realiza_cultivo' => 'nullable|string',
        'anios_palmicultura' => 'nullable|integer',
        'internet' => 'nullable|string',
        'tipo_persona' => 'nullable|string',
        'red_social' => 'nullable|string',
        'regimen_salud' => 'nullable|string',
    ]);

    $datos->update($validated);

    return redirect()->route('datos_personales_sociales.create', $visita)
        ->with('success', 'Datos personales actualizados correctamente.');
}


    public function show($id)
{
    $datos = DatosPersonalesSocial::findOrFail($id);
    $visita = $datos->visitaSocial; // asumiendo que tienes relación en el modelo

    return view('datos_personales_social.show', compact('datos', 'visita'));
}
}
