@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-user-lock"></i> Gestión de Permisos por Rol</h2>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver a Usuarios
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-shield-alt"></i> Configuración de Permisos</h5>
                    <small>Selecciona qué permisos tiene cada rol. Los usuarios heredarán los permisos de su rol.</small>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Roles disponibles:</strong><br>
                        @foreach($roles as $role)
                            <span class="badge bg-secondary me-2">{{ $role->name }}</span>
                        @endforeach
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 30%;">Permiso</th>
                                    <th>Descripción</th>
                                    @foreach($roles as $role)
                                        <th class="text-center" style="width: {{ 70 / $roles->count() }}%;">
                                            <i class="fas fa-user-tag"></i> {{ $role->name }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $permissionDescriptions = [
                                        'manage users' => 'Crear, editar y eliminar usuarios del sistema',
                                        'manage system' => 'Acceso completo a configuraciones del sistema',
                                        'manage processed bins' => 'Gestionar bins procesados (recepción, procesamiento)',
                                        'manage calibration' => 'Gestionar calibración de bins',
                                        'view calibration' => 'Ver información de calibración',
                                        'scan qr codes' => 'Escanear códigos QR de bins',
                                        'view reports' => 'Ver reportes del sistema',
                                    ];
                                @endphp
                                @foreach($permissions as $permission)
                                    <tr>
                                        <td>
                                            <strong>{{ $permission->name }}</strong>
                                            <br>
                                            <code class="text-muted small">{{ $permission->name }}</code>
                                        </td>
                                        <td class="text-muted">
                                            {{ $permissionDescriptions[$permission->name] ?? 'Sin descripción' }}
                                        </td>
                                        @foreach($roles as $role)
                                            <td class="text-center align-middle">
                                                <form action="{{ route('users.update-permissions', $role->id) }}" method="POST" class="permission-form">
                                                    @csrf
                                                    <input type="hidden" name="current_permission" value="{{ $permission->name }}">
                                                    @php
                                                        $hasPermission = $role->permissions->contains('name', $permission->name);
                                                        // Obtener todos los permisos del rol
                                                        $rolePermissions = $role->permissions->pluck('name')->toArray();
                                                    @endphp
                                                    <!-- Incluir todos los permisos actuales del rol -->
                                                    @foreach($rolePermissions as $rp)
                                                        @if($rp !== $permission->name)
                                                            <input type="hidden" name="permissions[]" value="{{ $rp }}">
                                                        @endif
                                                    @endforeach
                                                    
                                                    <div class="form-check form-switch d-flex justify-content-center">
                                                        <input 
                                                            class="form-check-input permission-toggle" 
                                                            type="checkbox" 
                                                            name="permissions[]" 
                                                            value="{{ $permission->name }}"
                                                            {{ $hasPermission ? 'checked' : '' }}
                                                            onchange="this.form.submit()"
                                                            style="cursor: pointer; width: 3rem; height: 1.5rem;"
                                                        >
                                                    </div>
                                                </form>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información adicional -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-users"></i> Usuarios por Rol</h6>
                </div>
                <div class="card-body">
                    @foreach($roles as $role)
                        <div class="mb-3">
                            <h6 class="text-primary">
                                <i class="fas fa-user-tag"></i> {{ $role->name }}
                                <span class="badge bg-secondary">{{ $role->users()->count() }} usuario(s)</span>
                            </h6>
                            @php
                                $roleUsers = $role->users()->limit(5)->get();
                            @endphp
                            @if($roleUsers->count() > 0)
                                <ul class="mb-0">
                                    @foreach($roleUsers as $user)
                                        <li>{{ $user->name }} <small class="text-muted">({{ $user->email }})</small></li>
                                    @endforeach
                                    @if($role->users()->count() > 5)
                                        <li class="text-muted">... y {{ $role->users()->count() - 5 }} más</li>
                                    @endif
                                </ul>
                            @else
                                <p class="text-muted mb-0">No hay usuarios con este rol</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Información Importante</h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li class="mb-2">
                            <strong>Cambios inmediatos:</strong> Los cambios de permisos se aplican inmediatamente a todos los usuarios con ese rol.
                        </li>
                        <li class="mb-2">
                            <strong>Usuarios activos:</strong> Los usuarios que estén conectados verán los cambios en su próxima acción.
                        </li>
                        <li class="mb-2">
                            <strong>Super Admin:</strong> Se recomienda mantener al menos un usuario con rol "Super Admin" con todos los permisos.
                        </li>
                        <li class="mb-2">
                            <strong>Permisos críticos:</strong> 
                            <span class="badge bg-danger">manage system</span> y 
                            <span class="badge bg-danger">manage users</span> 
                            deben asignarse con precaución.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.permission-toggle:checked {
    background-color: #198754;
    border-color: #198754;
}
.permission-form {
    margin: 0;
}
</style>

<script>
// Confirmar cambios críticos
document.addEventListener('DOMContentLoaded', function() {
    const criticalPermissions = ['manage system', 'manage users'];
    const toggles = document.querySelectorAll('.permission-toggle');
    
    toggles.forEach(toggle => {
        const originalChecked = toggle.checked;
        
        toggle.addEventListener('change', function(e) {
            const permissionValue = this.value;
            const isChecked = this.checked;
            
            // Si es un permiso crítico y se está REMOVIENDO
            if (criticalPermissions.includes(permissionValue) && !isChecked) {
                if (!confirm(`¿Estás seguro de QUITAR el permiso "${permissionValue}"? Este es un permiso crítico.`)) {
                    e.preventDefault();
                    this.checked = originalChecked;
                    return false;
                }
            }
        });
    });
});
</script>
@endsection
