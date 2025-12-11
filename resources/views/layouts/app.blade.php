<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sistema Cofrupa</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: linear-gradient(135deg, #722f37 0%, #8b4513 50%, #4a5d23 100%);
            color: white;
            padding-top: 20px;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar-logo {
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        .sidebar-logo img {
            max-width: 120px;
            height: auto;
        }
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar-menu li {
            margin: 5px 0;
        }
        .sidebar-menu a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            transition: all 0.3s;
        }
        .sidebar-menu a:hover {
            background: rgba(255,255,255,0.1);
            border-left: 4px solid white;
        }
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.2);
            border-left: 4px solid white;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .top-navbar {
            background: white;
            border-bottom: 1px solid #ddd;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .user-info {
            display: flex;
            align-items: center;
        }
        .user-info span {
            margin-right: 15px;
            color: #666;
        }
        .content-wrapper {
            background: #f8f9fa;
            min-height: calc(100vh - 80px);
            padding: 30px;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div id="app">
        @auth
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-logo">
                <img src="{{ asset('image/LOGO-sinfonfopng.png') }}" alt="Cofrupa Logo">
            </div>
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('suppliers.index') }}" class="{{ request()->is('suppliers*') ? 'active' : '' }}">
                        <i class="fas fa-truck"></i> Proveedores
                    </a>
                </li>
                <li>
                    <a href="{{ route('bins.index') }}" class="{{ request()->is('bins*') ? 'active' : '' }}">
                        <i class="fas fa-boxes"></i> Bins
                    </a>
                </li>
                <li>
                    <a href="{{ route('purchases.index') }}" class="{{ request()->is('purchases*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-cart"></i> Compras
                    </a>
                </li>
                <li>
                    <a href="{{ route('reports.index') }}" class="{{ request()->is('reports*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i> Reportes
                    </a>
                </li>
                @can('manage processed bins')
                <li>
                    <a href="{{ route('bin_reception.index') }}" class="{{ request()->is('bin_reception*') ? 'active' : '' }}">
                        <i class="fas fa-truck-loading"></i> Recepción de Bins
                    </a>
                </li>
                <li>
                    <a href="{{ route('bin_processing.index') }}" class="{{ request()->is('bin_processing*') ? 'active' : '' }}">
                        <i class="fas fa-balance-scale"></i> Procesamiento de Bins
                    </a>
                </li>
                <li>
                    <a href="{{ route('tarjas.scanner') }}" class="{{ request()->is('tarjas*') ? 'active' : '' }}">
                        <i class="fas fa-qrcode"></i> Lector de Tarjas
                    </a>
                </li>
                @endcan
                @can('manage users')
                <li>
                    <a href="{{ route('users.index') }}" class="{{ request()->is('users*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i> Administrador de Usuarios
                    </a>
                </li>
                <li>
                    <a href="{{ route('logs.index') }}" class="{{ request()->is('logs*') ? 'active' : '' }}">
                        <i class="fas fa-history"></i> Log de Conexiones
                    </a>
                </li>
                @endcan
                <li>
                    <a href="#" class="{{ request()->is('reportes*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i> Reportes del sistema
                    </a>
                </li>
            </ul>
        </div>
        @endauth

        <!-- Main Content -->
        <div class="main-content">
            @auth
            <!-- Top Navbar -->
            <div class="top-navbar">
                <h4 class="mb-0">Sistema Cofrupa</h4>
                <div class="user-info">
                    <span>Bienvenido, {{ Auth::user()->name }}</span>
                    <a class="btn btn-outline-danger btn-sm" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                              document.getElementById('logout-form').submit();">
                         <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                     </a>
                     <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                         @csrf
                     </form>
                </div>
            </div>
            @endauth

            <!-- Page Content -->
            <div class="content-wrapper">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
</body>
</html>
