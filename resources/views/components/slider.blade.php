<div 
    class="sidebar bg-dark text-white" 
    style="background-color: rgba(29, 89, 7, 0.9) !important; margin-top:47px"
    id="mainSidebar"
    style="width: 12%; min-width: 50px; position: fixed; height: 100vh; z-index: 1000; transition: all 0.3s ease;"
>
    <!-- Overlay para cerrar el menú en móviles (SOLO se cierra al hacer clic en el overlay) -->
    <div class="sidebar-overlay"
         style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 999; display: none;"
         onclick="closeSidebar()">
    </div>

    <!-- Contenido del Sidebar -->
    <div class="sidebar-content" style="height: 100%; overflow-y: auto; position: relative; z-index: 1000;">
        <!-- Header del Sidebar -->
        <div class="sidebar-header p-3 d-flex justify-content-between align-items-center border-bottom border-light">
            <h5 class="mb-0 text-white">
                <i class="fas fa-bars me-2"></i>Menú
            </h5>
            <button onclick="toggleSidebar()" class="btn btn-sm btn-outline-light mobile-close-btn">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Resto de tu código del sidebar (igual que antes) -->
        <!-- Logo/Brand -->
        <div class="sidebar-brand p-3 border-bottom border-light">
            <a class="navbar-brand d-flex align-items-center text-white text-decoration-none" href="{{ route('dashboard') }}">
                <i class="fas fa-leaf me-2 fs-4"></i>
                <span class="fw-bold">Compra Fruto</span>
            </a>
        </div>

        <!-- Menú Navigation -->
        <ul class="sidebar-menu list-unstyled p-3 mb-0">
            <!-- Dashboard -->
            <li class="sidebar-item {{ request()->routeIs('evaluaciones-ibt.create')  }} mb-2">
                <a href="{{ route('evaluaciones-ibt.index') }}" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center">
                    <i class="fas fa-tachometer-alt me-3 fs-5"></i>
                    <span class="fw-medium">Evaluación IBT</span>
                </a>
            </li>
           
            
            <!-- Zona Admon -->
            @if(Auth::check() && in_array(Auth::user()->rol, [1,2,3,4]))
            <li class="sidebar-item has-submenu mb-2 {{ request()->routeIs('proveedores.*') || request()->routeIs('plantaciones.*') || request()->routeIs('usuarios.*') || request()->routeIs('auditorias.*') ? 'active' : '' }}">
                <a href="#zonaAdmonSubmenu" data-bs-toggle="collapse" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-cogs me-3 fs-5"></i>
                        <span class="fw-medium">Zona Admon</span>
                    </div>
                    <i class="fas fa-chevron-down ms-2 fs-6"></i>
                </a>
                <ul id="zonaAdmonSubmenu" class="sidebar-submenu collapse list-unstyled mt-2 ps-4 {{ request()->routeIs('proveedores.*') || request()->routeIs('plantaciones.*') || request()->routeIs('usuarios.*') || request()->routeIs('auditorias.*') ? 'show' : '' }}">
                    <!-- Proveedores -->
                    <li class="sidebar-item has-submenu mb-2 {{ request()->routeIs('proveedores.*') ? 'active' : '' }}">
                        <a href="#proveedoresSubmenu" data-bs-toggle="collapse" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-truck me-3 fs-6"></i>
                                <span class="fw-medium">Proveedores</span>
                            </div>
                            <i class="fas fa-chevron-down ms-2 fs-6"></i>
                        </a>
                        <ul id="proveedoresSubmenu" class="sidebar-submenu collapse list-unstyled mt-2 ps-4 {{ request()->routeIs('proveedores.*') ? 'show' : '' }}">
                            <li class="sidebar-item mb-1">
                                <a href="{{ route('proveedores.index') }}" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center">
                                    <i class="fas fa-list me-3 fs-6"></i>
                                    <span>Listar Proveedores</span>
                                </a>
                            </li>
                            @if(Auth::check() && in_array(Auth::user()->rol, [1,4]))
                            <li class="sidebar-item mb-1">
                                <a href="{{ route('proveedores.import.form') }}" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center">
                                    <i class="fas fa-upload me-3 fs-6"></i>
                                    <span>Importar Proveedores</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>

                    <!-- Plantaciones -->
                    <li class="sidebar-item has-submenu mb-2 {{ request()->routeIs('plantaciones.*') ? 'active' : '' }}">
                        <a href="#plantacionesSubmenu" data-bs-toggle="collapse" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-seedling me-3 fs-6"></i>
                                <span class="fw-medium">Plantaciones</span>
                            </div>
                            <i class="fas fa-chevron-down ms-2 fs-6"></i>
                        </a>
                        <ul id="plantacionesSubmenu" class="sidebar-submenu collapse list-unstyled mt-2 ps-4 {{ request()->routeIs('plantaciones.*') ? 'show' : '' }}">
                            <li class="sidebar-item mb-1">
                                <a href="{{ route('dashboard.plantaciones') }}" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center">
                                    <i class="fas fa-chart-bar me-3 fs-6"></i>
                                    <span>Dashboard Plantaciones</span>
                                </a>
                            </li>
                            @if(Auth::check() && in_array(Auth::user()->rol, [1,4]))
                            <li class="sidebar-item mb-1">
                                <a href="{{ route('plantaciones.create') }}" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center">
                                    <i class="fas fa-upload me-3 fs-6"></i>
                                    <span>Importar Plantaciones</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>

                    <!-- Usuarios (Solo rol 1) -->
                    @if(Auth::check() && in_array(Auth::user()->rol, [1]))
                    <li class="sidebar-item has-submenu mb-2 {{ request()->routeIs('usuarios.*') || request()->routeIs('auditorias.*') ? 'active' : '' }}">
                        <a href="#usuariosSubmenu" data-bs-toggle="collapse" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user me-3 fs-6"></i>
                                <span class="fw-medium">Usuarios</span>
                            </div>
                            <i class="fas fa-chevron-down ms-2 fs-6"></i>
                        </a>
                        <ul id="usuariosSubmenu" class="sidebar-submenu collapse list-unstyled mt-2 ps-4 {{ request()->routeIs('usuarios.*') || request()->routeIs('auditorias.*') ? 'show' : '' }}">
                            <li class="sidebar-item mb-1">
                                <a href="{{ route('register') }}" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center">
                                    <i class="fas fa-user-plus me-3 fs-6"></i>
                                    <span>Agregar Usuario</span>
                                </a>
                            </li>
                            <li class="sidebar-item mb-1">
                                <a href="{{ route('usuarios.index') }}" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center">
                                    <i class="fas fa-list me-3 fs-6"></i>
                                    <span>Listar Usuarios</span>
                                </a>
                            </li>
                            <li class="sidebar-item mb-1">
                                <a href="{{ route('auditorias.index') }}" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center">
                                    <i class="fas fa-shield-alt me-3 fs-6"></i>
                                    <span>Auditoria Usuario</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            <!-- Visitas -->
            @if(Auth::check() && in_array(Auth::user()->rol, [1,2,3,4]))
            <li class="sidebar-item has-submenu mb-2 {{ request()->routeIs('visitas.*') || request()->routeIs('visitas_social.*') || request()->routeIs('visitas_ambiental.*') ? 'active' : '' }}">
                <a href="#visitasSubmenu" data-bs-toggle="collapse" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clipboard-list me-3 fs-5"></i>
                        <span class="fw-medium">Visitas</span>
                    </div>
                    <i class="fas fa-chevron-down ms-2 fs-6"></i>
                </a>
                <ul id="visitasSubmenu" class="sidebar-submenu collapse list-unstyled mt-2 ps-4 {{ request()->routeIs('visitas.*') || request()->routeIs('visitas_social.*') || request()->routeIs('visitas_ambiental.*') ? 'show' : '' }}">
                    <!-- Visita Agronómica -->
                    <li class="sidebar-item has-submenu mb-2 {{ request()->routeIs('visitas.*') ? 'active' : '' }}">
                        <a href="#visitaAgroSubmenu" data-bs-toggle="collapse" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-seedling me-3 fs-6"></i>
                                <span>Agronómica</span>
                            </div>
                            <i class="fas fa-chevron-down ms-2 fs-6"></i>
                        </a>
                        <ul id="visitaAgroSubmenu" class="sidebar-submenu collapse list-unstyled mt-2 ps-4 {{ request()->routeIs('visitas.*') ? 'show' : '' }}">
                            <li class="sidebar-item mb-1">
                                <a href="{{ route('visitasHome') }}" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center">
                                    <i class="fas fa-home me-3 fs-6"></i>
                                    <span>Home <br>Visitas</span>
                                </a>
                            </li>
                            @if(Auth::check() && in_array(Auth::user()->rol, [1,2]))
                            <li class="sidebar-item mb-1">
                                <a href="{{ route('visitas.import.form') }}" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center">
                                    <i class="fas fa-upload me-3 fs-6"></i>
                                    <span>Importar <br> Individual</span>
                                </a>
                            </li>
                            <li class="sidebar-item mb-1">
                                <a href="{{ route('visitas.full-import.form') }}" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center">
                                    <i class="fas fa-file-import me-3 fs-6"></i>
                                    <span>Importar <br> Componente</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>

                    <!-- Visita Social -->
                    <li class="sidebar-item has-submenu mb-2 {{ request()->routeIs('visitas_social.*') ? 'active' : '' }}">
                        <a href="#visitaSocialSubmenu" data-bs-toggle="collapse" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-users me-3 fs-6"></i>
                                <span>Social</span>
                            </div>
                            <i class="fas fa-chevron-down ms-2 fs-6"></i>
                        </a>
                        <ul id="visitaSocialSubmenu" class="sidebar-submenu collapse list-unstyled mt-2 ps-4 {{ request()->routeIs('visitas_social.*') ? 'show' : '' }}">
                            <li class="sidebar-item mb-1">
                                <a href="{{ route('visitas_social.homeSocial') }}" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center">
                                    <i class="fas fa-home me-3 fs-6"></i>
                                    <span>Home <br> Social</span>
                                </a>
                            </li>
                            @if(Auth::check() && in_array(Auth::user()->rol, [1,3]))
                            <li class="sidebar-item mb-1">
                                <a href="{{ route('visitas_social.import.form') }}" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center">
                                    <i class="fas fa-upload me-3 fs-6"></i>
                                    <span>Importar <br> Individual</span>
                                </a>
                            </li>
                            <li class="sidebar-item mb-1">
                                <a href="{{ route('visitas_social.full-import.form') }}" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center">
                                    <i class="fas fa-file-import me-3 fs-6"></i>
                                    <span>Importar <br> Componente <br> Social</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>

                    <!-- Visita Ambiental -->
                    <li class="sidebar-item has-submenu mb-2 {{ request()->routeIs('visitas_ambiental.*') ? 'active' : '' }}">
                        <a href="#visitaAmbientalSubmenu" data-bs-toggle="collapse" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-leaf me-3 fs-6"></i>
                                <span>Ambiental</span>
                            </div>
                            <i class="fas fa-chevron-down ms-2 fs-6"></i>
                        </a>
                        <ul id="visitaAmbientalSubmenu" class="sidebar-submenu collapse list-unstyled mt-2 ps-4 {{ request()->routeIs('visitas_ambiental.*') ? 'show' : '' }}">
                            <li class="sidebar-item mb-1">
                                <a href="{{ route('visitasHomeAmbiental') }}" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center">
                                    <i class="fas fa-home me-3 fs-6"></i>
                                    <span>Home <br> Ambiental</span>
                                </a>
                            </li>
                            @if(Auth::check() && in_array(Auth::user()->rol, [1,3])) <!-- Ajusta los roles según necesites -->
                            <li class="sidebar-item mb-1">
                                <a href="{{ route('visitas_ambiental.import.form') }}" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center">
                                    <i class="fas fa-upload me-3 fs-6"></i>
                                    <span>Importar <br> Individual</span>
                                </a>
                            </li>
                            <li class="sidebar-item mb-1">
                                <a href="{{ route('visitas_ambiental.full-import.form') }}" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center">
                                    <i class="fas fa-file-import me-3 fs-6"></i>
                                    <span>Importar <br> Componente <br> Ambiental</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                </ul>
            </li>
            @endif

            <!-- Envíos -->
            @if(Auth::check() && in_array(Auth::user()->rol, [1,2,3]))
            <li class="sidebar-item has-submenu mb-2 {{ request()->routeIs('envios.*') ? 'active' : '' }}">
                <a href="#enviosSubmenu" data-bs-toggle="collapse" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-truck me-3 fs-5"></i>
                        <span class="fw-medium">Envíos</span>
                    </div>
                    <i class="fas fa-chevron-down ms-2 fs-6"></i>
                </a>
                <ul id="enviosSubmenu" class="sidebar-submenu collapse list-unstyled mt-2 ps-4 {{ request()->routeIs('envios.*') ? 'show' : '' }}">
                    <li class="sidebar-item mb-1">
                        <a href="{{ route('envios.index') }}" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center">
                            <i class="fas fa-list me-3 fs-6"></i>
                            <span>📋 Listado de Envíos</span>
                        </a>
                    </li>
                    <li class="sidebar-item mb-1">
                        <a href="{{ route('envios.create') }}" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center">
                            <i class="fas fa-plus me-3 fs-6"></i>
                            <span>➕ Nuevo Envío</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            <!-- Planificación -->
            @if(Auth::check() && in_array(Auth::user()->rol, [1,2,3]))
            <li class="sidebar-item has-submenu mb-2 {{ request()->routeIs('planificaciones.*') || request()->routeIs('planificaciones_social.*') ? 'active' : '' }}">
                <a href="#planificacionSubmenu" data-bs-toggle="collapse" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-calendar-alt me-3 fs-5"></i>
                        <span class="fw-medium">Planificación</span>
                    </div>
                    <i class="fas fa-chevron-down ms-2 fs-6"></i>
                </a>
                <ul id="planificacionSubmenu" class="sidebar-submenu collapse list-unstyled mt-2 ps-4 {{ request()->routeIs('planificaciones.*') || request()->routeIs('planificaciones_social.*') ? 'show' : '' }}">
                    <li class="sidebar-item mb-1">
                        <a href="{{ route('planificaciones.calendario') }}" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center">
                            <i class="fas fa-seedling me-3 fs-6"></i>
                            <span>Agronómica</span>
                        </a>
                    </li>
                    <li class="sidebar-item mb-1">
                        <a href="{{ route('planificaciones_social.index') }}" class="sidebar-link text-white text-decoration-none py-2 px-3 rounded d-flex align-items-center">
                            <i class="fas fa-users me-3 fs-6"></i>
                            <span>Social</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif

        </ul>

        <!-- Footer del Sidebar -->
        <div class="sidebar-footer p-3 border-top border-light mt-auto">
            <div class="d-flex align-items-center text-white-50">
                <i class="fas fa-user-circle me-2"></i>
                <small>
                    {{ Auth::user()->name ?? 'Usuario' }}
                </small>
            </div>
        </div>
    </div>
</div>

<script>
// Estado del sidebar
let sidebarOpen = window.innerWidth > 868;

// Inicializar el sidebar cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
    checkScreenSize();
    window.addEventListener('resize', checkScreenSize);
});

// Verificar tamaño de pantalla
function checkScreenSize() {
    const isMobile = window.innerWidth <= 868;
    const sidebar = document.getElementById('mainSidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    
    if (isMobile) {
        // En móvil - sidebar oculto por defecto
        sidebar.classList.add('mobile-sidebar');
        if (!sidebarOpen) {
            sidebar.classList.remove('mobile-open');
            overlay.style.display = 'none';
        } else {
            sidebar.classList.add('mobile-open');
            overlay.style.display = 'block';
        }
    } else {
        // En desktop - sidebar siempre visible
        sidebar.classList.remove('mobile-sidebar', 'mobile-open');
        overlay.style.display = 'none';
        sidebarOpen = true;
    }
}

// Alternar sidebar
function toggleSidebar() {
    sidebarOpen = !sidebarOpen;
    checkScreenSize();
}

// Cerrar sidebar (SOLO se llama desde el overlay o botón cerrar)
function closeSidebar() {
    if (window.innerWidth <= 868) {
        sidebarOpen = false;
        checkScreenSize();
    }
}

// Prevenir que los clics dentro del sidebar cierren el overlay
document.addEventListener('DOMContentLoaded', function() {
    const sidebarContent = document.querySelector('.sidebar-content');
    if (sidebarContent) {
        sidebarContent.addEventListener('click', function(event) {
            event.stopPropagation(); // Evita que el clic se propague al overlay
        });
    }
});
</script>

<style>
    /* Estilos para pantallas grandes (más de 868px) */
    @media (min-width: 869px) {
        .sidebar {
            width: 12% !important;
            min-width: 300px;
            transform: translateX(0) !important;
            display: block !important;
        }
        .sidebar-overlay {
            display: none !important;
        }
        .mobile-close-btn {
            display: none !important;
        }
    }

    /* Estilos para pantallas pequeñas (hasta 868px) */
    @media (max-width: 868px) {
        .mobile-sidebar {
            width: 280px !important;
            transform: translateX(-100%);
            left: 0;
            top: 0;
            box-shadow: 2px 0 15px rgba(0,0,0,0.3);
            display: block !important;
            z-index: 1000 !important;
        }
        
        .mobile-sidebar.mobile-open {
            transform: translateX(0) !important;
        }
        
        .sidebar-overlay {
            display: block !important;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5); /* Más transparente */
            z-index: 999; /* Detrás del sidebar */
        }
        
        .mobile-close-btn {
            display: block !important;
        }
        
        /* Asegurar que el contenido del sidebar esté por encima del overlay */
        .sidebar-content {
            position: relative;
            z-index: 1000;
            background-color: rgba(29, 89, 7, 0.9) !important;
            height: 100%;
        }
    }

    /* Estilos generales del sidebar */
    .sidebar {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .sidebar-item.active {
        background-color: rgba(255, 255, 255, 0.15);
        border-radius: 8px;
    }

    .sidebar-link {
        transition: all 0.2s ease;
        border-radius: 8px;
        cursor: pointer;
    }

    .sidebar-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .sidebar-link[aria-expanded="true"] .fa-chevron-down {
        transform: rotate(180deg);
    }

    .sidebar-link .fa-chevron-down {
        transition: transform 0.2s ease;
    }

    .sidebar-submenu {
        border-left: 2px solid rgba(255, 255, 255, 0.2);
    }

    /* Scroll personalizado */
    .sidebar-content::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-content::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
    }

    .sidebar-content::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 3px;
    }

    .sidebar-content::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }
</style>