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
        .accordion-item {
            border: none;
        }
        .accordion-header {
            margin: 0;
        }
        .accordion-button {
            background: transparent;
            color: white;
            padding: 12px 25px;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .accordion-button:hover {
            background: rgba(255,255,255,0.1);
        }
        .accordion-button:not(.collapsed) {
            background: rgba(255,255,255,0.2);
            border-left: 4px solid white;
        }
        .accordion-button::after {
            content: '\f078';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            transition: transform 0.3s;
            margin-left: auto;
        }
        .accordion-button:not(.collapsed)::after {
            transform: rotate(180deg);
        }
        .accordion-collapse {
            display: none;
        }
        .accordion-collapse.show {
            display: block;
        }
        .accordion-body {
            padding: 0;
        }
        .accordion-body .sidebar-menu a {
            padding-left: 45px;
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

                <li class="accordion-item">
                    <div class="accordion-header">
                        <button class="accordion-button {{ request()->is('suppliers*') || request()->is('bins*') || request()->is('purchases*') || request()->is('reports*') || request()->is('bin_reception*') || request()->is('bin_processing*') || request()->is('tarjas*') ? '' : 'collapsed' }}" type="button" data-target="#adquisicionesAccordion" aria-expanded="{{ request()->is('suppliers*') || request()->is('bins*') || request()->is('purchases*') || request()->is('reports*') || request()->is('bin_reception*') || request()->is('bin_processing*') || request()->is('tarjas*') ? 'true' : 'false' }}">
                            <i class="fas fa-hand-holding-usd"></i> Adquisiciones
                        </button>
                    </div>
                    <div id="adquisicionesAccordion" class="accordion-collapse {{ request()->is('suppliers*') || request()->is('bins*') || request()->is('purchases*') || request()->is('reports*') || request()->is('bin_reception*') || request()->is('bin_processing*') || request()->is('tarjas*') ? 'show' : '' }}">
                        <div class="accordion-body">
                            <ul class="sidebar-menu">
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
                                    <a href="{{ route('stock.index') }}" class="{{ request()->is('stock*') ? 'active' : '' }}">
                                        <i class="fas fa-warehouse"></i> Inventario de Stock
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('tarjas.scanner') }}" class="{{ request()->is('tarjas*') ? 'active' : '' }}">
                                        <i class="fas fa-qrcode"></i> Lector de Tarjas
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </div>
                    </div>
                </li>

                <!-- Acordeón de Procesamiento -->
                <li class="accordion-item">
                    <div class="accordion-header">
                        <button class="accordion-button {{ request()->is('processing*') ? '' : 'collapsed' }}" type="button" data-target="#procesamientoAccordion" aria-expanded="{{ request()->is('processing*') ? 'true' : 'false' }}">
                            <i class="fas fa-industry"></i> Procesamiento
                        </button>
                    </div>
                    <div id="procesamientoAccordion" class="accordion-collapse {{ request()->is('processing*') ? 'show' : '' }}">
                        <div class="accordion-body">
                            <ul class="sidebar-menu">
                                <li>
                                    <a href="{{ route('processing.plants.index') }}" class="{{ request()->is('processing/plants*') ? 'active' : '' }}">
                                        <i class="fas fa-building"></i> Mantenedor de Plantas
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('processing.production-orders.index') }}" class="{{ request()->is('processing/production-orders*') ? 'active' : '' }}">
                                        <i class="fas fa-industry"></i> Programa de Producción
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('processing.orders.index') }}" class="{{ request()->is('processing/orders*') ? 'active' : '' }}">
                                        <i class="fas fa-clipboard-list"></i> Envío de Órdenes
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('processing.accounting.index') }}" class="{{ request()->is('processing/accounting*') ? 'active' : '' }}">
                                        <i class="fas fa-calculator"></i> Módulo de Contabilidad
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>

                <li class="accordion-item">
                    <div class="accordion-header">
                        <button class="accordion-button {{ request()->is('clients*') || request()->is('brokers*') || request()->is('contracts*') || request()->is('documents*') || request()->is('shipments*') || request()->is('exportations*') ? '' : 'collapsed' }}" type="button" data-target="#ventasAccordion" aria-expanded="{{ request()->is('clients*') || request()->is('brokers*') || request()->is('contracts*') || request()->is('documents*') || request()->is('shipments*') || request()->is('exportations*') ? 'true' : 'false' }}">
                            <i class="fas fa-handshake"></i> Ventas y Comercialización
                        </button>
                    </div>
                    <div id="ventasAccordion" class="accordion-collapse {{ request()->is('clients*') || request()->is('brokers*') || request()->is('contracts*') || request()->is('documents*') || request()->is('shipments*') || request()->is('exportations*') ? 'show' : '' }}">
                        <div class="accordion-body">
                            <ul class="sidebar-menu">
                                <li>
                                    <a href="{{ route('clients.index') }}" class="{{ request()->is('clients*') ? 'active' : '' }}">
                                        <i class="fas fa-users"></i> Gestión de Clientes y Brokers
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('contracts.index') }}" class="{{ request()->is('contracts*') && !request()->is('contracts/*/documents*') ? 'active' : '' }}">
                                        <i class="fas fa-file-contract"></i> Gestión de Contratos
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('documents.quality-certificate.list') }}" class="{{ request()->routeIs('documents.quality-certificate.*') ? 'active' : '' }}">
                                        <i class="fas fa-certificate"></i> Certificado de Calidad (China)
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('documents.quality-certificate-eu.list') }}" class="{{ request()->routeIs('documents.quality-certificate-eu.*') ? 'active' : '' }}">
                                        <i class="fas fa-certificate"></i> Certificado de Calidad (EU)
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('documents.shipping-instructions.list') }}" class="{{ request()->is('*/shipping-instructions*') ? 'active' : '' }}">
                                        <i class="fas fa-ship"></i> Instructivo de Embarque
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('documents.transport-instructions.list') }}" class="{{ request()->is('*/transport-instructions*') ? 'active' : '' }}">
                                        <i class="fas fa-truck"></i> Instructivo de Transporte
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('documents.dispatch-guides.list') }}" class="{{ request()->is('*/dispatch-guides*') ? 'active' : '' }}">
                                        <i class="fas fa-clipboard-list"></i> Guías de Despacho
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('documents.invoice.list') }}" class="{{ request()->is('*/invoice*') && !request()->is('contracts*') ? 'active' : '' }}">
                                        <i class="fas fa-file-invoice-dollar"></i> Factura
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('exportations.index') }}" class="{{ request()->is('exportations*') ? 'active' : '' }}">
                                        <i class="fas fa-folder-open"></i> Carpetas de Exportación
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>

                <!-- Acordeón de Administración - AL FINAL -->
                <li class="accordion-item">
                    <div class="accordion-header">
                        <button class="accordion-button {{ request()->is('users*') || request()->is('logs*') || request()->is('reports*') ? '' : 'collapsed' }}" type="button" data-target="#administracionAccordion" aria-expanded="{{ request()->is('users*') || request()->is('logs*') || request()->is('reports*') ? 'true' : 'false' }}">
                            <i class="fas fa-cog"></i> Administración
                        </button>
                    </div>
                    <div id="administracionAccordion" class="accordion-collapse {{ request()->is('users*') || request()->is('logs*') || request()->is('reports*') ? 'show' : '' }}">
                        <div class="accordion-body">
                            <ul class="sidebar-menu">
                                @can('manage users')
                                <li>
                                    <a href="{{ route('users.index') }}" class="{{ request()->is('users*') ? 'active' : '' }}">
                                        <i class="fas fa-users-cog"></i> Administrador de Usuarios
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('logs.index') }}" class="{{ request()->is('logs*') ? 'active' : '' }}">
                                        <i class="fas fa-history"></i> Log de Conexiones
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('reports.index') }}" class="{{ request()->is('reports*') && !request()->is('reports/payments*') && !request()->is('reports/supplier-debts*') ? 'active' : '' }}">
                                        <i class="fas fa-chart-line"></i> Reportes del Sistema
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
                    </div>
                </li>
                @can('manage users')
                <li class="accordion-item">
                    <div class="accordion-header">
                        <button class="accordion-button {{ request()->is('users*') || request()->is('logs*') || request()->is('reportes*') ? '' : 'collapsed' }}" type="button" data-target="#adminAccordion" aria-expanded="{{ request()->is('users*') || request()->is('logs*') || request()->is('reportes*') ? 'true' : 'false' }}">
                            <i class="fas fa-cog"></i> Administración
                        </button>
                    </div>
                    <div id="adminAccordion" class="accordion-collapse {{ request()->is('users*') || request()->is('logs*') || request()->is('reportes*') ? 'show' : '' }}">
                        <div class="accordion-body">
                            <ul class="sidebar-menu">
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
                                <li>
                                    <a href="#" class="{{ request()->is('reportes*') ? 'active' : '' }}">
                                        <i class="fas fa-chart-bar"></i> Reportes del sistema
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                @endcan
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Bootstrap JS for Accordion -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Accordion Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const accordionButtons = document.querySelectorAll('.accordion-button');
            accordionButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const targetId = this.getAttribute('data-bs-target') || this.getAttribute('data-target');
                    const target = document.querySelector(targetId);
                    
                    if (!target) return;
                    
                    // Toggle the show class
                    const isExpanded = target.classList.contains('show');
                    
                    if (isExpanded) {
                        // Collapse
                        target.classList.remove('show');
                        this.classList.add('collapsed');
                        this.setAttribute('aria-expanded', 'false');
                    } else {
                        // Expand
                        target.classList.add('show');
                        this.classList.remove('collapsed');
                        this.setAttribute('aria-expanded', 'true');
                    }
                });
            });
        });
    </script>
</body>
</html>
