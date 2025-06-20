
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










});