@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body {
        background-image: url('{{ asset('images/fondo_agronomico.png') }}');
    }
    
    .container-dashboard {
        width: 90%;
        padding: 20px;
        background-color: #5282289d;
        border-radius: 35px;
        margin: 20px auto;
    }
    
    .button-33 {
        background-color: #073c1a;
        border-radius: 100px;
        box-shadow: rgba(44, 187, 99, .2) 0 -25px 18px -14px inset,
                    rgba(44, 187, 99, .15) 0 1px 2px,
                    rgba(44, 187, 99, .15) 0 2px 4px,
                    rgba(44, 187, 99, .15) 0 4px 8px,
                    rgba(44, 187, 99, .15) 0 8px 16px,
                    rgba(44, 187, 99, .15) 0 16px 32px;
        color: #fff;
        cursor: pointer;
        display: inline-block;
        padding: 8px 22px;
        text-align: center;
        text-decoration: none;
        transition: all 250ms;
        border: 0;
        font-size: 15px;
        user-select: none;
    }
    
    .button-33:hover {
        box-shadow: rgba(44,187,99,.35) 0 -25px 18px -14px inset,
                    rgba(44,187,99,.25) 0 1px 2px,
                    rgba(44,187,99,.25) 0 2px 4px,
                    rgba(44,187,99,.25) 0 4px 8px,
                    rgba(44,187,99,.25) 0 8px 16px,
                    rgba(44,187,99,.25) 0 16px 32px;
        transform: scale(1.05) rotate(-1deg);
    }
    
    .card-custom {
        background-color: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        border: 2px solid #2e7d32;
        margin-bottom: 20px;
        overflow: hidden;
    }
    
    .card-header-custom {
        background: linear-gradient(135deg, #2e7d32, #1b5e20);
        color: white;
        border-radius: 13px 13px 0 0 !important;
        padding: 15px 20px;
        font-weight: bold;
        font-size: 1.1rem;
    }
    
    .form-control-custom {
        border: 1px solid #ced4da;
        border-radius: 8px;
        padding: 8px 12px;
        transition: all 0.3s;
    }
    
    .form-control-custom:focus {
        border-color: #2e7d32;
        box-shadow: 0 0 0 0.2rem rgba(46, 125, 50, 0.25);
    }
    
    .componentes-info {
        background: linear-gradient(135deg, #e8f5e8, #f8fff8);
        border: 2px solid #2e7d32;
        border-radius: 15px;
        padding: 20px;
        margin-top: 20px;
    }
    
    .componente-item {
        background-color: white;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 10px;
        border-left: 5px solid;
        transition: all 0.3s;
    }
    
    .componente-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .componente-1 { border-color: #3498db; }
    .componente-2 { border-color: #2ecc71; }
    .componente-3 { border-color: #e74c3c; }
    .componente-4 { border-color: #f39c12; }
    .componente-5 { border-color: #9b59b6; }
</style>

<div class="container container-dashboard">
    <!-- Título -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h3 class="text-white">📝 Crear Nueva Evaluación IBT</h3>
            <p style="color: #e0e0e0">Paso 1: Información básica de la evaluación</p>
        </div>
    </div>

    <form id="formEvaluacionIbt" method="POST" action="{{ route('evaluaciones-ibt.store') }}">
        @csrf
        
        <!-- Información básica -->
        <div class="card-custom">
            <div class="card-header-custom">
                <i class="fas fa-info-circle"></i> Información General de la Evaluación
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="proveedor_id" class="form-label fw-bold">Proveedor *</label>
                            <select name="proveedor_id" id="proveedor_id" class="form-control form-control-custom" required>
                                <option value="">Seleccionar proveedor...</option>
                                @foreach($proveedores as $p)
                                    <option value="{{ $p->id }}" {{ old('proveedor_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->proveedor_nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('proveedor_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="plantacion_id" class="form-label fw-bold">Plantación *</label>
                            <select name="plantacion_id" id="plantacion_id" class="form-control form-control-custom" required>
                                <option value="">Seleccionar plantación...</option>
                                @foreach($plantaciones as $pl)
                                    <option value="{{ $pl->id }}" {{ old('plantacion_id') == $pl->id ? 'selected' : '' }}>
                                        {{ $pl->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('plantacion_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fecha_inicio" class="form-label fw-bold">Fecha de Inicio *</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" 
                                   class="form-control form-control-custom" value="{{ old('fecha_inicio', date('Y-m-d')) }}" required>
                            @error('fecha_inicio')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row g-3 mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="visita_id" class="form-label fw-bold">Visita Asociada (Opcional)</label>
                            <select name="visita_id" id="visita_id" class="form-control form-control-custom">
                                <option value="">Sin visita asociada</option>
                                @foreach($visitas as $v)
                                    <option value="{{ $v->id }}" {{ old('visita_id') == $v->id ? 'selected' : '' }}>
                                        #{{ $v->id }} - {{ $v->proveedor->proveedor_nombre ?? 'N/A' }} - {{ date('d/m/Y', strtotime($v->fecha)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tecnico_id" class="form-label fw-bold">Técnico Evaluador *</label>
                            <select name="tecnico_id" id="tecnico_id" class="form-control form-control-custom" required>
                                <option value="">Seleccionar técnico...</option>
                                @foreach($tecnicos as $t)
                                    <option value="{{ $t->id }}" {{ old('tecnico_id') == $t->id ? 'selected' : '' }}>
                                        {{ $t->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tecnico_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="observaciones_generales" class="form-label fw-bold">Observaciones Iniciales</label>
                            <textarea name="observaciones_generales" id="observaciones_generales" 
                                      class="form-control form-control-custom" rows="3"
                                      placeholder="Observaciones generales sobre la evaluación...">{{ old('observaciones_generales') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información sobre los componentes -->
        <div class="componentes-info">
            <h4 class="text-center mb-4" style="color: #2e7d32;">
                <i class="fas fa-list-check"></i> Componentes a Evaluar
            </h4>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="componente-item componente-1">
                        <h6><i class="fas fa-seedling text-primary"></i> <strong>1. Establecimiento de cultivo</strong></h6>
                        <small class="text-muted">6 criterios - 20 puntos máx.</small>
                        <p class="mb-0"><small>Evaluación de establecimiento inicial del cultivo</small></p>
                    </div>
                    
                    <div class="componente-item componente-2">
                        <h6><i class="fas fa-tools text-success"></i> <strong>2. Labores culturales</strong></h6>
                        <small class="text-muted">6 criterios - 20 puntos máx.</small>
                        <p class="mb-0"><small>Evaluación de labores culturales y mantenimiento</small></p>
                    </div>
                    
                    <div class="componente-item componente-3">
                        <h6><i class="fas fa-flask text-danger"></i> <strong>3. Manejo nutricional</strong></h6>
                        <small class="text-muted">7 criterios - 25 puntos máx.</small>
                        <p class="mb-0"><small>Evaluación de manejo nutricional y fertilización</small></p>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="componente-item componente-4">
                        <h6><i class="fas fa-shield-alt text-warning"></i> <strong>4. Manejo sanitario</strong></h6>
                        <small class="text-muted">5 criterios - 20 puntos máx.</small>
                        <p class="mb-0"><small>Evaluación de manejo sanitario y control de plagas</small></p>
                    </div>
                    
                    <div class="componente-item componente-5">
                        <h6><i class="fas fa-tractor text-purple"></i> <strong>5. Cosecha y producción</strong></h6>
                        <small class="text-muted">4 criterios - 15 puntos máx.</small>
                        <p class="mb-0"><small>Evaluación de cosecha y parámetros de producción</small></p>
                    </div>
                    
                    <div class="text-center mt-4">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Total: 28 criterios - 100 puntos máx.</strong>
                            <br>
                            <small>La evaluación se completará por componentes en el siguiente paso</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="card-custom mt-4">
            <div class="card-body text-center p-4">
                <button type="submit" class="button-33 me-3 px-5">
                    <i class="fas fa-play-circle"></i> Iniciar Evaluación
                </button>
                <a href="{{ route('evaluaciones-ibt.index') }}" class="btn btn-secondary px-5">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus en el primer campo
    document.getElementById('proveedor_id').focus();
    
    // Validar formulario antes de enviar
    document.getElementById('formEvaluacionIbt').addEventListener('submit', function(e) {
        const proveedor = document.getElementById('proveedor_id').value;
        const plantacion = document.getElementById('plantacion_id').value;
        const tecnico = document.getElementById('tecnico_id').value;
        const fecha = document.getElementById('fecha_inicio').value;
        
        if (!proveedor || !plantacion || !tecnico || !fecha) {
            e.preventDefault();
            alert('Por favor complete todos los campos obligatorios (*) antes de continuar.');
            return false;
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {

    const proveedorSelect  = document.getElementById('proveedor_id');
    const plantacionSelect = document.getElementById('plantacion_id');

    proveedorSelect.addEventListener('change', function () {

        const proveedorId = this.value;

        // Reset plantaciones
        plantacionSelect.innerHTML = '<option value="">Seleccionar plantación...</option>';

        if (!proveedorId) {
            return;
        }

        fetch(`/evaluaciones-ibt/plantaciones-por-proveedor/${proveedorId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(pl => {
                    const option = document.createElement('option');
                    option.value = pl.id;
                    option.textContent = pl.nombre;
                    plantacionSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error cargando plantaciones:', error);
                alert('No se pudieron cargar las plantaciones');
            });
    });

});
</script>
@endsection