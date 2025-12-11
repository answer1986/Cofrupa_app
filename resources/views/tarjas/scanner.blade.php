@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-qrcode"></i> Lector de Código QR - Tarja</h2>
                <a href="{{ route('bin_reception.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-camera"></i> Escanear Código QR</h5>
                </div>
                <div class="card-body">
                    <form id="qrForm" action="{{ route('tarjas.readQr') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="qr_data" class="form-label">
                                <i class="fas fa-qrcode"></i> Ingrese o pegue el código QR encriptado
                            </label>
                            <textarea 
                                class="form-control @error('qr_data') is-invalid @enderror" 
                                id="qr_data" 
                                name="qr_data" 
                                rows="4" 
                                placeholder="Pegue aquí el código QR escaneado..."
                                required
                                autofocus></textarea>
                            <small class="form-text text-muted">
                                Use un lector de QR para escanear el código de la tarja y pegue el resultado aquí.
                            </small>
                            @error('qr_data')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-search"></i> Leer Código QR
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Instrucciones:</h6>
                        <ol class="mb-0">
                            <li>Use un lector de QR (app del teléfono o escáner)</li>
                            <li>Escanee el código QR de la tarja</li>
                            <li>Copie el texto completo que aparece</li>
                            <li>Péguelo en el campo de arriba</li>
                            <li>Haga clic en "Leer Código QR"</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

