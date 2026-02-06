@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-handshake"></i> Ventas y Exportaciones</h2>
            <p class="text-muted mb-0">Monitoreo del proceso, estado de pago y cierre del negocio</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Acceso rápido a Fletes --}}
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('ventas.fletes.index') }}" class="btn btn-outline-success">
                <i class="fas fa-truck"></i> Gestión de Fletes
            </a>
            <span class="text-muted ms-2 small">Costos de transporte en todas las etapas</span>
        </div>
    </div>

    {{-- Monitoreo y punto de cierre --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0"><i class="fas fa-tasks"></i> Monitoreo del proceso y cierre</h5>
            <a href="{{ route('contracts.create') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus"></i> Nuevo contrato
            </a>
        </div>
        <div class="card-body">
            {{-- Filtros --}}
            <form method="GET" action="{{ route('ventas.index') }}" class="row g-2 mb-4">
                <div class="col-auto">
                    <label class="form-label mb-0">Estado negocio</label>
                    <select name="estado_negocio" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="en_proceso" {{ request('estado_negocio') === 'en_proceso' ? 'selected' : '' }}>En proceso</option>
                        <option value="cerrados" {{ request('estado_negocio') === 'cerrados' ? 'selected' : '' }}>Cerrados</option>
                        <option value="cancelados" {{ request('estado_negocio') === 'cancelados' ? 'selected' : '' }}>Cancelados</option>
                    </select>
                </div>
                <div class="col-auto">
                    <label class="form-label mb-0">Estado pago</label>
                    <select name="estado_pago" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="pending" {{ request('estado_pago') === 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="partial" {{ request('estado_pago') === 'partial' ? 'selected' : '' }}>Parcial</option>
                        <option value="paid" {{ request('estado_pago') === 'paid' ? 'selected' : '' }}>Pagado</option>
                    </select>
                </div>
                <div class="col-auto d-flex align-items-end">
                    <button type="submit" class="btn btn-sm btn-outline-primary"><i class="fas fa-filter"></i> Filtrar</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>CONTRACT#</th>
                            <th>PRODUCT</th>
                            <th>BOOKING</th>
                            <th>VESSEL</th>
                            <th>ETD</th>
                            <th>ETA</th>
                            <th>WEEK</th>
                            <th>INV</th>
                            <th>CRIT</th>
                            <th>PAGO</th>
                            <th width="120">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contracts as $c)
                            <tr>
                                <td>
                                    <a href="{{ route('contracts.show', $c) }}" class="fw-bold">{{ $c->contract_number ?: $c->id }}</a>
                                </td>
                                <td>{{ $c->product_type ?? '-' }}</td>
                                <td>{{ $c->booking_number ?? '-' }}</td>
                                <td>{{ $c->vessel_name ?? '-' }}</td>
                                <td>
                                    @if($c->etd_date)
                                        {{ $c->etd_date->format('d/m/Y') }}
                                        @if($c->etd_week) <small class="text-muted">(Sem. {{ $c->etd_week }})</small> @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($c->eta_date)
                                        {{ $c->eta_date->format('d/m/Y') }}
                                        @if($c->eta_week) <small class="text-muted">(Sem. {{ $c->eta_week }})</small> @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $c->transit_weeks ?? '-' }}</td>
                                <td>{{ $c->invoice_number ?? '-' }}</td>
                                <td>{{ $c->customer_reference ?? $c->contract_ref ?? '-' }}</td>
                                <td>
                                    @php
                                        $payStatus = $c->payment_status ?? 'pending';
                                        $payLabels = ['pending' => 'Pendiente', 'partial' => 'Parcial', 'paid' => 'Pagado'];
                                        $payBg = ['pending' => '#ffc107', 'partial' => '#17a2b8', 'paid' => '#28a745'];
                                    @endphp
                                    <div style="background-color: {{ $payBg[$payStatus] ?? '#6c757d' }}; color: white; padding: 2px 8px; border-radius: 4px; text-align: center; font-size: 11px; font-weight: bold;">
                                        {{ $payLabels[$payStatus] ?? $payStatus }}
                                    </div>
                                </td>
                                <td>
                                    {{-- Actualizar pago --}}
                                    <form action="{{ route('ventas.contract.payment-status', $c) }}" method="POST" class="mb-1">
                                        @csrf
                                        <select name="payment_status" class="form-select form-select-sm" onchange="this.form.submit()" style="font-size: 11px;">
                                            <option value="pending" {{ ($c->payment_status ?? 'pending') === 'pending' ? 'selected' : '' }}>⏳ Pendiente</option>
                                            <option value="partial" {{ ($c->payment_status ?? '') === 'partial' ? 'selected' : '' }}>💰 Parcial</option>
                                            <option value="paid" {{ ($c->payment_status ?? '') === 'paid' ? 'selected' : '' }}>✅ Pagado OK</option>
                                        </select>
                                    </form>
                                    {{-- Cerrar negocio --}}
                                    @if(in_array($c->status, ['active', 'draft']))
                                        <form action="{{ route('ventas.contract.close', $c) }}" method="POST" onsubmit="return confirm('¿Cerrar negocio?');">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm w-100" style="font-size: 10px; padding: 2px 4px;">
                                                <i class="fas fa-check"></i> Cerrar
                                            </button>
                                        </form>
                                    @elseif($c->status === 'completed')
                                        <span class="badge bg-success" style="font-size: 10px;">Cerrado</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted py-4">
                                    No hay contratos que coincidan con el filtro.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($contracts->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $contracts->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Accesos rápidos a módulos --}}
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <button class="btn btn-link text-dark text-decoration-none p-0 w-100 text-start" type="button" data-bs-toggle="collapse" data-bs-target="#accesosVentas">
                <i class="fas fa-th-large"></i> Accesos rápidos a documentos y exportaciones
            </button>
        </div>
        <div class="collapse show" id="accesosVentas">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('clients.index') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-users"></i><br>
                            <small>Clientes y Brokers</small>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('contracts.index') }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-file-contract"></i><br>
                            <small>Contratos</small>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('documents.quality-certificate.list') }}" class="btn btn-outline-info w-100">
                            <i class="fas fa-certificate"></i><br>
                            <small>Cert. Calidad China</small>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('documents.quality-certificate-eu.list') }}" class="btn btn-outline-info w-100">
                            <i class="fas fa-certificate"></i><br>
                            <small>Cert. Calidad EU</small>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('documents.shipping-instructions.list') }}" class="btn btn-outline-warning w-100">
                            <i class="fas fa-ship"></i><br>
                            <small>Instruct. Embarque</small>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('documents.transport-instructions.list') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-truck"></i><br>
                            <small>Instruct. Transporte</small>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('documents.dispatch-guides.list') }}" class="btn btn-outline-dark w-100">
                            <i class="fas fa-clipboard-list"></i><br>
                            <small>Guías Despacho</small>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('documents.invoice.list') }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-file-invoice-dollar"></i><br>
                            <small>Factura</small>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('exportations.index') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-folder-open"></i><br>
                            <small>Carpetas Export.</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
