@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h2><i class="fas fa-chart-line"></i> Módulo de Finanzas</h2>
                <div class="d-flex gap-2">
                    @if($tab === 'dashboard' || $tab === 'purchases')
                        <a href="{{ route('finance.purchases.create', ['company' => $company]) }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nueva Compra
                        </a>
                    @endif
                    @if($tab === 'dashboard' || $tab === 'sales')
                        <a href="{{ route('finance.sales.create', ['company' => $company]) }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Nueva Venta
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Pestañas por Empresa -->
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link {{ $company === 'cofrupa' ? 'active' : '' }}" href="{{ route('finance.index', ['company' => 'cofrupa', 'tab' => $tab]) }}">
                <i class="fas fa-building"></i> Cofrupa Export
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $company === 'luis_gonzalez' ? 'active' : '' }}" href="{{ route('finance.index', ['company' => 'luis_gonzalez', 'tab' => $tab]) }}">
                <i class="fas fa-user-tie"></i> Luis Gonzalez
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $company === 'comercializadora' ? 'active' : '' }}" href="{{ route('finance.index', ['company' => 'comercializadora', 'tab' => $tab]) }}">
                <i class="fas fa-store"></i> Comercializadora
            </a>
        </li>
    </ul>

    <!-- Sub-pestañas: Dashboard / Compras / Ventas / Pagos -->
    <ul class="nav nav-pills mb-4">
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'dashboard' ? 'active' : '' }}" href="{{ route('finance.index', ['company' => $company, 'tab' => 'dashboard']) }}">
                <i class="fas fa-chart-pie"></i> Panel General
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'purchases' ? 'active' : '' }}" href="{{ route('finance.index', ['company' => $company, 'tab' => 'purchases']) }}">
                <i class="fas fa-shopping-cart"></i> Compras
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'sales' ? 'active' : '' }}" href="{{ route('finance.index', ['company' => $company, 'tab' => 'sales']) }}">
                <i class="fas fa-hand-holding-usd"></i> Ventas
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('finance.payments.index') }}">
                <i class="fas fa-money-check-alt"></i> Pagos
            </a>
        </li>
    </ul>

    @if($tab !== 'dashboard')
    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase mb-1">Total Kilos</h6>
                    <h3 class="mb-0">{{ number_format($totalKilos, 0, ',', '.') }} kg</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-{{ $tab === 'sales' ? 'success' : 'primary' }} text-white">
                <div class="card-body">
                    <h6 class="text-uppercase mb-1">Total {{ $tab === 'purchases' ? 'Compras' : 'Ventas' }}</h6>
                    <h3 class="mb-0">${{ number_format($totalAmount, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="text-uppercase mb-1">Registros</h6>
                    <h3 class="mb-0">{{ $records->total() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('finance.index') }}" class="row g-3">
                <input type="hidden" name="company" value="{{ $company }}">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div class="col-md-2">
                    <label class="form-label">Estado</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Pagado</option>
                        <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Parcial</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Desde</label>
                    <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Hasta</label>
                    <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-filter"></i> Filtrar</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Tabla tipo Excel -->
    @if($tab === 'dashboard')
        @include('finance.partials.dashboard', ['records' => $records, 'debts' => $debtsByBank, 'company' => $company])
    @elseif($tab === 'purchases')
        @include('finance.partials.purchases_table', ['purchases' => $records, 'company' => $company])
    @else
        @include('finance.partials.sales_table', ['sales' => $records, 'company' => $company])
    @endif

    @if($records->hasPages())
        <div class="mt-3">
            {{ $records->links() }}
        </div>
    @endif
</div>
@endsection
