<!DOCTYPE html>
<html lang="es" class="h-100">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Compra Fruto')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Estilos personalizados -->
    <style>
        :root {
            --sidebar-width: 250px;
            --collapsed-sidebar-width: 70px;
        }
        
        body {
            padding-top: 56px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-image: url('{{ asset('images/fondo.jpg') }}'); 
            background-size: contain;
            background-position: center;
            height: 100vh;
        }
        
        .sidebar {
            width: var(--sidebar-width);
            height: calc(100vh - 56px);
            position: fixed;
            top: 56px;
            left: 0;
            transition: all 0.3s;
            z-index: 1000;
            overflow-y: auto;
        }
        
        .sidebar-collapsed {
            width: var(--collapsed-sidebar-width) !important;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
        }
        
        .sidebar-item {
            padding: 0.5rem 1rem;
            border-left: 3px solid transparent;
        }
        
        .sidebar-item.active {
            border-left: 3px solid #0d6efd;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-link {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            white-space: nowrap;
        }
        
        .sidebar-link:hover {
            color: rgba(255, 255, 255, 0.75);
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            transition: all 0.3s;
        }
        
        .collapsed-main {
            margin-left: var(--collapsed-sidebar-width) !important;
        }
        
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#28a745">
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body x-data="{ sidebarOpen: window.innerWidth > 768 }">
    <!-- AppBar -->
    @auth
        @include('components.app-bar')
        <!-- Slider -->
        @include('components.slider')
    @endauth
    
    <!-- Contenido Principal -->
    <main 
        class="main-content py-4"
        :class="{ 'collapsed-main': !sidebarOpen }"
    >
        <div style="margin-top: 80px !important;" class="container-fluid">
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script para manejar el resize -->
    <script>
        window.addEventListener('resize', () => {
            window.dispatchEvent(new CustomEvent('resize'));
        });
    </script>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('/service-worker.js')
                .then(function (reg) {
                    console.log('SW registrado con éxito:', reg);
                })
                .catch(function (err) {
                    console.log('SW error:', err);
                });
            });
            }

    </script>

</body>
</html>