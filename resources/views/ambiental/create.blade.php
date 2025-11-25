@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12">

            <div class="card shadow-lg border-0">

                <div class="card-header bg-gradient-success text-white py-3">
                    <h4 class="mb-0 text-black">
                        🌿 Registrar Visita Ambiental
                    </h4>
                </div>

                <div class="card-body p-4">

                    <form method="POST" action="{{ route('visitasAmbientales.store') }}" id="visitaAmbientalForm">
                        @csrf

                        {{-- FECHA --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold text-success">Fecha de visita:</label>
                            <input type="date" name="fecha_visita" class="form-control"
                                   value="{{ date('Y-m-d') }}" required>
                        </div>

                        {{-- PROVEEDOR --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold text-success">Proveedor:</label>

                            <div class="input-group mb-2">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" id="buscarProveedor" class="form-control"
                                       placeholder="Buscar proveedor..." oninput="filtrarProveedores()">
                            </div>

                            <select id="proveedor_id" name="proveedor_id" class="form-control"
                                    size="8" style="min-height: 200px" required>
                                <option value="">Seleccione proveedor</option>
                                @foreach($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}">{{ $proveedor->proveedor_nombre }}</option>
                                @endforeach
                            </select>

                            <small class="text-muted" id="contadorProveedores">
                                {{ count($proveedores) }} proveedores disponibles
                            </small>
                        </div>

                        {{-- PLANTACION --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold text-success">Plantación:</label>
                            <select id="plantacion_id" name="plantacion_id" class="form-control" required>
                                <option value="">Seleccione un proveedor primero</option>
                            </select>
                        </div>

                        {{-- UBICACIÓN --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold text-success">Ubicación:</label>
                            <input type="text" id="ubicacion" name="ubicacion" class="form-control"
                                   placeholder="Ubicación de la plantación" required>
                        </div>

                        {{-- TECNICO --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold text-success">Técnico ambiental:</label>
                            <select name="tecnico_id" class="form-control" required>
                                <option value="">Seleccione técnico</option>
                                @foreach($tecnicos as $t)
                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- TIPOS DE VISITA --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold text-success">Tipo de visita ambiental:</label>

                            <div class="row g-2">
                                @php
                                    $tiposAmbientales = [
                                        ['Monitoreo', 'eye'],
                                        ['Seguimiento', 'sync'],
                                        ['Verificacion', 'check-circle'],
                                        ['Biodiversidad', 'leaf'],
                                        ['Fauna', 'paw'],
                                        ['Flora', 'seedling'],
                                        ['Impacto Ambiental', 'exclamation-triangle']
                                    ];
                                @endphp

                                @foreach($tiposAmbientales as [$valor, $icono])
                                    <div class="col-6">
                                        <div class="form-check bg-light p-2 rounded border">
                                            <input class="form-check-input" type="checkbox"
                                                   name="tipo_visita_ambiental[]"
                                                   value="{{ $valor }}"
                                                   id="tipo_{{ strtolower(str_replace(' ', '_', $valor)) }}">

                                            <label class="form-check-label fw-bold"
                                                   for="tipo_{{ strtolower(str_replace(' ', '_', $valor)) }} }}">
                                                <i class="fas fa-{{ $icono }} text-success me-2"></i>
                                                {{ $valor }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <small class="text-muted">Puedes seleccionar múltiples opciones</small>
                        </div>

                        {{-- OBSERVACIONES --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold text-success">Observaciones:</label>
                            <textarea name="observaciones" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="alert alert-success mt-3">
                            Esta visita se registra manualmente.  
                            El estado inicial será <b>pendiente</b>.
                        </div>

                        {{-- BOTONES --}}
                        <div class="d-flex gap-3 mt-4">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Guardar visita
                            </button>

                            <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
                                <i class="fas fa-arrow-left me-2"></i>Cancelar
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
/**
 * SOLUCIÓN ROBUSTA: 1) Al cargar plantaciones ponemos data-ubicacion en cada <option>.
 *                     2) Al seleccionar plantación usamos ese data-ubicacion (rápido y fiable).
 *                     3) Si no existe data-ubicacion, hacemos fetch a la API como fallback.
 */

(function () {
    // referencias seguras a elementos (evitan errores si no existen)
    const proveedorEl = document.getElementById('proveedor_id');
    const plantacionEl = document.getElementById('plantacion_id');
    const ubicacionEl = document.getElementById('ubicacion');
    const buscarProveedorEl = document.getElementById('buscarProveedor');
    const contadorProveedoresEl = document.getElementById('contadorProveedores');

    if (!proveedorEl || !plantacionEl || !ubicacionEl) {
        console.warn('Formulario ambiental: faltan elementos (proveedor / plantacion / ubicacion).');
        return;
    }

    // filtrar proveedores (tu función)
    window.filtrarProveedores = function() {
        const filtro = buscarProveedorEl.value.toLowerCase();
        let opciones = proveedorEl.querySelectorAll('option');
        let contador = 0;
        opciones.forEach(op => {
            if (op.value === '') { op.style.display = ''; return; } // opción vacía siempre visible
            if (op.textContent.toLowerCase().includes(filtro)) {
                op.style.display = '';
                contador++;
            } else {
                op.style.display = 'none';
            }
        });
        if (contadorProveedoresEl) contadorProveedoresEl.textContent = contador + ' proveedores encontrados';
    };

    // cargar plantaciones según proveedor (añadiendo data-ubicacion)
    proveedorEl.addEventListener('change', async function () {
        const proveedorId = this.value;
        plantacionEl.innerHTML = '<option value="">Cargando plantaciones...</option>';
        ubicacionEl.value = '';

        if (!proveedorId) {
            plantacionEl.innerHTML = '<option value="">Seleccione un proveedor primero</option>';
            return;
        }

        try {
            const res = await fetch(`/api/plantaciones/${proveedorId}`);
            if (!res.ok) throw new Error('Respuesta no OK: ' + res.status);
            const data = await res.json();

            // Esperamos array de plantaciones con { id, nombre, vereda, municipio, departamento } idealmente
            if (!Array.isArray(data)) {
                console.error('API /api/plantaciones devolvió formato inesperado:', data);
                plantacionEl.innerHTML = '<option value="">Error: formato inválido</option>';
                return;
            }

            let opciones = '<option value="">Seleccione plantación</option>';
            data.forEach(p => {
                // construir ubicacion completa si los campos existen
                const ubic = [p.vereda, p.municipio, p.departamento].filter(Boolean).join(', ');
                // guardamos la ubicacion en data-ubicacion para uso rápido al seleccionar
                opciones += `<option value="${p.id}" data-ubicacion="${ubic}">${p.nombre}${ubic ? ' — ' + ubic : ''}</option>`;
            });

            plantacionEl.innerHTML = opciones;
        } catch (err) {
            console.error('Error cargando plantaciones:', err);
            plantacionEl.innerHTML = '<option value="">Error al cargar plantaciones</option>';
        }
    });

    // al seleccionar plantacion: tomar data-ubicacion o pedir fallback
    plantacionEl.addEventListener('change', async function () {
        const selected = this.options[this.selectedIndex];
        const rawUbic = selected ? selected.getAttribute('data-ubicacion') : null;

        if (rawUbic && rawUbic.trim().length) {
            // use data-ubicacion (rápido, no depende del servidor)
            ubicacionEl.value = rawUbic;
            return;
        }

        // fallback: si no tenemos data-ubicacion, consultamos la API por la plantación
        const plantacionId = this.value;
        if (!plantacionId) {
            ubicacionEl.value = '';
            return;
        }

        try {
            // Intentamos dos rutas posibles: /api/plantacion/{id} ó /api/plantaciones/{id}
            const endpoints = [
                `/api/plantacion/${plantacionId}`,
                `/api/plantaciones/${plantacionId}`,
            ];

            let responseData = null;
            for (const url of endpoints) {
                try {
                    const r = await fetch(url);
                    if (!r.ok) continue;
                    const json = await r.json();
                    // json puede ser { ubicacion: "..." } o el objeto plantación con vereda/municipio...
                    if (json.ubicacion) {
                        responseData = json.ubicacion;
                        break;
                    }
                    // si vienen partes separadas:
                    if (json.vereda || json.municipio || json.departamento) {
                        responseData = [json.vereda, json.municipio, json.departamento].filter(Boolean).join(', ');
                        break;
                    }
                    // si la API devuelve array, tomamos el primero
                    if (Array.isArray(json) && json.length>0 && (json[0].vereda||json[0].municipio)) {
                        const first = json[0];
                        responseData = [first.vereda, first.municipio, first.departamento].filter(Boolean).join(', ');
                        break;
                    }
                } catch (e) {
                    // ignora, probar siguiente endpoint
                    console.warn('Intento endpoint fallback falló:', url, e);
                }
            }

            if (responseData) {
                ubicacionEl.value = responseData;
                return;
            } else {
                console.warn('No se obtuvo ubicación del fallback API para plantación', plantacionId);
                ubicacionEl.value = '';
            }
        } catch (err) {
            console.error('Error en fallback para obtener ubicación:', err);
            ubicacionEl.value = '';
        }
    });

    // Inicialización: si ya hay proveedor seleccionado al cargar la página (editar), disparar cambio
    if (proveedorEl.value) {
        // usar setTimeout para garantizar que el DOM esté listo
        setTimeout(() => proveedorEl.dispatchEvent(new Event('change')), 50);
    }
})();
</script>

<style>
    body{
        background-image: url('{{ asset('images/fondo_ambiental.png') }}'); 
    }
</style>
@endsection
