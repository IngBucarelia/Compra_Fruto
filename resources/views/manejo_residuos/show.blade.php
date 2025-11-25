@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Detalle de Manejo de Residuos #{{ $item->id }}</h3>

    <table class="table table-bordered">
        <tr><th>Visita</th><td>{{ $item->visita_ambiental_id }}</td></tr>
        <tr><th>Capacitación</th><td>{{ $item->capacitacion_personal }}</td></tr>
        <tr><th>Clasificación</th><td>{{ $item->conoce_clasificacion_residuos }}</td></tr>
        <tr><th>Certificados RESPEL</th><td>{{ $item->certificados_respel }}</td></tr>
        <tr><th>Manifiesto RESPEL</th><td>{{ $item->manifiesto_transporte_respel }}</td></tr>
        <tr><th>Puntos ecológicos</th><td>{{ $item->puntos_ecologicos }}</td></tr>
        <tr><th>Entrega autorizada</th><td>{{ $item->entrega_residuos_transportador_autorizado }}</td></tr>
        <tr><th>Disposición final autorizada</th><td>{{ $item->disposicion_final_empresa_autorizada }}</td></tr>
        <tr><th>Acciones minimizar</th><td>{{ $item->acciones_minimizacion_impactos }}</td></tr>
        <tr><th>Certificado final</th><td>{{ $item->certificado_disposicion_final }}</td></tr>
        <tr><th>Aprovechables gestionados</th><td>{{ $item->aprovechables_gestionados }}</td></tr>
        <tr><th>Peso y registro</th><td>{{ $item->pesa_registra_cantidades }}</td></tr>
        <tr><th>Observaciones</th><td>{{ $item->observaciones }}</td></tr>
    </table>

    <a href="{{ route('manejo_residuos.index') }}" class="btn btn-secondary">Volver</a>
</div>
@endsection
