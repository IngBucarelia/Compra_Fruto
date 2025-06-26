
<?php

use App\Http\Controllers\AreaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlantacionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\PlanificacionController;
use App\Http\Controllers\AreaOfflineController;
use App\Http\Controllers\FertilizacionController;
use App\Http\Controllers\LaboresCultivoController;
use App\Http\Controllers\SanidadController;
use App\Http\Controllers\SueloController;
use App\Http\Controllers\EvaluacionCosechaCampoController;
use App\Http\Controllers\VisitaController;

// Redirección por defecto al login
Route::redirect('/', '/login');

// Rutas de autenticación
require __DIR__.'/auth.php';

// Rutas para usuarios autenticados
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Perfil 
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD de Plantaciones individuales
    Route::get('/plantaciones', [PlantacionController::class, 'index'])->name('plantaciones.index');
    Route::get('/plantaciones/create', [PlantacionController::class, 'create'])->name('plantaciones.create');
    Route::post('/plantaciones', [PlantacionController::class, 'store'])->name('plantaciones.store');
    Route::get('/plantaciones/{id}', [PlantacionController::class, 'show'])->name('plantaciones.show');
    Route::get('/plantaciones/{id}/edit', [PlantacionController::class, 'edit'])->name('plantaciones.edit');
    Route::put('/plantaciones/{id}', [PlantacionController::class, 'update'])->name('plantaciones.update');
    Route::delete('/plantaciones/{id}', [PlantacionController::class, 'destroy'])->name('plantaciones.destroy');

    // CRUD de Plantaciones anidadas (excepto index, que está personalizado arriba)
    Route::resource('proveedores.plantaciones', PlantacionController::class)->except(['index']);
    
      // CRUD de Proveedores
    Route::resource('proveedores', ProveedorController::class)->parameters([
        'proveedores' => 'proveedor' // fuerza a que lo llame proveedor
    ]);
    // Plantaciones asociadas a un proveedor
    Route::get('/proveedores/{proveedor}/plantaciones', [ProveedorController::class, 'plantacionesIndex'])->name('proveedores.plantaciones.index');

    // rutas para visita 
    Route::resource('visitas', App\Http\Controllers\VisitaController::class);
    Route::get('/api/plantaciones/{proveedor}', function ($proveedorId) {
        return \App\Models\Plantacion::where('id_proveedor', $proveedorId)->get();
    });
    Route::get('/visitas/{id}/detalle', [App\Http\Controllers\VisitaController::class, 'detalle'])->name('visitas.detalle');


    // rutas de planificacion 
    Route::resource('planificaciones', PlanificacionController::class);
    Route::get('/planificaciones-calendario', [PlanificacionController::class, 'calendario'])->name('planificaciones.calendario');
    Route::get('/api/planificaciones', [PlanificacionController::class, 'apiPlanificaciones']);
    Route::get('/api/planificacion/{id}', function ($id) {
            return \App\Models\Planificacion::findOrFail($id);
        });

    // rutas de area
    Route::resource('areas', AreaController::class)->middleware('auth');
    Route::get('/areas/{area}/edit', [AreaController::class, 'edit'])->name('areas.edit');
    Route::put('/areas/{area}', [AreaController::class, 'update'])->name('areas.update');


    //formulario offline 

    Route::get('/visitas/{id}/offline', function ($id) {
            $visita = \App\Models\Visita::with('proveedor')->findOrFail($id);
            return view('visitas.offline', compact('visita'));
        })->middleware('auth')->name('visitas.offline');
            //ruta areas 
            Route::post('/api/sync-areas', [AreaOfflineController::class, 'sync']);
            // ruta fertilizacion
            Route::post('/sync-fertilizaciones', [\App\Http\Controllers\Api\SyncController::class, 'fertilizaciones']);


    //rutas fertilizacion 
    Route::get('/fertilizaciones/create', [FertilizacionController::class, 'create'])->name('fertilizaciones.create');
    Route::post('/fertilizaciones', [FertilizacionController::class, 'store'])->name('fertilizaciones.store');

    //rutas polinizacion
    Route::resource('polinizaciones', \App\Http\Controllers\PolinizacionController::class);
    Route::delete('/polinizaciones/{id}', [\App\Http\Controllers\PolinizacionController::class, 'destroy'])->name('polinizaciones.destroy');
    Route::get('/fertilizaciones/{id}/edit', [FertilizacionController::class, 'edit'])->name('fertilizaciones.edit');
    Route::put('/fertilizaciones/{id}', [FertilizacionController::class, 'update'])->name('fertilizaciones.update');
    Route::delete('/fertilizaciones/{id}', [FertilizacionController::class, 'destroy'])->name('fertilizaciones.destroy');
    
    // rutas sanidad
    Route::resource('sanidades', \App\Http\Controllers\SanidadController::class)->only(['create', 'store']);
    Route::delete('/sanidades/{id}', [SanidadController::class, 'destroy'])->name('sanidades.destroy');
    Route::get('/sanidades/{id}/edit', [SanidadController::class, 'edit'])->name('sanidades.edit');
    Route::put('/sanidades/{id}', [SanidadController::class, 'update'])->name('sanidades.update');



    // rutas analisis de suelo 
    Route::get('/suelos/create', [App\Http\Controllers\SueloController::class, 'create'])->name('suelos.create');
    Route::post('/suelos', [App\Http\Controllers\SueloController::class, 'store'])->name('suelos.store');
    Route::get('/suelos/{suelo}/edit', [SueloController::class, 'edit'])->name('suelos.edit');
    Route::put('/suelos/{suelo}', [SueloController::class, 'update'])->name('suelos.update');
    Route::delete('/suelos/{id}', [SueloController::class, 'destroy'])->name('suelos.destroy');


    // labores de cultivo rutas 
    Route::get('/labores-cultivo/create', [LaboresCultivoController::class, 'create'])->name('labores_cultivo.create');
    Route::post('/labores-cultivo', [LaboresCultivoController::class, 'store'])->name('labores_cultivo.store');
    Route::get('/labores-cultivo/{visita_id}/edit', [LaboresCultivoController::class, 'edit'])->name('labores-cultivo.edit');
    Route::put('/labores-cultivo/{id}', [LaboresCultivoController::class, 'update'])->name('labores-cultivo.update');
    Route::delete('/labores-cultivo/{id}', [LaboresCultivoController::class, 'destroy'])->name('labores-cultivo.destroy');


    // rutas evaluacion cosecha campo 
    Route::get('/evaluacion-cosecha/create', [EvaluacionCosechaCampoController::class, 'create'])->name('evaluacion.create');
    Route::post('/evaluacion-cosecha', [EvaluacionCosechaCampoController::class, 'store'])->name('evaluacion.store');
    Route::get('/evaluacion-cosecha/{evaluacion}/edit', [EvaluacionCosechaCampoController::class, 'edit'])->name('evaluacion.edit');
    Route::put('/evaluacion-cosecha/{evaluacion}', [EvaluacionCosechaCampoController::class, 'update'])->name('evaluacion.update');
    Route::delete('/evaluacion-cosecha/{id}', [EvaluacionCosechaCampoController::class, 'destroy'])->name('evaluacion.destroy');

    // rutas para cierre de visitas 
    Route::get('/cierre-visitas/create', [App\Http\Controllers\CierreVisitaController::class, 'create'])->name('cierre-visitas.create');
    Route::post('/cierre-visitas', [App\Http\Controllers\CierreVisitaController::class, 'store'])->name('cierre-visitas.store');

    //rutas para exportacion de visitas
    Route::get('/visitas/{id}/exportar/pdf', [VisitaController::class, 'exportarPDF'])->name('visitas.exportar.pdf');
    Route::get('/visitas/{id}/exportar/excel', [VisitaController::class, 'exportarExcel'])->name('visitas.exportar.excel');

















});