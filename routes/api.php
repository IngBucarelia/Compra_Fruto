<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VisitaController; // Asegúrate de importar tu VisitaController
use App\Http\Controllers\AreaController;
use App\Http\Controllers\FertilizacionController;
use App\Http\Controllers\PolinizacionController;
use App\Http\Controllers\SanidadController;
use App\Http\Controllers\SueloController;
use App\Http\Controllers\LaboresCultivoController;
use App\Http\Controllers\EvaluacionCosechaCampoController;
use App\Http\Controllers\CierreVisitaController;


// Tu ruta principal de sincronización que tu JS está llamando
Route::post('/offline/sync', [VisitaController::class, 'syncOfflineData']);

// Las otras rutas específicas para cada tipo de formulario (si aún las usas, aunque tu JS parece llamar solo a /offline/sync)
Route::post('/offline-sync/areas', [AreaController::class, 'syncOffline']);
Route::post('/offline-sync/fertilizaciones', [FertilizacionController::class, 'syncOffline']);
Route::post('/offline-sync/polinizaciones', [PolinizacionController::class, 'syncOffline']);
Route::post('/offline-sync/sanidades', [SanidadController::class, 'syncOffline']);
Route::post('/offline-sync/suelos', [SueloController::class, 'syncOffline']);
Route::post('/offline-sync/labores', [LaboresCultivoController::class, 'syncOffline']);
Route::post('/offline-sync/evaluacion', [EvaluacionCosechaCampoController::class, 'syncOffline']);
Route::post('/offline-sync/cierre-visitas', [CierreVisitaController::class, 'syncOffline']);
Route::put('/visitas/update-status', [VisitaController::class, 'updateStatus']);


 