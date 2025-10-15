@extends('layouts.app')

@section('content')
<div
    id="offline-app"
    data-visita-id="{{ $visita_id }}"
    data-area='@json($area)'
></div>
@endsection
