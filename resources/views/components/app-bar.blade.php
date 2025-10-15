<nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm fixed-top" style="background-color:  rgba(29, 89, 7, 0.9)  !important">
    <div class="container-fluid" style="margin-bottom: -15px">
        
       <span style="font-size: 2em;">🌴</span>
        <a href="{{ route('dashboard') }}">
            <img class="logo" src="{{ asset('images/logo.webp') }}" alt="Logo">
        </a>
        <span style="font-size: 2em;">🚜</span>
        <style>
            .logo{
                width: 200px;
                margin-top: -10px;
            }
        </style>
        
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" style="color: black">
                        <i class="fas fa-user-circle me-1"></i> {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-1"></i> Configuración</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-1"></i> Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>