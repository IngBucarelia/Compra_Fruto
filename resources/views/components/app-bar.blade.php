<nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm fixed-top" >
    <div class="container-fluid" style="margin-bottom: -15px">
        <button 
            class="navbar-toggler" 
            type="button" 
            @click="sidebarOpen = !sidebarOpen"
        >
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <span style="font-size: 2em;">🌴</span>
    <span style="font-size: 2em;">🛢️</span>

	 App Compra de Fruto<span style="font-size: 2em;">🚜</span>

        </a>
        
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
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