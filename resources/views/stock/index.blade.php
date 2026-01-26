@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-warehouse"></i> Inventario de Stock (Tarjas en Bodega)</h2>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Tarjas</h5>
                    <h2>{{ $stats['total_tarjas'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Disponibles</h5>
                    <h2>{{ $stats['available'] }}</h2>
                    <small>{{ number_format($stats['total_kg_available'], 0, ',', '.') }} kg</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Asignadas</h5>
                    <h2>{{ $stats['assigned'] }}</h2>
                    <small>{{ number_format($stats['total_kg_assigned'], 0, ',', '.') }} kg</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">En Proceso</h5>
                    <h2>{{ $stats['in_process'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('stock.index') }}" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">Estado</label>
                    <select name="stock_status" class="form-select">
                        <option value="">Todos</option>
                        <option value="available" {{ request('stock_status') == 'available' ? 'selected' : '' }}>Disponible</option>
                        <option value="assigned" {{ request('stock_status') == 'assigned' ? 'selected' : '' }}>Asignado</option>
                        <option value="in_process" {{ request('stock_status') == 'in_process' ? 'selected' : '' }}>En Proceso</option>
                        <option value="completed" {{ request('stock_status') == 'completed' ? 'selected' : '' }}>Completado</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Proveedor</label>
                    <select name="supplier_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Calibre</label>
                    <input type="text" name="caliber" class="form-control" value="{{ request('caliber') }}" placeholder="120-140">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Lote</label>
                    <input type="text" name="lote" class="form-control" value="{{ request('lote') }}" placeholder="COF-81">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Ubicación</label>
                    <input type="text" name="location" class="form-control" value="{{ request('location') }}" placeholder="Bodega A">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-info w-100">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Stock -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>N° Tarja</th>
                            <th>Proveedor</th>
                            <th>Código Interno</th>
                            <th>Lote</th>
                            <th>Calibre</th>
                            <th>Peso Neto</th>
                            <th>Kg Disponibles</th>
                            <th>Kg Asignados</th>
                            <th>Kg Usados</th>
                            <th>Estado</th>
                            <th>Ubicación</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tarjas as $tarja)
                            <tr class="{{ $tarja->stock_status == 'available' ? 'table-success' : ($tarja->stock_status == 'assigned' ? 'table-warning' : '') }}">
                                <td><strong>{{ $tarja->tarja_number }}</strong></td>
                                <td>{{ $tarja->supplier->name ?? 'N/A' }}</td>
                                <td>{{ $tarja->supplier_internal_code ?? 'N/A' }}</td>
                                <td>{{ $tarja->lote ?? 'N/A' }}</td>
                                <td>{{ $tarja->current_calibre ?? 'N/A' }}</td>
                                <td>{{ number_format($tarja->net_fruit_weight, 0, ',', '.') }} kg</td>
                                <td class="text-success fw-bold">
                                    {{ $tarja->available_kg ? number_format($tarja->available_kg, 0, ',', '.') : '0' }} kg
                                </td>
                                <td class="text-warning">
                                    {{ $tarja->assigned_kg ? number_format($tarja->assigned_kg, 0, ',', '.') : '0' }} kg
                                </td>
                                <td class="text-muted">
                                    {{ $tarja->used_kg ? number_format($tarja->used_kg, 0, ',', '.') : '0' }} kg
                                </td>
                                <td>
                                    @switch($tarja->stock_status)
                                        @case('available')
                                            <span class="badge bg-success">✅ Disponible</span>
                                            @break
                                        @case('assigned')
                                            <span class="badge bg-warning text-dark">🟡 Asignado</span>
                                            @break
                                        @case('in_process')
                                            <span class="badge bg-info">🔵 En Proceso</span>
                                            @break
                                        @case('completed')
                                            <span class="badge bg-secondary">✔️ Completado</span>
                                            @break
                                        @default
                                            <span class="badge bg-light text-dark">{{ $tarja->stock_status }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    @if($tarja->location)
                                        <span class="badge bg-primary">{{ $tarja->location }}</span>
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#locationModal{{ $tarja->id }}">
                                            <i class="fas fa-map-marker-alt"></i> Asignar
                                        </button>
                                    @endif
                                </td>
                                <td>{{ $tarja->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('tarjas.show', $tarja->id) }}" class="btn btn-sm btn-outline-info" title="Ver Tarja">
                                            <i class="fas fa-qrcode"></i>
                                        </a>
                                        <a href="{{ route('tarjas.expanded', $tarja->id) }}" class="btn btn-sm btn-outline-primary" title="Ver Detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal para asignar ubicación -->
                            <div class="modal fade" id="locationModal{{ $tarja->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Asignar Ubicación - Tarja {{ $tarja->tarja_number }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('stock.update-location', $tarja->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="location{{ $tarja->id }}" class="form-label">Ubicación en Bodega</label>
                                                    <input type="text" class="form-control" id="location{{ $tarja->id }}" name="location" 
                                                           value="{{ $tarja->location }}" placeholder="Ej: Bodega A - Rack 3" required>
                                                    <small class="text-muted">Ingresa la ubicación física en la bodega</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">Guardar Ubicación</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center">No hay tarjas registradas en el inventario</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $tarjas->links() }}
        </div>
    </div>
</div>
@endsection



