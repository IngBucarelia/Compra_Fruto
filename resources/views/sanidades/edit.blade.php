@extends('layouts.app')

@section('content')

<style>
.container{
    background-color: rgba(129, 165, 114, 0.929);
    padding: 20px;
}

.title{
    text-align: center; 
    font-family: Arial Black; 
    font-weight: bold; 
    font-size: 30px; 
    color: #fdffe5; 
    text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
}

@media (max-width: 968px) {

         .container.offline-form-container {
        background-color: rgba(129, 165, 114, 0.929); /* Color de fondo específico para este formulario */
    }
        .button-group-top {
            flex-direction: row;
            justify-content: flex-start;
        }

         .container.offline-form-container {
        padding: 15px;
            margin-top: 15px;
            border-radius: 0;
            box-shadow: none;
            width: 123%;
            max-width: none;
            margin-left: -60px !important;
    }

    .title{
    text-align: center;
    font-family: Arial Black;
    font-weight: bold;
    font-size: 30px;
    color: #fdffe5;
    text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
    margin-bottom: 25px;
}

 .container {
        margin-left: -70px;
        width: 125%;
    

    }

        .dashboard-content {
            max-width: 100%;
        }
        .dashboard-card {
            margin-bottom: 15px;
        }

        .card{
        width: 100%;
    }
    }
</style>

<div class="container">
    <h3 class="title">✏️ Editar Sanidad</h3><h3> - {{ $visita->proveedor->proveedor_nombre }}</h3>

    <form method="POST" action="{{ route('sanidades.update', $sanidad->id) }}">
        @csrf
        @method('PUT')

        <!-- Enfermedades dinámicas -->
        <div id="enfermedades-container" class="mb-4">
            <h5>Enfermedades</h5>
            <button type="button" class="btn btn-sm btn-primary mb-2" onclick="addEnfermedad()">➕ Agregar enfermedad</button>
        </div>

        <!-- Plagas dinámicas -->
        <div id="plagas-container" class="mb-4">
            <h5>Plagas</h5>
            <button type="button" class="btn btn-sm btn-primary mb-2" onclick="addPlaga()">➕ Agregar plaga</button>
        </div>

        <!-- Otros campos -->
        <div class="form-group mb-3">
            <label for="otros">Otros:</label>
            <input type="text" name="otros" class="form-control" value="{{ old('otros', $sanidad->otros) }}">
        </div>

        <div class="form-group mb-3">
            <label for="observaciones">Observaciones:</label>
            <textarea name="observaciones" class="form-control">{{ old('observaciones', $sanidad->observaciones) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">💾 Actualizar</button>
        <a href="{{ route('sanidades.create', ['visita_id' => $visita->id]) }}" class="btn btn-secondary">↩️ Cancelar</a>
    </form>
</div>

<script>
  let currentEnfermedadIndex = 0;
  let currentPlagaIndex = 0;

  function addEnfermedad(nombre = '', estado = '') {
    const container = document.getElementById('enfermedades-container');
    const group = document.createElement('div');
    group.classList.add('row','gx-2','align-items-end','mb-2');
    const uniqueId = `enfermedad_${currentEnfermedadIndex}`;
    group.innerHTML = `
      <div class="col-md-5">
        <label for="${uniqueId}_nombre">Enfermedad:</label>
        <input type="text" name="enfermedades[${currentEnfermedadIndex}][nombre]" id="${uniqueId}_nombre" class="form-control" value="${nombre}">
      </div>
      <div class="col-md-5">
        <label for="${uniqueId}_estado">Estado:</label>
        <input type="text" name="enfermedades[${currentEnfermedadIndex}][estado]" id="${uniqueId}_estado" class="form-control" value="${estado}">
      </div>
      <div class="col-md-2 d-grid">
        <button type="button" class="btn btn-danger" onclick="this.closest('.row').remove()">✖️</button>
      </div>`;
    container.appendChild(group);
    currentEnfermedadIndex++;
  }

  function addPlaga(nombre = '', estado = '') {
    const container = document.getElementById('plagas-container');
    const group = document.createElement('div');
    group.classList.add('row','gx-2','align-items-end','mb-2');
    const uniqueId = `plaga_${currentPlagaIndex}`;
    group.innerHTML = `
      <div class="col-md-5">
        <label for="${uniqueId}_nombre">Plaga:</label>
        <input type="text" name="plagas[${currentPlagaIndex}][nombre]" id="${uniqueId}_nombre" class="form-control" value="${nombre}">
      </div>
      <div class="col-md-5">
        <label for="${uniqueId}_estado">Estado:</label>
        <input type="text" name="plagas[${currentPlagaIndex}][estado]" id="${uniqueId}_estado" class="form-control" value="${estado}">
      </div>
      <div class="col-md-2 d-grid">
        <button type="button" class="btn btn-danger" onclick="this.closest('.row').remove()">✖️</button>
      </div>`;
    container.appendChild(group);
    currentPlagaIndex++;
  }

  document.addEventListener('DOMContentLoaded', function() {
    // precargar enfermedades desde backend
    @foreach ($sanidad->enfermedades as $enf)
      addEnfermedad("{{ $enf->nombre }}", "{{ $enf->estado }}");
    @endforeach

    // precargar plagas desde backend
    @foreach ($sanidad->plagas as $plaga)
      addPlaga("{{ $plaga->nombre }}", "{{ $plaga->estado }}");
    @endforeach

    // Si no hay ninguna, al menos mostrar un input vacío
    @if ($sanidad->enfermedades->count() === 0)
      addEnfermedad();
    @endif
    @if ($sanidad->plagas->count() === 0)
      addPlaga();
    @endif
  });
</script>
@endsection
