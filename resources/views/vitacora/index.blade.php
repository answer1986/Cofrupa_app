@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-clipboard-list"></i> Vitácora de eventos</h2>
            <small class="text-muted">Eventos de la campana: atendidos y pendientes</small>
        </div>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Pendientes actuales -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bell"></i> Pendientes actuales</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">Eventos que aparecen en la campana. Marca como atendido cuando los revises.</p>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Evento</th>
                                <th class="text-center">Cantidad</th>
                                <th>Estado</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eventTypes as $type => $info)
                            @php
                                $count = $counts[$type] ?? 0;
                                $last = $lastAttended[$type] ?? null;
                                $url = isset($info['param']) ? route($info['route'], $info['param']) : route($info['route']);
                            @endphp
                            <tr>
                                <td>
                                    <a href="{{ $url }}">{{ $info['label'] }}</a>
                                </td>
                                <td class="text-center">
                                    @if($count > 0)
                                        <span class="badge bg-warning text-dark">{{ $count }}</span>
                                    @else
                                        <span class="badge bg-secondary">0</span>
                                    @endif
                                </td>
                                <td>
                                    @if($last)
                                        <span class="text-success">
                                            <i class="fas fa-check-circle"></i> Atendido el {{ $last->attended_at->format('d/m/Y H:i') }}
                                        </span>
                                    @else
                                        <span class="text-muted">Pendiente de revisión</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ $url }}" class="btn btn-sm btn-outline-primary me-1" title="Ir">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    <form action="{{ route('vitacora.store') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="event_type" value="{{ $type }}">
                                        <button type="submit" class="btn btn-sm btn-success" title="Marcar como atendido">
                                            <i class="fas fa-check"></i> Marcar como atendido
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Historial de atenciones -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-history"></i> Historial de atenciones</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="fas fa-clock"></i> Fecha</th>
                                <th><i class="fas fa-tag"></i> Evento</th>
                                <th class="text-center">Cantidad (momento)</th>
                                <th><i class="fas fa-user"></i> Usuario</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($history as $log)
                            <tr>
                                <td>{{ $log->attended_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $log->event_label }}</td>
                                <td class="text-center">
                                    @if($log->count_snapshot !== null)
                                        <span class="badge bg-secondary">{{ $log->count_snapshot }}</span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>{{ $log->user->name ?? '—' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                                    <br>
                                    Aún no hay registros de atenciones.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($history->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $history->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
