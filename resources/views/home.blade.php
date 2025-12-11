@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-tachometer-alt"></i> Dashboard</h2>
            <small class="text-muted">Bienvenido al Sistema Cofrupa</small>
        </div>
    </div>
</div>

@if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <!-- Calibración Module -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-balance-scale fa-3x text-primary"></i>
                </div>
                <h5 class="card-title">Proceso de Calibración</h5>
                <p class="card-text text-muted">Gestión completa de compras, pesaje y calibración de productos</p>
                <a href="#" class="btn btn-primary">
                    <i class="fas fa-arrow-right"></i> Acceder
                </a>
            </div>
        </div>
    </div>

    <!-- User Management Module -->
    @can('manage users')
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-users fa-3x text-success"></i>
                </div>
                <h5 class="card-title">Administrador de Usuarios</h5>
                <p class="card-text text-muted">Gestión de usuarios, roles y permisos del sistema</p>
                <a href="{{ route('users.index') }}" class="btn btn-success">
                    <i class="fas fa-user-cog"></i> Administrar
                </a>
            </div>
        </div>
    </div>
    @endcan

    <!-- Reports Module -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-chart-bar fa-3x text-info"></i>
                </div>
                <h5 class="card-title">Reportes y Estadísticas</h5>
                <p class="card-text text-muted">Visualización de datos y reportes del sistema</p>
                <a href="#" class="btn btn-info">
                    <i class="fas fa-chart-line"></i> Ver Reportes
                </a>
            </div>
        </div>
    </div>
</div>

<!-- User Info Card -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user"></i> Información del Usuario</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong>Nombre:</strong><br>
                        {{ Auth::user()->name }}
                    </div>
                    <div class="col-md-3">
                        <strong>Email:</strong><br>
                        {{ Auth::user()->email }}
                    </div>
                    <div class="col-md-3">
                        <strong>Rol:</strong><br>
                        <span class="badge bg-primary">{{ Auth::user()->roles->first()->name ?? 'Sin rol asignado' }}</span>
                    </div>
                    <div class="col-md-3">
                        <strong>Autenticación 2FA:</strong><br>
                        @if(Auth::user()->google2fa_enable)
                            <span class="badge bg-success"><i class="fas fa-shield-alt"></i> Habilitado</span>
                        @else
                            <span class="badge bg-warning"><i class="fas fa-exclamation-triangle"></i> Deshabilitado</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-primary">0</h3>
                <p class="text-muted mb-0">Compras Hoy</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-success">0</h3>
                <p class="text-muted mb-0">Productos Calibrados</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-info">0</h3>
                <p class="text-muted mb-0">Bins Activos</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-warning">0</h3>
                <p class="text-muted mb-0">Reportes Generados</p>
            </div>
        </div>
    </div>
</div>
@endsection
