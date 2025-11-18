
<?php

use App\Http\Controllers\AreaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlantacionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\PlanificacionController;
use App\Http\Controllers\AreaOfflineController;
use App\Http\Controllers\CierreVisitaController;
use App\Http\Controllers\DatoPredioSocialController;
use App\Http\Controllers\DatosPersonalesSocialController;
use App\Http\Controllers\EnvioController;
use App\Http\Controllers\FertilizacionController;
use App\Http\Controllers\LaboresCultivoController;
use App\Http\Controllers\SanidadController;
use App\Http\Controllers\SueloController;
use App\Http\Controllers\EvaluacionCosechaCampoController;
use App\Http\Controllers\FuerzaLaboralController;
use App\Http\Controllers\FullVisitaImportController;
use App\Http\Controllers\PlanificacionSocialController;
use App\Http\Controllers\PolinizacionController;
use App\Http\Controllers\VisitaController;
use App\Http\Controllers\VisitaSocialController;
use App\Http\Controllers\VisitaImportController;
use App\Models\Plantacion;
use App\Http\Controllers\MiembroHogarController;
use App\Http\Controllers\FullVisitaSocialImportController;
use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\DashboardPlantacionController;
use App\Http\Controllers\DashboardVisitaSocialController;
use App\Http\Controllers\UserController;


// Redirección por defecto al login
Route::redirect('/', '/login');

// Rutas de autenticación
require __DIR__.'/auth.php';

// Rutas para usuarios autenticados
Route::middleware('auth')->group(function () {

     Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/crear', [UserController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{id}/editar', [UserController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{id}', [UserController::class, 'update'])->name('usuarios.update');
    Route::patch('/usuarios/{id}/estado', [UserController::class, 'cambiarEstado'])->name('usuarios.estado');
    Route::get('/proveedores/search', [App\Http\Controllers\ProveedorController::class, 'search'])
    ->name('proveedores.search');
    Route::get('proveedores/eliminados', [ProveedorController::class, 'eliminados'])
    ->name('proveedores.eliminados');

    // rutas de envios 
    Route::get('/proveedor/{id}/plantaciones', [EnvioController::class, 'getPlantacionesByProveedor'])->name('proveedor.plantaciones');

    Route::resource('envios', EnvioController::class);
    Route::post('envios/{envio}/ejecutar', [EnvioController::class, 'ejecutar'])->name('envios.ejecutar');
    Route::post('envios/{envio}/completar', [EnvioController::class, 'completar'])->name('envios.completar');
    Route::post('envios/{envio}/evidencias', [EnvioController::class, 'uploadEvidencias'])->name('envios.evidencias.store');


    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Perfil 
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD de Plantaciones individuales
    
    Route::get('/plantaciones/import', [PlantacionController::class, 'importForm'])->name('plantaciones.import.form');
    Route::post('/plantaciones/import', [PlantacionController::class, 'import'])->name('plantaciones.import.store');

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
      
      Route::get('/proveedores/import', [ProveedorController::class, 'importForm'])->name('proveedores.import.form');
    Route::post('/proveedores/import', [ProveedorController::class, 'import'])->name('proveedores.import.store');
    Route::resource('proveedores', ProveedorController::class)->parameters([
        'proveedores' => 'proveedor' 
    ]);
    
    
    
    // Plantaciones asociadas a un proveedor
    Route::get('/proveedores/{proveedor}/plantaciones', [ProveedorController::class, 'plantacionesIndex'])->name('proveedores.plantaciones.index');

    // rutas para visita 
    Route::get('/visitas/home', [VisitaController::class, 'homeVisitas'])->name('visitasHome');
    Route::get('/visitas/import', [VisitaController::class, 'importForm'])->name('visitas.import.form');
    Route::post('/visitas/import', [VisitaController::class, 'import'])->name('visitas.import.store');
    Route::get('/visitas/eliminadas', [VisitaController::class, 'eliminadas'])->name('visitas.eliminadas');


    Route::resource('visitas', App\Http\Controllers\VisitaController::class);
    Route::get('/api/plantaciones/{proveedor}', function ($proveedorId) {
        return \App\Models\Plantacion::where('id_proveedor', $proveedorId)->get();
    });
    Route::get('/visitas/{id}/detalle', [App\Http\Controllers\VisitaController::class, 'detalle'])->name('visitas.detalle');
    // Ruta para iniciar visita

    // rutas de planificacion 
    Route::resource('planificaciones', PlanificacionController::class);
    Route::get('/planificaciones-calendario', [PlanificacionController::class, 'calendario'])->name('planificaciones.calendario');
    Route::get('/api/planificaciones', [PlanificacionController::class, 'apiPlanificaciones']);
    Route::get('/api/planificacion/{id}', function ($id) {
            return \App\Models\Planificacion::findOrFail($id);
        });
    // Asegúrate de que las rutas estén así:
    Route::get('/planificaciones/{planificacion}/edit', [PlanificacionController::class, 'edit'])->name('planificaciones.edit');
    Route::put('/planificaciones/{planificacion}', [PlanificacionController::class, 'update'])->name('planificaciones.update');

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
    Route::get('/labores-cultivo/{visita_id}/create', [LaboresCultivoController::class, 'create'])->name('labores_cultivo.create');
    Route::post('/labores-cultivo', [LaboresCultivoController::class, 'store'])->name('labores_cultivo.store');
    Route::get('/labores-cultivo/{visita_id}/edit', [LaboresCultivoController::class, 'edit'])->name('labores_cultivo.edit');
    Route::put('/labores-cultivo/{id}', [LaboresCultivoController::class, 'update'])->name('labores-cultivo.update');
    Route::delete('/labores-cultivo/{id}', [LaboresCultivoController::class, 'destroy'])->name('labores_cultivo.destroy');


    // rutas evaluacion cosecha campo 
    Route::get('/evaluacion-cosecha/create', [EvaluacionCosechaCampoController::class, 'create'])->name('evaluacion.create');
    Route::post('/evaluacion-cosecha', [EvaluacionCosechaCampoController::class, 'store'])->name('evaluacion.store');
    Route::get('/evaluacion-cosecha/{evaluacion}/edit', [EvaluacionCosechaCampoController::class, 'edit'])->name('evaluacion.edit');
    Route::put('/evaluacion-cosecha/{evaluacion}', [EvaluacionCosechaCampoController::class, 'update'])->name('evaluacion.update');
    Route::delete('/evaluacion-cosecha/{id}', [EvaluacionCosechaCampoController::class, 'destroy'])->name('evaluacion.destroy');

    // rutas para cierre de visitas 
    Route::get('/cierre-visitas/{visita_id}/create', [CierreVisitaController::class, 'create'])->name('cierre-visitas.create');
    Route::post('/cierre-visitas', [CierreVisitaController::class, 'store'])->name('cierre-visitas.store');
    
    //rutas para exportacion de visitas
    Route::get('/visitas/{id}/exportar/pdf', [VisitaController::class, 'exportarPDF'])->name('visitas.exportar.pdf');
    Route::get('/visitas/{id}/exportar/excel', [VisitaController::class, 'exportarExcel'])->name('visitas.exportar.excel');



    //rutas importaciones excel 

    Route::get('/visitas/import', [VisitaImportController::class, 'showForm'])->name('visitas.import.form');
    Route::post('/visitas/import', [VisitaImportController::class, 'import'])->name('visitas.import');
    Route::get('/visitas/full-import/form', [FullVisitaImportController::class, 'showForm'])->name('visitas.full-import.form');
    Route::post('/visitas/full-import', [FullVisitaImportController::class, 'import'])->name('visitas.full-import');
    
  

 
// Página offline (SPA en Vue que maneja rutas internamente)
Route::get('/offline/{any?}', function () {
    return response()->view('offline');
})->where('any', '.*');

// Ruta para iniciar visita agronómica
Route::put('/visitas/{visita}/iniciar-agronomica', [VisitaController::class, 'iniciarAgronomica'])->name('visitas.iniciar_agronomica');

// Ruta para redirección de secciones agronómicas
Route::get('/visitas/{visita}/redireccion-seccion-agronomica', [VisitaController::class, 'redireccionSeccionAgronomica'])->name('redireccion_seccion_agronomica');






// RUTAS PARA EL COMPONENETE SOCIAL 
    Route::get('/visitas/home', [VisitaController::class, 'homeVisitas'])->name('visitasHome');
    Route::prefix('visitas-social')->name('visitas_social.')->group(function () {
    Route::get('/homeSocial', [VisitaSocialController::class, 'home'])->name('homeSocial');
    Route::get('/Social', [VisitaSocialController::class, 'index'])->name('indexSocial');
    Route::get('/createSocial', [VisitaSocialController::class, 'create'])->name('createSocial');
    Route::post('/storeSocial', [VisitaSocialController::class, 'store'])->name('storeSocial');
    Route::get('Social/{id}', [VisitaSocialController::class, 'show'])->name('showSocial');
    Route::get('/{id}/editSocial', [VisitaSocialController::class, 'edit'])->name('editSocial');
    Route::put('SocialUpdate/{id}', [VisitaSocialController::class, 'update'])->name('updateSocial');
    Route::delete('social-destroy/{id}', [VisitaSocialController::class, 'destroy'])->name('destroySocial');
    Route::get('eliminadas', [VisitaSocialController::class, 'eliminadas'])->name('eliminadas');

        Route::get('/visitas-social/{id}/detalleSocial', [App\Http\Controllers\VisitaSocialController::class, 'detalle'])->name('detalleSocial');


    // Exportaciones
    Route::get('/{id}/export-pdfSocial', [VisitaSocialController::class, 'exportarPDF'])->name('exportar_pdfSocial');
    Route::get('/{id}/export-excelSocial', [VisitaSocialController::class, 'exportarExcel'])->name('exportar_excelSocial');
    
   




    // API para calendario
    Route::get('/api/planificaciones-sociales', [PlanificacionSocialController::class, 'apiPlanificacionesSociales']);

    // API para obtener plantaciones por proveedor
    Route::get('/api/plantaciones-por-proveedor/{proveedorId}', function ($proveedorId) {
        return Plantacion::where('id_proveedor', $proveedorId)->get();
    });

    // Actualización de estado
    Route::put('/{visita}/update-statusSocial', [VisitaSocialController::class, 'updateStatus'])->name('update_statusSocial');

    
});

 // Importar visitas sociales
    Route::get('/visitas-social/import', [App\Http\Controllers\VisitaSocialController::class, 'importForm'])
        ->name('visitas_social.import.form');

    Route::post('/visitas-social/import', [App\Http\Controllers\VisitaSocialController::class, 'import'])
        ->name('visitas_social.import');

    Route::put('/visitas/{visita}/iniciar', [VisitaSocialController::class, 'iniciar'])->name('visitas.iniciar');
    Route::get('/visitas-social/full-import/form', [FullVisitaSocialImportController::class, 'showForm'])
    ->name('visitas_social.full-import.form');

    Route::post('/visitas-social/full-import', [FullVisitaSocialImportController::class, 'import'])
    ->name('visitas_social.full-import');


Route::get('/visitas-social/{id}/edit', [VisitaSocialController::class, 'edit'])->name('visitas_social.edit');
Route::put('/visitas-social/{id}', [VisitaSocialController::class, 'update'])->name('visitas_social.update');

 Route::get('/visitas-social/{id}/exportar-pdf', [VisitaSocialController::class, 'exportarPDF'])
    ->name('visitas_social.exportar.pdf');

 // Rutas para planificación social
    Route::prefix('planificaciones-social')->name('planificaciones_social.')->group(function () {
    Route::get('/', [PlanificacionSocialController::class, 'index'])->name('index');
    Route::get('/create', [PlanificacionSocialController::class, 'create'])->name('create');
    Route::post('/', [PlanificacionSocialController::class, 'store'])->name('store');

    // 🔹 Primero rutas fijas
    Route::get('/calendario', [PlanificacionSocialController::class, 'calendario'])->name('calendario');
    Route::get('/eventos', [PlanificacionSocialController::class, 'eventos'])->name('eventos');

    // 🔹 Después las que tienen parámetros dinámicos
    Route::get('/{planificacionSocial}', [PlanificacionSocialController::class, 'show'])->name('show');
    Route::get('/{planificacionSocial}/edit', [PlanificacionSocialController::class, 'edit'])->name('edit');
    Route::put('/{planificacionSocial}', [PlanificacionSocialController::class, 'update'])->name('update');
    Route::delete('/{planificacionSocial}', [PlanificacionSocialController::class, 'destroy'])->name('destroy');
});

// edit planificacion social 
    Route::get('/planificaciones-social/{id}/edit', [PlanificacionSocialController::class, 'edit'])->name('planificaciones_social.edit');
    Route::put('/planificaciones-social/{id}', [PlanificacionSocialController::class, 'update'])->name('planificaciones_social.update');





    // API para obtener plantaciones por proveedor (AGREGAR ESTA RUTA)
    Route::get('/api/plantaciones-por-proveedor/{proveedorId}', function ($proveedorId) {
        $plantaciones = Plantacion::where('id_proveedor', $proveedorId)->get();
        
        return response()->json($plantaciones);
    });

    // Zona de visita social - datos personales 
    Route::prefix('visitas-social/{visita}')->name('datos_personales_sociales.')->group(function () {
    Route::get('/datos-personales/create', [DatosPersonalesSocialController::class, 'create'])->name('create');
    Route::post('/datos-personales', [DatosPersonalesSocialController::class, 'store'])->name('store');
    Route::get('edit/datos-personales', [DatosPersonalesSocialController::class, 'edit'])->name('edit');
    Route::put('update/datos-personales', [DatosPersonalesSocialController::class, 'update'])->name('update');
    Route::get('/datos-personales/{id}', [DatosPersonalesSocialController::class, 'show'])->name('show');

    });
    Route::put('/visitas_social/{id}/iniciar', [App\Http\Controllers\VisitaSocialController::class, 'iniciarVisita'])
    ->name('visitas_social.iniciar');
    


    //Zona de Visita Social -  miembros del hogar 


Route::prefix('visitas-sociales/{visitaId}')->group(function () {
    Route::get('/miembros-hogar', [MiembroHogarController::class, 'index'])->name('miembros_hogar.index');
    Route::get('/miembros-hogar/create', [MiembroHogarController::class, 'create'])->name('miembros_hogar.create');
    Route::post('/miembros-hogar', [MiembroHogarController::class, 'store'])->name('miembros_hogar.store');
    Route::get('/miembros-hogar/{id}/edit', [MiembroHogarController::class, 'edit'])->name('miembros_hogar.edit');
    Route::put('/miembros-hogar/{id}', [MiembroHogarController::class, 'update'])->name('miembros_hogar.update');
    Route::delete('/miembros-hogar/{id}', [MiembroHogarController::class, 'destroy'])->name('miembros_hogar.destroy');
});
Route::get('/visitas-social/{visita}/redirigir', [App\Http\Controllers\VisitaSocialController::class, 'redirigirSeccion'])
    ->name('redireccion_seccion_social');


    //Zona de Visita Social - Predio informacion 

    Route::prefix('visitas-sociales/{visitaId}/datos-predio')->group(function () {
    Route::get('/', [DatoPredioSocialController::class, 'index'])->name('datos_predio_social.index');
    Route::get('/{plantacionId}/create', [DatoPredioSocialController::class, 'create'])->name('datos_predio_social.create');
    Route::post('/{plantacionId}', [DatoPredioSocialController::class, 'store'])->name('datos_predio_social.store');
    Route::get('/{id}/edit', [DatoPredioSocialController::class, 'edit'])->name('datos_predio_social.edit');
    Route::put('/{id}', [DatoPredioSocialController::class, 'update'])->name('datos_predio_social.update');
    Route::delete('/{id}', [DatoPredioSocialController::class, 'destroy'])->name('datos_predio_social.destroy');
});

    // Zona de Visita Social -  Fuerza laboral

    Route::prefix('visitas-social/{visita}')->name('fuerza_laboral.')->group(function () {
    Route::get('/fuerza-laboral', [FuerzaLaboralController::class, 'index'])->name('index');
    Route::get('/fuerza-laboral/create', [FuerzaLaboralController::class, 'create'])->name('create');
    Route::post('/fuerza-laboral', [FuerzaLaboralController::class, 'store'])->name('store');
    Route::get('/fuerza-laboral/{id}/edit', [FuerzaLaboralController::class, 'edit'])->name('edit');
    Route::put('/fuerza-laboral/{id}', [FuerzaLaboralController::class, 'update'])->name('update');
    Route::get('/fuerza-laboral/{id}', [FuerzaLaboralController::class, 'show'])->name('show');
     Route::delete('/{fuerza}', [FuerzaLaboralController::class, 'destroy'])->name('destroy');
});

    // Zona de Visita Social - Organización Social
    Route::prefix('visitas_social/{visita_id}/organizacion_social')->group(function () {
    Route::get('/', [App\Http\Controllers\OrganizacionSocialController::class, 'index'])->name('organizacion_social.index');
    Route::get('/create', [App\Http\Controllers\OrganizacionSocialController::class, 'create'])->name('organizacion_social.create');
    Route::post('/', [App\Http\Controllers\OrganizacionSocialController::class, 'store'])->name('organizacion_social.store');
    Route::get('/{id}', [App\Http\Controllers\OrganizacionSocialController::class, 'show'])->name('organizacion_social.show');
    Route::get('/{id}/edit', [App\Http\Controllers\OrganizacionSocialController::class, 'edit'])->name('organizacion_social.edit');
    Route::put('/{id}', [App\Http\Controllers\OrganizacionSocialController::class, 'update'])->name('organizacion_social.update');
});

    // Cierre de visitas sociales
    Route::get('cierre-visitas-social/create/{visita_social_id}', [App\Http\Controllers\CierreVisitaSocialController::class, 'create'])
        ->name('cierre-visitas-social.create'); 

    Route::post('cierre-visitas-social/store', [App\Http\Controllers\CierreVisitaSocialController::class, 'store'])
        ->name('cierre-visitas-social.store');

    // actualizar estado social 

    Route::put('/visitas-social/{visitaId}/update-status', [VisitaSocialController::class, 'updateStatus']);


    // rutas para envios 

    Route::get('/envios/{envio}/preparar', [EnvioController::class, 'preparar'])->name('envios.preparar');
    Route::post('/envios/{envio}/iniciar', [EnvioController::class, 'iniciar'])->name('envios.iniciar');
    Route::get('/envios/{envio}/stage', [EnvioController::class, 'stage'])->name('envios.stage');
    Route::post('/envios/{envio}/comenzar', [EnvioController::class, 'comenzar'])->name('envios.comenzar');
    Route::post('/envios/{envio}/evidencias', [EnvioController::class, 'uploadEvidencias'])->name('envios.upload');
    Route::post('/envios/{envio}/completar', [EnvioController::class, 'completar'])->name('envios.completar');

    Route::get('/envios/{id}/pdf', [App\Http\Controllers\EnvioController::class, 'generarPDF'])
    ->name('envios.pdf');
    // RUTAS AUDITORIA DE MOVIMIENTO 


    Route::get('/auditorias', [AuditoriaController::class, 'index'])->name('auditorias.index');

// rutas de informacion o get de bd con grafdicas 

    Route::get('/dashboard/visitas-agro', [DashboardController::class, 'visitasAgro'])
    ->name('dashboard.visitas_agro')
    ->middleware('auth');

    Route::get('/dashboard/visitas-agro', [DashboardController::class, 'visitasAgro'])
    ->name('dashboard.visitas.agro');
    Route::get('/dashboard/visitas-social', [DashboardVisitaSocialController::class, 'index'])->name('dashboard.visitas.social');


    // Endpoints AJAX para datos filtrados
    Route::get('/dashboard/visitas-agro/data', [DashboardController::class, 'dataVisitasAgro'])
        ->name('dashboard.visitas.agro.data');
    Route::get('/dashboard/visitas-agro/data-modulos', [DashboardController::class, 'dataModulos'])
        ->name('dashboard.visitas.agro.data.modulos');

    Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/plantaciones', [DashboardPlantacionController::class, 'index'])
        ->name('dashboard.plantaciones');
    });

    Route::get('/dashboard/plantaciones/{id}/visitas', [DashboardPlantacionController::class, 'verVisitas'])
    ->name('dashboard.plantaciones.visitas');


    
    Route::middleware(['auth'])->group(function () {
        Route::resource('usuarios', UserController::class);
    });


});