@extends('layouts.app')

@section('content')
<div id="app"></div>

<script>
    localStorage.setItem('visita_activa', JSON.stringify(@json($visita)));
</script>

@vite('resources/js/app.js')
@endsection
