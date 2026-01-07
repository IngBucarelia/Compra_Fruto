<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>IBT_{{ $evaluacion->id }}.xls</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 5px; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .total { background-color: #e0e0e0; font-weight: bold; }
    </style>
</head>
<body>
    <h1>EVALUACIÓN IBT - {{ $evaluacion->proveedor->proveedor_nombre }}</h1>
    
    <table>
        <tr>
            <td><strong>Predio/Lote:</strong></td>
            <td>{{ $evaluacion->plantacion->nombre ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Proveedor:</strong></td>
            <td>{{ $evaluacion->proveedor->proveedor_nombre }}</td>
        </tr>
        <tr>
            <td><strong>Fecha Evaluación:</strong></td>
            <td>{{ $evaluacion->fecha_evaluacion }}</td>
        </tr>
        <tr>
            <td><strong>Técnico:</strong></td>
            <td>{{ $evaluacion->tecnico->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Puntaje Total:</strong></td>
            <td>{{ $evaluacion->puntaje_total }}</td>
        </tr>
        <tr>
            <td><strong>Calificación:</strong></td>
            <td>{{ $evaluacion->calificacion }}</td>
        </tr>
    </table>
    
    <br>
    
    <table>
        <thead>
            <tr>
                <th>Componente</th>
                <th>Criterio</th>
                <th>Condición Deseable</th>
                <th>Puntaje Máx.</th>
                <th>Puntaje Obtenido</th>
            </tr>
        </thead>
        <tbody>
            @foreach($componentesConRespuestas as $componenteData)
            @foreach($componenteData['respuestas'] as $respuesta)
            <tr>
                <td>{{ $componenteData['componente']->nombre }}</td>
                <td>{{ $respuesta->subcomponente->nombre }}</td>
                <td>{{ $respuesta->subcomponente->condicion_deseable }}</td>
                <td>{{ $respuesta->subcomponente->puntaje_maximo }}</td>
                <td>{{ $respuesta->calificacion_actual }}</td>
            </tr>
            @endforeach
            <tr class="total">
                <td colspan="3">Subtotal {{ $componenteData['componente']->nombre }}</td>
                <td>{{ $componenteData['componente']->puntaje_maximo }}</td>
                <td>{{ $componenteData['puntaje_obtenido'] }}</td>
            </tr>
            @endforeach
            <tr class="total">
                <td colspan="3">TOTAL GENERAL</td>
                <td>100</td>
                <td>{{ $evaluacion->puntaje_total }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>