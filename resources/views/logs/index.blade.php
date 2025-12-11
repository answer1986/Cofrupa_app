@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-history"></i> Log de Conexiones</h2>
            <small class="text-muted">Historial de accesos al sistema</small>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list"></i> Registro de Conexiones</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="fas fa-user"></i> Usuario</th>
                                <th><i class="fas fa-envelope"></i> Email</th>
                                <th><i class="fas fa-globe"></i> IP</th>
                                <th><i class="fas fa-clock"></i> Fecha de Conexión</th>
                                <th><i class="fas fa-shield-alt"></i> Estado</th>
                                <th><i class="fas fa-desktop"></i> Navegador</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($loginLogs as $log)
                            <tr>
                                <td>
                                    <strong>{{ $log->user->name ?? 'Usuario eliminado' }}</strong>
                                </td>
                                <td>{{ $log->email }}</td>
                                <td>
                                    <code>{{ $log->ip_address }}</code>
                                </td>
                                <td>
                                    {{ $log->login_at->format('d/m/Y H:i:s') }}
                                </td>
                                <td>
                                    @if($log->successful)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i> Exitoso
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times"></i> Fallido
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ Str::limit($log->user_agent, 30) }}
                                    </small>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                                    <br>
                                    No hay registros de conexión aún.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($loginLogs->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $loginLogs->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-primary">{{ $loginLogs->total() }}</h3>
                <p class="text-muted mb-0">Total Conexiones</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-success">{{ $loginLogs->where('successful', true)->count() }}</h3>
                <p class="text-muted mb-0">Conexiones Exitosas</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-danger">{{ $loginLogs->where('successful', false)->count() }}</h3>
                <p class="text-muted mb-0">Conexiones Fallidas</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-info">{{ $loginLogs->unique('user_id')->count() }}</h3>
                <p class="text-muted mb-0">Usuarios Únicos</p>
            </div>
        </div>
    </div>
</div>
@endsection