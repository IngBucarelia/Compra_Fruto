<div 
    class="sidebar bg-dark text-white"
    :class="{ 'sidebar-collapsed': !sidebarOpen }"
    x-data="{ sidebarOpen: window.innerWidth > 768 }"
    @resize.window="sidebarOpen = window.innerWidth > 768"
>
    <div class="sidebar-header p-3 d-flex justify-content-between align-items-center">
        <h5 x-show="sidebarOpen" class="mb-0">Menú</h5>
        <button @click="sidebarOpen = !sidebarOpen" class="btn btn-sm btn-outline-light">
            <i class="fas" :class="{ 'fa-chevron-left': sidebarOpen, 'fa-chevron-right': !sidebarOpen }"></i>
        </button>
    </div>
    
    <ul class="sidebar-menu">
        <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="sidebar-link">
                <i class="fas fa-tachometer-alt me-2"></i>
                <span x-show="sidebarOpen">Dashboard</span>
            </a>
        </li>
        
        <li class="sidebar-item {{ request()->routeIs('proveedores.*') ? 'active' : '' }}">
            <a href="{{ route('proveedores.index') }}" class="sidebar-link">
                <i class="fas fa-truck me-2"></i>
                <span x-show="sidebarOpen">Proveedores</span>
            </a>
        </li>
        <li class="sidebar-item {{ request()->routeIs('plantaciones.*') ? 'active' : '' }}">
            <a href="{{ route('plantaciones.index') }}" class="sidebar-link">
                <i class="fas fa-tractor me-2"></i>
                <span x-show="sidebarOpen">Plantaciones</span>
            </a>
        </li>
        
        <li class="sidebar-item">
            <a href="{{ route('visitas.index') }}" class="sidebar-link">
                <i class="fas fa-clipboard-list me-2"></i>
                <span x-show="sidebarOpen">Visitas</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{ route('planificaciones.calendario') }}" class="sidebar-link">
                <i class="fas fa-calendar-alt me-2"></i>
                <span x-show="sidebarOpen">Calendario <br>Visitas</span>
            </a>
        </li>
    </ul>
</div>