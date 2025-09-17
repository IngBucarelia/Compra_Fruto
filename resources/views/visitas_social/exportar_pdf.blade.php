<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Visita Social - {{ $visita->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        h1, h2 { color: #2F4F4F; margin-bottom: 5px; }
        .section { margin-bottom: 18px; }
        .section h2 { border-bottom: 1px solid #ccc; padding-bottom: 4px; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        table th, table td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        .data-card { padding: 8px; margin-bottom: 8px; border-radius: 4px; background: #f7f7f7; }
        .img-thumb { max-width: 260px; max-height: 260px; margin: 6px; display:inline-block; }
    </style>
</head>
<body>

    <h1>Detalle Visita Social - #{{ $visita->id }}</h1>
    <p><strong>Fecha:</strong> {{ $visita->fecha ?? 'N/A' }}</p>
    <p><strong>Proveedor:</strong> {{ $visita->proveedor->proveedor_nombre ?? 'N/A' }}</p>
    <hr>

    {{-- Datos Personales --}}
    <div class="section">
        <h2>👤 Datos Personales</h2>
        <div class="data-card">
            <<p><strong>Proveedor:</strong> {{ $visita->datosPersonales->proveedor ? $visita->datosPersonales->proveedor->proveedor_nombre : 'N/A' }}</p>
                        <p><strong>Teléfono:</strong> {{ $visita->datosPersonales->telefono ?? 'N/A' }}</p>
                        <p><strong>Sexo:</strong> {{ $visita->datosPersonales->sexo ?? 'N/A' }}</p>
                        <p><strong>RNP:</strong> {{ $visita->datosPersonales->rnp ?? 'N/A' }}</p>
                        <p><strong>Fedepalma:</strong> {{ $visita->datosPersonales->fedepalma ?? 'N/A' }}</p>
                        <p><strong>Alfabetizado:</strong> {{ $visita->datosPersonales->alfabetizado ?? 'N/A' }}</p>
                        <p><strong>Nivel de estudio:</strong> {{ $visita->datosPersonales->nivel_estudio ?? 'N/A' }}</p>
                        <p><strong>Otras líneas:</strong> {{ $visita->datosPersonales->otras_lineas ?? 'N/A' }}</p>
                        <p><strong>Fecha de nacimiento:</strong> {{ $visita->datosPersonales->fecha_nacimiento ?? 'N/A' }}</p>
                        <p><strong>Grupo poblacional:</strong> {{ $visita->datosPersonales->grupo_poblacional ?? 'N/A' }}</p>
                        <p><strong>Reside en el predio:</strong> {{ $visita->datosPersonales->reside_predio ?? 'N/A' }}</p>
                        <p><strong>Administra cultivo:</strong> {{ $visita->datosPersonales->administra_cultivo ?? 'N/A' }}</p>
                        <p><strong>Supervisa cultivo:</strong> {{ $visita->datosPersonales->supervisa_cultivo ?? 'N/A' }}</p>
                        <p><strong>Realiza cultivo:</strong> {{ $visita->datosPersonales->realiza_cultivo ?? 'N/A' }}</p>
                        <p><strong>Años en palmicultura:</strong> {{ $visita->datosPersonales->anios_palmicultura ?? 'N/A' }}</p>
                        <p><strong>Internet:</strong> {{ $visita->datosPersonales->internet ?? 'N/A' }}</p>
                        <p><strong>Tipo de persona:</strong> {{ $visita->datosPersonales->tipo_persona ?? 'N/A' }}</p>
                        <p><strong>Red social:</strong> {{ $visita->datosPersonales->red_social ?? 'N/A' }}</p>
                        <p><strong>Régimen de salud:</strong> {{ $visita->datosPersonales->regimen_salud ?? 'N/A' }}</p>
        </div>
    </div>

    {{-- Miembros --}}
    <div class="section">
        <h2>👨‍👩‍👧‍👦 Miembros del Hogar</h2>
       <h2 class="accordion-header" id="headingMiembros">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMiembros">
                    👨‍👩‍👧‍👦 Miembros del Hogar
                </button>
            </h2>
            <div id="collapseMiembros" class="accordion-collapse collapse" data-bs-parent="#accordionVisita">
                <div class="accordion-body">
                    @if($visita->miembros->count())
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-success">
                                    <tr>
                                        <th>👤 Nombre</th>
                                        <th>🪪 Documento</th>
                                        <th>⚧ Sexo</th>
                                        <th>👨‍👩‍👧 Parentesco</th>
                                        <th>🏡 Reside en el Predio</th>
                                        <th>📖 Sabe Leer</th>
                                        <th>🎓 Nivel de Estudio</th>
                                        <th>🌱 Participa en Labores</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($visita->miembros as $miembro)
                                        <tr>
                                            <td>{{ $miembro->nombre }}</td>
                                            <td>{{ $miembro->documento }}</td>
                                            <td>{{ $miembro->sexo }}</td>
                                            <td>{{ $miembro->parentezco }}</td>
                                            <td>{{ $miembro->reside_predio == 'si' ? '✅ Sí' : '❌ No' }}</td>
                                            <td>{{ $miembro->sabe_leer == 'si' ? '✅ Sí' : '❌ No' }}</td>
                                            <td>{{ $miembro->nivel_estudio }}</td>
                                            <td>{{ $miembro->participa_labores == 'si' ? '✅ Sí' : '❌ No' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>No hay miembros registrados.</p>
                    @endif
                </div>
    </div>

    {{-- Predio / Plantaciones --}}
    <div class="section">
        <h2>🏡 Datos del Predio / Plantaciones</h2>

        @if(isset($plantaciones) && $plantaciones->count())
            @foreach($plantaciones as $plantacion)
                <div class="data-card">
                    <h4>Plantación: {{ $plantacion->nombre }}</h4>

                    @php
                        $dato = $datos->where('plantacion_id', $plantacion->id)->first();
                    @endphp

                    @if($dato)
                        <p><b>Nombre finca:</b> {{ $dato->nombre_finca ?? 'N/A' }}</p>
                        <p><b>Forma Tenencia:</b> {{ $dato->forma_tenencia ?? 'N/A' }}</p>
                        <p><b>Municipio:</b> {{ $dato->municipio ?? 'N/A' }}</p>
                        <p><b>Vereda:</b> {{ $dato->vereda ?? 'N/A' }}</p>
                        <p><b>Registro ICA:</b> {{ $dato->registrado_ica ?? 'N/A' }}</p>
                        <p><b>Vive en el predio:</b> {{ $dato->vive_predio ?? 'N/A' }}</p>
                    @else
                        <p>No hay datos del predio para esta plantación.</p>
                    @endif
                </div>
            @endforeach
        @else
            <p>No hay plantaciones relacionadas al proveedor/visita.</p>
        @endif
    </div>

    {{-- Fuerza Laboral --}}
    <div class="section">
        <h2>🧑‍🌾 Fuerza Laboral</h2>
        @if($visita->fuerzaLaboral && $visita->fuerzaLaboral->count())
            <table>
                <thead>
                    <tr>
                        <th>#</th><th>Forma contratación</th><th>Trabajadores</th><th>Hombres</th><th>Mujeres</th>
                        <th>Contrato Formal</th><th>Seguridad Social</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($visita->fuerzaLaboral as $i => $f)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>
                                @if(is_array($f->forma_contratacion))
                                    {{ implode(', ', $f->forma_contratacion) }}
                                @else
                                    {{ $f->forma_contratacion }}
                                @endif
                            </td>
                            <td>{{ $f->num_trabajadores ?? 'N/A' }}</td>
                            <td>{{ $f->num_hombres ?? 'N/A' }}</td>
                            <td>{{ $f->num_mujeres ?? 'N/A' }}</td>
                            <td>{{ $f->contrato_formal == 'si' ? 'Sí' : 'No' }}</td>
                            <td>{{ $f->seguridad_social == 'si' ? 'Sí' : 'No' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No hay registros de fuerza laboral.</p>
        @endif
    </div>

    {{-- Organización Social --}}
    <div class="section">
        <h2>👥 Organización Social</h2>
        @if($visita->organizacionSocial)
            <div class="data-card">
                <p><b>Pertenece a JAC:</b> {{ $visita->organizacionSocial->pertenece_jac ? 'Sí' : 'No' }}</p>
                <p><b>Pertenece a Asociación:</b> {{ $visita->organizacionSocial->pertenece_asociacion ? 'Sí' : 'No' }}</p>
                <p><b>Nombre Asociación:</b> {{ $visita->organizacionSocial->nombre_asociacion ?? 'N/A' }}</p>
            </div>
        @else
            <p>No hay datos de organización social.</p>
        @endif
    </div>

   {{-- Cierre de Visita Social --}}
<div class="section">
    <h2>✅ Cierre de Visita Social</h2>

    @if($visita->cierreVisitaSocial)
        <p><strong>📅 Fecha de Cierre:</strong> {{ $visita->cierreVisitaSocial->fecha_cierre ?? 'N/A' }}</p>
        <p><strong>📌 Estado:</strong>
            @if($visita->cierreVisitaSocial->estado_visita == 'completado')
                ✅ Completado
            @elseif($visita->cierreVisitaSocial->estado_visita == 'pendiente')
                ⏳ Pendiente
            @else
                ❌ Cancelado
            @endif
        </p>
        <p><strong>📝 Observaciones Finales:</strong> {{ $visita->cierreVisitaSocial->observaciones_finales ?? 'N/A' }}</p>
        <p><strong>💡 Recomendaciones:</strong> {{ $visita->cierreVisitaSocial->recomendaciones ?? 'N/A' }}</p>

        {{-- Firmas --}}
        <table width="100%" style="margin-top:20px; text-align:center;">
            <tr>
                <td>
                    <h4>✍️ Firma Responsable</h4>
                    @if($visita->cierreVisitaSocial->firma_responsable)
                        <img src="{{ public_path($visita->cierreVisitaSocial->firma_responsable) }}" style="max-height:120px; max-width:200px;" alt="Firma Responsable">
                    @else
                        <p>No registrada</p>
                    @endif
                </td>
                <td>
                    <h4>✍️ Firma Recibe</h4>
                    @if($visita->cierreVisitaSocial->firma_recibe)
                        <img src="{{ public_path($visita->cierreVisitaSocial->firma_recibe) }}" style="max-height:120px; max-width:200px;" alt="Firma Recibe">
                    @else
                        <p>No registrada</p>
                    @endif
                </td>
                <td>
                    <h4>✍️ Firma Testigo</h4>
                    @if($visita->cierreVisitaSocial->firma_testigo)
                        <img src="{{ public_path($visita->cierreVisitaSocial->firma_testigo) }}" style="max-height:120px; max-width:200px;" alt="Firma Testigo">
                    @else
                        <p>No registrada</p>
                    @endif
                </td>
            </tr>
        </table>

        {{-- Imágenes del cierre --}}
        @php
            $imagenes = is_string($visita->cierreVisitaSocial->imagenes)
                ? json_decode($visita->cierreVisitaSocial->imagenes, true)
                : $visita->cierreVisitaSocial->imagenes;
        @endphp

        @if(!empty($imagenes))
            <h3 style="margin-top:25px;">📸 Imágenes del Cierre</h3>
            <table width="100%">
                <tr>
                    @foreach($imagenes as $i => $imagen)
                        <td style="text-align:center; padding:10px;">
                            <img src="{{ public_path($imagen) }}" style="max-width:200px; max-height:200px;" alt="Foto cierre">
                        </td>
                        @if(($i+1) % 3 == 0)
                            </tr><tr>
                        @endif
                    @endforeach
                </tr>
            </table>
        @endif
    @else
        <p>No se ha registrado el cierre de esta visita.</p>
    @endif
</div>


</body>
</html>
