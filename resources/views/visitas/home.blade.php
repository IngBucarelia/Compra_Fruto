@extends('layouts.app')

@section('content')
<style>
   
    .dashboard-container {
        background-color: #909176be; /* Color de fondo del contenedor principal */
        border-radius: 20px;
        padding: 20px; /* Añadido padding para espacio interno */
        margin-top: -30px; /* Espacio superior */
        margin-bottom: 20px; /* Espacio inferior */
    }

    .dashboard-content {
        width: 100%; /* Ocupa todo el ancho disponible dentro del container */
    }

    .dashboard-card {
        background: rgba(129, 165, 114, 0.614);
        border: none;
        border-radius: 10px;
        transition: 0.3s;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        text-align: center;
        margin-bottom: 20px; /* Espacio entre cards */
        height: 100%; /* Asegura que las tarjetas tengan la misma altura en una fila */
        display: flex; /* Para centrar contenido verticalmente si es necesario */
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
    }

    .dashboard-icon {
        font-size: 3em;
        margin-bottom: 10px;
    }

    .dashboard-title {
        font-size: 1.2em;
        font-weight: bold;
        color: #333;
        margin-top: 10px; /* Espacio entre imagen y título */
    }

    .dashboard-link {
        text-decoration: none;
        color: inherit;
        display: block; /* Para que el enlace ocupe todo el espacio de la columna */
        height: 100%; /* Asegura que el enlace ocupe toda la altura de la tarjeta */
    }

    .dashboard-header {
        font-size: 1.8em;
        font-weight: bold;
        margin-bottom: 30px;
        text-align: center;
        color: #2c3e50;
        width: 100%;
    }

    .dashboard-card img.logo {
        max-width: 200px; /* Tamaño fijo para los logos */
        height: 200px;
        object-fit: cover; /* Asegura que la imagen se vea bien */
    }
    .button-33 {
  background-color: #073c1a;
  border-radius: 100px;
  box-shadow: rgba(44, 187, 99, .2) 0 -25px 18px -14px inset,rgba(44, 187, 99, .15) 0 1px 2px,rgba(44, 187, 99, .15) 0 2px 4px,rgba(44, 187, 99, .15) 0 4px 8px,rgba(44, 187, 99, .15) 0 8px 16px,rgba(44, 187, 99, .15) 0 16px 32px;
  color: rgb(241, 243, 241);
  cursor: pointer;
  display: inline-block;
  font-family: CerebriSans-Regular,-apple-system,system-ui,Roboto,sans-serif;
  padding: 7px 20px;
  text-align: center;
  text-decoration: none;
  transition: all 250ms;
  border: 0;
  font-size: 16px;
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
}

.button-33:hover {
  box-shadow: rgba(44,187,99,.35) 0 -25px 18px -14px inset,rgba(44,187,99,.25) 0 1px 2px,rgba(44,187,99,.25) 0 2px 4px,rgba(44,187,99,.25) 0 4px 8px,rgba(44,187,99,.25) 0 8px 16px,rgba(44,187,99,.25) 0 16px 32px;
  transform: scale(1.05) rotate(-1deg);
}
.dashboard-header{
    text-align: center; 
font-family: Arial Black; 
font-weight: bold; 
font-size: 30px; 
color: #fdffe5; 
text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
}
.dashboard-title{
    text-align: center; 
font-family: Arial Black; 
font-weight: bold; 
font-size: 20px; 
color: #fdffe5; 
text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
}

    /* Media query para pantallas pequeñas (móviles) */
    @media (max-width: 767.98px) { /* Bootstrap's 'sm' breakpoint is 576px, 'md' is 768px */
        .dashboard-container {
            margin-left: 0; /* Eliminar margen negativo en móvil */
            width: 100%;
            padding: 15px;
            margin-left: -30px
 /* Ajustar padding para pantallas pequeñas */
        }
        .dashboard-card {
            margin-bottom: 15px; /* Espacio entre tarjetas en móvil */
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

<div class="container dashboard-container">
    <div class="dashboard-content">
        <div class="dashboard-header">
            Area de visitas Componente Agronomico<br> En Sesion :  {{ auth()->user()->name }}
        </div>

        {{-- ✅ CAMBIO CLAVE: Usamos un row de Bootstrap para la cuadrícula --}}
        <div class="row">
            {{-- Cada elemento se envuelve en una columna --}}
            {{-- col-12: Ocupa todo el ancho en pantallas extra-pequeñas y pequeñas (móvil) --}}
            {{-- col-md-6: Ocupa la mitad del ancho en pantallas medianas y grandes (2 columnas) --}}

            <div class="col-12 col-md-6 mb-4"> {{-- mb-4 añade margen inferior entre filas --}}
                <a href="{{ route('dashboard.visitas.agro') }}" class="dashboard-link">
                    <div class="dashboard-card p-4">
                        <div class="dashboard-title">Dashboard Visitas</div><br>
                        <img class="logo" src="{{ asset('images/visitas.webp') }}" style="border-radius: 30px" alt="Listado de Visitas"><br>
                        <!-- HTML !-->
                    <button class="button-33" role="button">Ir a Visitas</button>
                    </div>
                    
                </a>
            </div>

            

            <div class="col-12 col-md-6 mb-4">
                <a href="{{ route('planificaciones.calendario') }}" class="dashboard-link">
                    <div class="dashboard-card p-4">
                        <div class="dashboard-title">Calendario de Visitas</div><br>
                        <img class="logo" src="{{ asset('images/calendario.webp') }}" style="border-radius: 30px" alt="Calendario de Visitas">
                        <br><button class="button-33" role="button">Ir a Calendario</button>
                    </div>
                </a>
            </div>
            
            <div class="col-12 mt-4 d-md-none">
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dashboard-link">
                    <div class="dashboard-card p-4 text-center">
                        <div class="dashboard-title">🚪 Cerrar Sesión</div>
                        <p class="mt-2 mb-0">Cerrar la sesión actual</p>
                    </div>
                </a>
            </div>
            
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</div>
@endsection