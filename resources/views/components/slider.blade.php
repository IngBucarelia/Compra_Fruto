<div 
    class="sidebar bg-dark text-white" style="background-color:  rgba(29, 89, 7, 0.9) !important; margin-top:47px"
    :class="{ 'sidebar-collapsed': !sidebarOpen }"
    x-data="{
        sidebarOpen: window.innerWidth > 768,
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
        },
        closeOnMobile() {
            if (window.innerWidth <= 768) {
                this.sidebarOpen = false;
            }
        }
    }"
    @resize.window="sidebarOpen = window.innerWidth > 768"
    style="width: 12%; min-width: 50px; position: fixed; height: 100vh; z-index: 1000; transition: all 0.3s ease;"
>
    <!-- Botón flotante para móviles (parte inferior derecha) -->
    <div class="mobile-menu-button" 
         style="position: fixed; bottom: 20px; right: 20px; z-index: 1100; display: none;"
         x-show="!sidebarOpen && window.innerWidth <= 768"
         @click="toggleSidebar()">
        <button class="btn btn-lg rounded-circle shadow" style="background: #2a9b48; color: white; border: 2px solid white; width: 56px; height: 56px;">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Overlay para cerrar el menú en móviles -->
    <div class="sidebar-overlay"
         style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 999; display: none;"
         x-show="sidebarOpen && window.innerWidth <= 768"
         @click="closeOnMobile()">
    </div>

    <!-- Resto del código del sidebar permanece igual -->
    <div class="sidebar-header p-3 d-flex justify-content-between align-items-center">
        <h5 x-show="sidebarOpen" class="mb-0">Menú</h5>
        <button @click="toggleSidebar()" class="btn btn-sm btn-outline-light">
            <i class="fas" :class="{ 'fa-chevron-left': sidebarOpen, 'fa-chevron-right': !sidebarOpen }"></i>
        </button>
    </div>
    <strong> 
        <a class="navbar-brand" href="{{ route('dashboard') }}" style="margin-left: 25px;color:aliceblue;align:center" x-show="sidebarOpen">
            Compra Fruto
        </a>
        <br><br>
    </strong>
    
    <ul class="sidebar-menu">
        <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="sidebar-link">
                <i class="fas fa-tachometer-alt me-2"></i>
                <span x-show="sidebarOpen">Dashboard</span>
            </a>
        </li>
        
        <li class="sidebar-item has-submenu 
    {{ request()->routeIs('proveedores.*') || request()->routeIs('plantaciones.*') ? 'active' : '' }}">
    
    <a href="#zonaAdmonSubmenu" data-bs-toggle="collapse" class="sidebar-link collapsed">
        <i class="fas fa-cogs me-2"></i>
        <span x-show="sidebarOpen">Zona Admon</span>
    </a>
{{-- ZONA ADMIN — solo para rol 1 --}}
{{-- ZONA VISITAS — solo para roles 1 y 2 --}}
@if(Auth::check() && in_array(Auth::user()->rol, [1, 4]))
    <ul id="zonaAdmonSubmenu" class="sidebar-submenu collapse 
        {{ request()->routeIs('proveedores.*') || request()->routeIs('plantaciones.*') ? 'show' : '' }}" 
        data-bs-parent="#sidebarMenu">
        
        {{-- Submenú Proveedores --}}
        <li class="sidebar-item has-submenu {{ request()->routeIs('proveedores.*') ? 'active' : '' }}">
            <a href="#proveedoresSubmenu" data-bs-toggle="collapse" class="sidebar-link collapsed">
                <i class="fas fa-truck me-2"></i>
                <span x-show="sidebarOpen">Proveedores</span>
            </a>
            <ul id="proveedoresSubmenu" class="sidebar-submenu collapse {{ request()->routeIs('proveedores.*') ? 'show' : '' }}">
                <li class="sidebar-item">
                    <a href="{{ route('proveedores.index') }}" class="sidebar-link">
                        <span x-show="sidebarOpen">Listar Proveedores</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('proveedores.import.form') }}" class="sidebar-link">
                        <span x-show="sidebarOpen">Importar Proveedores</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Submenú Plantaciones --}}
        <li class="sidebar-item has-submenu {{ request()->routeIs('plantaciones.*') ? 'active' : '' }}">
            <a href="#plantacionesSubmenu" data-bs-toggle="collapse" class="sidebar-link collapsed">
                <i class="fas fa-seedling me-2"></i>
                <span x-show="sidebarOpen">Plantaciones</span>
            </a>
            <ul id="plantacionesSubmenu" class="sidebar-submenu collapse {{ request()->routeIs('plantaciones.*') ? 'show' : '' }}">
                <li class="sidebar-item">
                    <a href="{{ route('plantaciones.index') }}" class="sidebar-link">
                        <span x-show="sidebarOpen">Listar Plantaciones</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('plantaciones.create') }}" class="sidebar-link">
                        <span x-show="sidebarOpen">Importar Plantaciones</span>
                    </a>
                </li>
            </ul>
        </li>

        @if(Auth::check() && Auth::user()->rol == 1)
        <li class="sidebar-item has-submenu {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
            <a href="#usuariosSubmenu" data-bs-toggle="collapse" class="sidebar-link collapsed">
                <i class="fas fa-user me-2"></i>
                <span x-show="sidebarOpen">Usuarios</span>
            </a>
            <ul id="usuariosSubmenu" class="sidebar-submenu collapse {{ request()->routeIs('usuarios.*') ? 'show' : '' }}">
                <li class="sidebar-item">
                    <a href="{{ route('register') }}" class="sidebar-link">
                        <span x-show="sidebarOpen">Agregar Usuario</span>
                    </a>
                </li>
                
            </ul>
        </li>
           @endif

    </ul>
    @endif

</li>

        @if(Auth::check() && in_array(Auth::user()->rol, [1, 2, 3 ,4]))
        
    <li class="sidebar-item has-submenu 
        {{ request()->routeIs('visitas.*') || request()->routeIs('visitas_social.*') ? 'active' : '' }}">
        
        <a href="#visitasSubmenu" data-bs-toggle="collapse" class="sidebar-link collapsed">
            <i class="fas fa-clipboard-list me-2"></i>
            <span x-show="sidebarOpen">Visitas</span>
        </a>

        <ul id="visitasSubmenu" class="sidebar-submenu collapse 
            {{ request()->routeIs('visitas.*') || request()->routeIs('visitas_social.*') ? 'show' : '' }}" 
            data-bs-parent="#sidebarMenu">

            {{-- Submenú Visita Agronómica --}}
            <li class="sidebar-item has-submenu {{ request()->routeIs('visitas.*') ? 'active' : '' }}">
                <a href="#visitaAgroSubmenu" data-bs-toggle="collapse" class="sidebar-link collapsed">
                    <i class="fas fa-seedling me-2"></i>
                    <span x-show="sidebarOpen">Visita - Agronómica</span>
                </a>
                <ul id="visitaAgroSubmenu" class="sidebar-submenu collapse {{ request()->routeIs('visitas.*') ? 'show' : '' }}">
                    <li class="sidebar-item">
                        <a href="{{ route('visitasHome') }}" class="sidebar-link">
                            <span x-show="sidebarOpen">Home Visitas</span>
                        </a>
                    </li> 
                    @if(Auth::check() && in_array(Auth::user()->rol, [1,2]))

                    <li class="sidebar-item">
                        <a href="{{ route('visitas.import.form') }}" class="sidebar-link">
                            <span x-show="sidebarOpen">Importar <br> Individual</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('visitas.full-import.form') }}" class="sidebar-link">
                            <span x-show="sidebarOpen">Importar <br> Componente</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>

            {{-- Submenú Visita Social --}}
            <li class="sidebar-item has-submenu {{ request()->routeIs('visitas_social.*') ? 'active' : '' }}">
                <a href="#visitaSocialSubmenu" data-bs-toggle="collapse" class="sidebar-link collapsed">
                    <i class="fas fa-users me-2"></i>
                    <span x-show="sidebarOpen">Visita - Social</span>
                </a>
                <ul id="visitaSocialSubmenu" class="sidebar-submenu collapse {{ request()->routeIs('visitas_social.*') ? 'show' : '' }}">
                    <li class="sidebar-item">
                        <a href="{{ route('visitas_social.homeSocial') }}" class="sidebar-link">
                            <span x-show="sidebarOpen">Home Social</span>
                        </a>
                    </li>
                    @if(Auth::check() && in_array(Auth::user()->rol, [1,3]))
                    <li class="sidebar-item">
                        <a href="{{ route('visitas_social.import.form') }}" class="sidebar-link">
                            <span>Importar <br>  individual </span>
                        </a>

                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('visitas_social.full-import.form') }}" class="sidebar-link">
                            <span x-show="sidebarOpen">Importar <br> Componente Social</span>
                        </a>
                    </li>
                    @endif

                </ul>
        </li>
         @endif

    </ul>
</li>
    @if(Auth::check() && in_array(Auth::user()->rol, [1, 2, 3 ]))
        <li class="sidebar-item has-submenu 
            {{ request()->routeIs('planificaciones.*') || request()->routeIs('planificaciones_social.*') ? 'active' : '' }}">
            
            <a href="#planificacionSubmenu" data-bs-toggle="collapse" class="sidebar-link collapsed">
                <i class="fas fa-calendar-alt me-2"></i>
                <span x-show="sidebarOpen">Planificación</span>
            </a>

            <ul id="planificacionSubmenu" class="sidebar-submenu collapse 
                {{ request()->routeIs('planificaciones.*') || request()->routeIs('planificaciones_social.*') ? 'show' : '' }}" 
                data-bs-parent="#sidebarMenu">

                {{-- Planificación Agronómica --}}
                <li class="sidebar-item">
                    <a href="{{ route('planificaciones.calendario') }}" class="sidebar-link">
                        <span x-show="sidebarOpen">Agronómica</span>
                    </a>
                </li>

                {{-- Planificación Social --}}
                <li class="sidebar-item">
                    <a href="{{ route('planificaciones_social.index') }}" class="sidebar-link">
                        <span x-show="sidebarOpen">Social</span>
                    </a>
        </li>
         @endif
    </ul>
</li>

        
    </ul>

    <style>
        /* Estilos responsive para el sidebar */
        @media (max-width: 768px) {
            .sidebar {
                width: 280px !important;
                transform: translateX(-100%);
                left: 0;
            }
            .sidebar:not(.sidebar-collapsed) {
                transform: translateX(0);
                box-shadow: 2px 0 10px rgba(0,0,0,0.2);
            }
            .mobile-menu-button {
                display: block !important;
            }
            .sidebar-overlay {
                display: block !important;
            }
        }
        
        /* Animación suave */
        .sidebar {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Efecto hover para el botón flotante */
        .mobile-menu-button button:hover {
            transform: scale(1.1);
            transition: transform 0.2s;
        }
        /* Estilos responsive para el sidebar */
        @media (max-width: 768px) {
            .sidebar {
                width: 250px !important;
                transform: translateX(-100%);
                left: 0;
            }
            .sidebar:not(.sidebar-collapsed) {
                transform: translateX(0);
            }
            .mobile-menu-button {
                display: block !important;
            }
            .sidebar-overlay {
                display: block !important;
            }
        }
        
        /* Transición suave */
        .sidebar {
            transition: transform 0.3s ease;
        }
        
        /* Estilos base para el menú */
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar-item {
            padding: 0.5rem 1rem;
        }
        .sidebar-link {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 0.5rem 0;
        }
        .sidebar-item.active {
            background-color: rgba(255, 255, 255, 0.1);
        }
    </style>
</div>