@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-9">

            {{-- HEADER --}}
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        📋 Evaluación IBT
                    </h4>
                    <span class="badge bg-light text-dark fs-6">
                        ID #{{ $evaluacion->id }}
                    </span>
                </div>
            </div>

            {{-- INFORMACIÓN GENERAL --}}
            <div class="card shadow mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 text-success">
                        Información General
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Fecha</label>
                            <div class="p-2 bg-light rounded">
                                {{ $evaluacion->fecha_evaluacion }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Proveedor</label>
                            <div class="p-2 bg-light rounded">
                                {{ $evaluacion->proveedor->proveedor_nombre ?? 'N/A' }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Plantación</label>
                            <div class="p-2 bg-light rounded">
                                {{ $evaluacion->plantacion->nombre ?? 'N/A' }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Evaluador</label>
                            <div class="p-2 bg-light rounded">
                                {{ $evaluacion->tecnico->name ?? 'N/A' }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- COMPONENTES IBT --}}
            <div class="card shadow mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 text-success">
                        Componentes Evaluados
                    </h5>
                </div>
                <div class="card-body">

                    <ul class="list-group">

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            🌱 Establecimiento de Cultivo

                            <div>
                                @if($evaluacion->establecimientoCultivo)
                                    <span class="badge bg-success me-2">
                                        {{ $evaluacion->establecimientoCultivo->puntaje_total }} pts
                                    </span>
                                @else
                                    <span class="badge bg-secondary me-2">
                                        Sin evaluar
                                    </span>
                                @endif

                                <a href="{{ route('ibt.establecimiento_cultivo.redirect', $evaluacion->id) }}"
                                class="btn btn-sm btn-outline-success">
                                    {{ $evaluacion->establecimientoCultivo ? 'Editar' : 'Evaluar' }}
                                </a>
                            </div>
                        </li>
                        @php
                            $labores = $evaluacion->laboresCulturales;
                        @endphp

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            🚜 Labores culturales

                            <div>
                                @if($labores)
                                    <span class="badge bg-success me-2">
                                        {{ $labores->puntaje_total }} pts
                                    </span>
                                @else
                                    <span class="badge bg-secondary me-2">
                                        Sin evaluar
                                    </span>
                                @endif

                                <a href="{{ $labores
                                    ? route('ibt.labores_culturales.edit', $labores->id)
                                    : route('ibt.labores_culturales.create', $evaluacion->id) }}"
                                class="btn btn-sm btn-outline-success">
                                    {{ $labores ? 'Editar' : 'Evaluar' }}
                                </a>
                            </div>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            🥗 Manejo Nutricional

                            <div>
                            @if($evaluacion->manejoNutricional)
                                <span class="badge bg-success me-2">
                                    {{ $evaluacion->manejoNutricional->puntaje_total }} pts
                                </span>
                            @else
                                <span class="badge bg-secondary me-2">
                                    Sin evaluar
                                </span>
                            @endif

                            <a href="{{ $evaluacion->manejoNutricional
                                ? route('ibt.manejo_nutricional.edit', $evaluacion->manejoNutricional->id)
                                : route('ibt.manejo_nutricional.create', $evaluacion->id) }}"
                            class="btn btn-sm btn-outline-success">
                            {{ $evaluacion->manejoNutricional ? 'Editar' : 'Evaluar' }}
                            </a>
                            </div>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            🩺 Manejo sanitario

                            <div>
                                @if($evaluacion->manejoSanitario)
                                    <span class="badge bg-success me-2">
                                    {{ $evaluacion->manejoSanitario->puntaje_total }} pts
                                    </span>
                                    @else
                                    <span class="badge bg-secondary me-2">
                                    Sin evaluar
                                    </span>
                                @endif

                                <a href="{{ $evaluacion->manejoSanitario
                                ? route('ibt.manejo_sanitario.edit',$evaluacion->manejoSanitario->id)
                                : route('ibt.manejo_sanitario.create',$evaluacion->id) }}"
                                class="btn btn-sm btn-outline-success">
                                {{ $evaluacion->manejoSanitario ? 'Editar' : 'Evaluar' }}
                                </a>
                            </div>
                        </li>


                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            🧺 Cosecha y producción

                            <div>
                                    @if($evaluacion->cosechaProduccion)
                                    <span class="badge bg-success me-2">
                                    {{ $evaluacion->cosechaProduccion->puntaje_total }} pts
                                    </span>
                                    @else
                                    <span class="badge bg-secondary me-2">
                                    Sin evaluar
                                    </span>
                                    @endif

                                    <a href="{{ $evaluacion->cosechaProduccion
                                        ? route('ibt.cosecha_produccion.edit',$evaluacion->cosechaProduccion->id)
                                        : route('ibt.cosecha_produccion.create',$evaluacion->id) }}"
                                    class="btn btn-sm btn-outline-success">
                                    {{ $evaluacion->cosechaProduccion ? 'Editar' : 'Evaluar' }}
                                    </a>
                            </div>
                        </li>










                    </ul><br><br>
                    {{-- CALIFICACIÓN FINAL --}}
                    <div class="card shadow mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">🏁 Calificación Final IBT</h5>
                        </div>

                        <div class="card-body">

                            {{-- SI YA ESTÁ TERMINADA --}}
                            @if($evaluacion->calificacion === 'terminado')

                                <div class="row text-center mb-3">
                                    <div class="col-md-6">
                                        <h6>Puntaje Final</h6>
                                        <span class="badge bg-success fs-4">
                                            {{ $evaluacion->puntaje_total }} pts
                                        </span>
                                    </div>

                                    <div class="col-md-6">
                                        <h6>Estado</h6>
                                        <span class="badge bg-success fs-5">
                                            Terminado
                                        </span>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <a href="{{ route('evaluaciones-ibt.editar-calificacion', $evaluacion->id) }}"
                                    class="btn btn-outline-success btn-lg">
                                        ✏️ Editar calificación final
                                    </a>
                                </div>

                            {{-- SI AÚN NO ESTÁ TERMINADA --}}
                            @else

                                @php
                                    $puntajeCalculado = $evaluacion->calcularPuntajeTotal();
                                @endphp

                                <div class="row text-center mb-3">
                                    <div class="col-md-6">
                                        <h6>Puntaje Calculado</h6>
                                        <span class="badge bg-warning text-dark fs-4">
                                            {{ $puntajeCalculado }} pts
                                        </span>
                                    </div>

                                    <div class="col-md-6">
                                        <h6>Estado</h6>
                                        <span class="badge bg-warning text-dark fs-5">
                                            En proceso
                                        </span>
                                    </div>
                                </div>

                                <form method="POST"
                                    action="{{ route('evaluaciones-ibt.finalizar', $evaluacion->id) }}"
                                    onsubmit="return confirm('¿Desea finalizar la evaluación IBT?');">
                                    @csrf
                                    @method('PUT')

                                    <div class="text-center">
                                        <button class="btn btn-success btn-lg">
                                            ✅ Finalizar Evaluación IBT
                                        </button>
                                    </div>
                                </form>

                            @endif

                        </div>
                    </div>



                </div>
            </div>

            {{-- BOTONES --}}
            <div class="text-center">
                <a href="{{ route('evaluaciones-ibt.index') }}" class="btn btn-outline-secondary btn-lg">
                    ⬅️ Volver al listado
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
