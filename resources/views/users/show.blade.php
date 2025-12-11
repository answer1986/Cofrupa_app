@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-user"></i> Detalles del Usuario</h2>
            <div>
                <a href="{{ route('users.edit', $user) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-id-card"></i> Información Personal</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Nombre Completo</label>
                            <p class="h5">{{ $user->name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Correo Electrónico</label>
                            <p class="h5">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Rol Asignado</label>
                            <p>
                                @if($user->roles->count() > 0)
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-primary fs-6">{{ $role->name }}</span>
                                    @endforeach
                                @else
                                    <span class="badge bg-secondary fs-6">Sin rol asignado</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Autenticación 2FA</label>
                            <p>
                                @if($user->google2fa_enable)
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-shield-alt"></i> Habilitada
                                    </span>
                                @else
                                    <span class="badge bg-warning fs-6">
                                        <i class="fas fa-exclamation-triangle"></i> Deshabilitada
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-calendar"></i> Fechas Importantes</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-muted">Fecha de Creación</label>
                    <p class="mb-1">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                    <small class="text-muted">
                        Hace {{ $user->created_at->diffForHumans() }}
                    </small>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted">Última Actualización</label>
                    <p class="mb-1">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                    <small class="text-muted">
                        Hace {{ $user->updated_at->diffForHumans() }}
                    </small>
                </div>
            </div>
        </div>

        @if($user->id !== auth()->id())
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0 text-danger"><i class="fas fa-exclamation-triangle"></i> Zona de Peligro</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">
                    Esta acción no se puede deshacer. El usuario perderá acceso al sistema.
                </p>
                <form action="{{ route('users.destroy', $user) }}" method="POST"
                      onsubmit="return confirm('¿Estás completamente seguro de que quieres eliminar este usuario? Esta acción no se puede deshacer.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-trash"></i> Eliminar Usuario
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection