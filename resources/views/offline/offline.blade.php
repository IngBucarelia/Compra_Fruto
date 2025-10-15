@extends('layouts.app')

@section('content')
<div id="offline-app" data-visita-id="{{ request()->query('visita_id') }}"></div>

@endsection
