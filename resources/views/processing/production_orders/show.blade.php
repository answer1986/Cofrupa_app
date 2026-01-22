@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-eye"></i> Orden de Producción: {{ $productionOrder->order_number }}</h2>
                <div>
                    <a href="{{ route('processing.production-orders.edit', $productionOrder->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="{{ route('processing.production-orders.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Información General</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Contrato:</th>
                            <td>{{ $productionOrder->contract->contract_number ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Planta de Proceso:</th>
                            <td><strong>{{ $productionOrder->plant->name }}</strong></td>
                        </tr>
                        <tr>
                            <th>N° Orden:</th>
                            <td><strong>{{ $productionOrder->order_number }}</strong></td>
                        </tr>
                        <tr>
                            <th>Producto:</th>
                            <td>{{ $productionOrder->product ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Calibre Salida:</th>
                            <td>{{ $productionOrder->output_caliber ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Cantidad (kilos):</th>
                            <td>{{ number_format($productionOrder->order_quantity_kg, 0, ',', '.') }} kg</td>
                        </tr>
                        <tr>
                            <th>Número de Reserva:</th>
                            <td>{{ $productionOrder->booking_number ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Motonave:</th>
                            <td>{{ $productionOrder->vessel ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Estado:</th>
                            <td>
                                <span class="badge bg-{{ $productionOrder->status === 'completed' ? 'success' : ($productionOrder->status === 'delayed' ? 'danger' : ($productionOrder->status === 'in_progress' ? 'info' : 'secondary')) }}">
                                    {{ $productionOrder->status_display }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Control de Tiempos</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Fecha Ingreso:</th>
                            <td>{{ $productionOrder->entry_date ? $productionOrder->entry_date->format('d/m/Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Hora Ingreso:</th>
                            <td>{{ $productionOrder->entry_time ? \Carbon\Carbon::parse($productionOrder->entry_time)->format('H:i:s') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Fecha Término:</th>
                            <td>{{ $productionOrder->completion_date ? $productionOrder->completion_date->format('d/m/Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Hora Término:</th>
                            <td>{{ $productionOrder->completion_time ? \Carbon\Carbon::parse($productionOrder->completion_time)->format('H:i:s') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Día:</th>
                            <td>{{ $productionOrder->day_of_week ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>KG/Hora Nominal:</th>
                            <td>{{ $productionOrder->nominal_kg_per_hour ? number_format($productionOrder->nominal_kg_per_hour, 0, ',', '.') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Horas Estimadas:</th>
                            <td>{{ $productionOrder->estimated_hours ? number_format($productionOrder->estimated_hours, 2, ',', '.') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Horas Reales:</th>
                            <td>{{ $productionOrder->actual_hours ? number_format($productionOrder->actual_hours, 2, ',', '.') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Atraso:</th>
                            <td>
                                <span class="{{ $productionOrder->delay_hours > 0 ? 'text-danger fw-bold' : 'text-success' }}">
                                    {{ $productionOrder->delay_hours ? number_format($productionOrder->delay_hours, 2, ',', '.') . ' horas' : '0 horas' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Kilos Producidos:</th>
                            <td>{{ $productionOrder->produced_kilos ? number_format($productionOrder->produced_kilos, 0, ',', '.') : 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if($productionOrder->has_delay && $productionOrder->delay_reason)
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-warning">
                    <h5><i class="fas fa-exclamation-triangle"></i> Retraso Detectado</h5>
                    <p><strong>Razón del Atraso:</strong> {{ $productionOrder->delay_reason }}</p>
                    <p><strong>Atraso:</strong> {{ number_format($productionOrder->delay_hours, 2, ',', '.') }} horas</p>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Información Adicional</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="20%">Programa de Producción:</th>
                            <td>{{ $productionOrder->production_program ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Solución Sorbato:</th>
                            <td>{{ $productionOrder->sorbate_solution ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Notas:</th>
                            <td>{{ $productionOrder->notes ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



