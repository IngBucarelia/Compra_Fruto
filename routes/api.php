<?php

use Illuminate\Support\Facades\Route;


Route::post('/sync-area', function (Illuminate\Http\Request $r) {
    $data = $r->validate([
        'visita_id' => 'required|integer|exists:visitas,id',
        'material' => 'required|in:guinense,hibrido',
        'estado' => 'required|in:desarrollo,produccion',
        'anio_siembra' => 'required|date',
        'area' => 'required|integer',
        'orden_plantis_numero' => 'required|integer',
        'estado_oren_plantis' => 'required|in:desarrollo,produccion',
    ]);

    \App\Models\Area::updateOrCreate(['visita_id' => $data['visita_id']], $data);
    return response()->json(['ok' => true]);
});

Route::post('/offline/sync', [\App\Http\Controllers\VisitaController::class, 'syncOfflineData']);

