<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Envío #{{ $envio->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        .titulo { text-align: center; font-size: 20px; color: #198754; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .firmas, .imagenes { margin-top: 30px; }
        .firma-img { border: 1px solid #ccc; max-height: 120px; display: block; margin-top: 5px; }
        .img-thumb { width: 180px; height: auto; margin: 5px; border: 1px solid #ddd; border-radius: 4px; }
        .imagenes-grid { display: flex; flex-wrap: wrap; justify-content: flex-start; }
    </style>
</head>
<body>
    <h1 class="titulo">📦 Detalles del Envío #{{ $envio->id }}</h1>

    <table>
        <tr><th>Plantación</th><td>{{ $envio->plantacion->nombre ?? 'Sin plantación' }}</td></tr>
        <tr><th>Proveedor</th><td>{{ $envio->proveedor->proveedor_nombre ?? 'Sin proveedor' }}</td></tr>
        <tr><th>Técnico Encargado</th><td>{{ $envio->tecnico->name ?? 'No asignado' }}</td></tr>
        <tr><th>Descripción</th><td>{{ $envio->descripcion_envio ?? 'N/A' }}</td></tr>
        <tr><th>Fecha</th><td>{{ optional($envio->fecha_envio)->format('d/m/Y') ?? 'Sin fecha' }}</td></tr>
        <tr><th>Estado</th><td>{{ ucfirst($envio->estado) }}</td></tr>
    </table>

    @if(isset($envio->comentarios_finales))
        <h3>📝 Comentarios Finales</h3>
        <p>{{ $envio->comentarios_finales }}</p>
    @endif

    {{-- SECCIÓN DE EVIDENCIAS --}}
    @if($envio->evidencias && $envio->evidencias->count())
        <div class="imagenes">
            <h3>📸 Evidencias del Envío</h3>
            <div class="imagenes-grid">
                @foreach($envio->evidencias as $evidencia)
                    @php
                        $ruta = public_path($evidencia->archivo);
                        if (!file_exists($ruta)) $ruta = str_replace('/home2/bucareli/public_html/', public_path('/'), $evidencia->archivo);
                    @endphp
                    @if(file_exists($ruta))
                        <img src="{{ $ruta }}" class="img-thumb" alt="Evidencia">
                    @endif
                @endforeach
            </div>
        </div>
    @else
        <p><em>No hay evidencias registradas para este envío.</em></p>
    @endif

    {{-- SECCIÓN DE FIRMAS --}}
    <div class="firmas">
        <h3>✍️ Firmas</h3>
        <table>
            <tr>
                <th>Entrega</th>
                <th>Recibe</th>
            </tr>
            <tr>
                <td>
                    @if($envio->firma_entrega)
                        <img src="{{ public_path($envio->firma_entrega) }}" class="firma-img">
                    @else
                        No registrada
                    @endif
                </td>
                <td>
                    @if($envio->firma_recibe)
                        <img src="{{ public_path($envio->firma_recibe) }}" class="firma-img">
                    @else
                        No registrada
                    @endif
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
