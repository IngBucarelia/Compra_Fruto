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
use App\Http\Controllers\SocialSyncController;
use App\Http\Controllers\AmbientalSyncController;


// Tu ruta principal de sincronización que tu JS está llamando
Route::post('/offline/sync', [VisitaController::class, 'syncOfflineData']);

// Las otras rutas específicas para cada tipo de formulario (si aún las usas, aunque tu JS parece llamar solo a /offline/sync)
Route::post('/offline-sync/areas', [AreaController::class, 'syncOfflineData']);
Route::post('/offline-sync/fertilizaciones', [FertilizacionController::class, 'syncOffline']);
Route::post('/offline-sync/polinizaciones', [PolinizacionController::class, 'syncOffline']);
Route::post('/offline-sync/sanidades', [SanidadController::class, 'syncOffline']);
Route::post('/offline-sync/suelos', [SueloController::class, 'syncOffline']);
Route::post('/offline-sync/labores', [LaboresCultivoController::class, 'syncOffline']);
Route::post('/offline-sync/evaluacion', [EvaluacionCosechaCampoController::class, 'syncOffline']);
Route::post('/offline-sync/cierre-visitas', [CierreVisitaController::class, 'syncOffline']);
Route::put('/visitas/{visita}/update-status', [VisitaController::class, 'updateStatus'])->name('visitas.updateStatus');

// Rutas para datos sociales
   Route::prefix('offline-sync')->group(function () {
    Route::post('/datos-personales-social', [SocialSyncController::class, 'syncDatosPersonales']);
    Route::post('/miembros-hogar-social', [SocialSyncController::class, 'syncMiembrosHogar']);
    Route::post('/datos-predio-social', [SocialSyncController::class, 'syncDatosPredio']);
    Route::post('/fuerza-laboral-social', [SocialSyncController::class, 'syncFuerzaLaboral']);
    Route::post('/organizacion-social', [SocialSyncController::class, 'syncOrganizacion']);
    Route::post('/cierre-visita-social', [SocialSyncController::class, 'syncCierreVisita']);
});


/*
|--------------------------------------------------------------------------
| Rutas de sincronización OFFLINE – AMBIENTAL
|--------------------------------------------------------------------------
*/
Route::prefix('offline-sync')->group(function () {

    Route::post('/agua-captacion', [AmbientalSyncController::class, 'aguaCaptacion']);
    Route::post('/agua-uso', [AmbientalSyncController::class, 'aguaUso']);
    Route::post('/suelo-conservacion', [AmbientalSyncController::class, 'sueloConservacion']);
    Route::post('/energia', [AmbientalSyncController::class, 'energia']);
    Route::post('/gobernanza', [AmbientalSyncController::class, 'gobernanza']);
    Route::post('/emisiones', [AmbientalSyncController::class, 'emisiones']);
    Route::post('/residuos', [AmbientalSyncController::class, 'residuos']);
    Route::post('/sustancias', [AmbientalSyncController::class, 'sustancias']);
    Route::post('/vertimientos', [AmbientalSyncController::class, 'vertimientos']);
    Route::post('/hmp', [AmbientalSyncController::class, 'hmp']);
    Route::post('/avc', [AmbientalSyncController::class, 'avc']);
    Route::post('/ecosistema', [AmbientalSyncController::class, 'ecosistema']);
    Route::post('/no-deforestacion', [AmbientalSyncController::class, 'noDeforestacion']);
    Route::post('/cierre-ambiental', [AmbientalSyncController::class, 'cierreAmbiental']);

});





 