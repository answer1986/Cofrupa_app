<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return auth()->check() ? redirect('/home') : redirect('/login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/2fa', [App\Http\Controllers\Google2FAController::class, 'index'])->name('2fa.index');
    Route::post('/2fa', [App\Http\Controllers\Google2FAController::class, 'verify'])->name('2fa.verify');
    Route::get('/2fa/enable', [App\Http\Controllers\Google2FAController::class, 'enable'])->name('2fa.enable');
    Route::post('/2fa/enable', [App\Http\Controllers\Google2FAController::class, 'confirmEnable'])->name('2fa.confirm');
    Route::post('/2fa/disable', [App\Http\Controllers\Google2FAController::class, 'disable'])->name('2fa.disable');
});

Route::middleware(['auth', '2fa'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('users', App\Http\Controllers\UserController::class)->middleware('can:manage users');
    Route::resource('suppliers', App\Http\Controllers\SupplierController::class);
    Route::resource('bins', App\Http\Controllers\BinController::class);
    Route::resource('purchases', App\Http\Controllers\PurchaseController::class);

    // Bin Reception (initial QR generation for received bins)
    Route::resource('bin_reception', App\Http\Controllers\BinReceptionController::class)->middleware('can:manage processed bins');

    // Bin Processing (mixing and calibration)
    Route::resource('bin_processing', App\Http\Controllers\BinProcessingController::class)->middleware('can:manage processed bins');
    
    // Stock/Inventory Management
    Route::get('/stock', [App\Http\Controllers\StockController::class, 'index'])->name('stock.index');
    Route::get('/stock/{id}', [App\Http\Controllers\StockController::class, 'show'])->name('stock.show');
    Route::post('/stock/{id}/update-location', [App\Http\Controllers\StockController::class, 'updateLocation'])->name('stock.update-location');
    
    // Tarjas (labels) - IMPORTANT: Routes with specific paths must come before routes with parameters
    Route::get('/tarjas/scanner', [App\Http\Controllers\TarjaController::class, 'scanner'])->name('tarjas.scanner');
    Route::post('/tarjas/read-qr', [App\Http\Controllers\TarjaController::class, 'readQr'])->name('tarjas.readQr');
    Route::get('/tarjas/{id}/expanded', [App\Http\Controllers\TarjaController::class, 'expanded'])->name('tarjas.expanded');
    Route::get('/tarjas/{id}/print', [App\Http\Controllers\TarjaController::class, 'print'])->name('tarjas.print');
    Route::get('/tarjas/{id}', [App\Http\Controllers\TarjaController::class, 'show'])->name('tarjas.show');
    Route::post('/bins/{id}/return', [App\Http\Controllers\BinController::class, 'returnBin'])->name('bins.return');
    Route::post('/bins/{id}/assign', [App\Http\Controllers\BinController::class, 'assignToSupplier'])->name('bins.assign');
    Route::get('/logs', [App\Http\Controllers\LoginLogController::class, 'index'])->name('logs.index')->middleware('can:manage users');

    // Reports routes
    Route::get('/reports', [App\Http\Controllers\ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/payments', [App\Http\Controllers\ReportsController::class, 'payments'])->name('reports.payments');
    Route::get('/reports/supplier-debts', [App\Http\Controllers\ReportsController::class, 'supplierDebts'])->name('reports.supplier-debts');

    // Clients and Brokers routes
    Route::resource('clients', App\Http\Controllers\ClientController::class);
    Route::post('/clients/conversations/store', [App\Http\Controllers\ClientController::class, 'storeConversation'])->name('clients.conversations.store');
    Route::resource('brokers', App\Http\Controllers\BrokerController::class);
    
    // Contracts routes
    Route::resource('contracts', App\Http\Controllers\ContractController::class);
    
    // Shipments and Logistics routes
    Route::resource('shipments', App\Http\Controllers\ShipmentController::class);
    Route::post('/shipments/{id}/send-email', [App\Http\Controllers\ShipmentController::class, 'sendTransportEmail'])->name('shipments.send-email');
    Route::resource('shipping-lines', App\Http\Controllers\ShippingLineController::class);
    Route::resource('logistics-companies', App\Http\Controllers\LogisticsCompanyController::class);
    
    // Generación de Documentos - Módulos Independientes
    // IMPORTANTE: Estas rutas DEBEN ir ANTES del Route::resource('documents') 
    // porque sino Laravel interpreta /documents/quality-certificate como /documents/{id}
    // Certificado de Calidad - China
    Route::get('/documents/quality-certificate', [App\Http\Controllers\DocumentGeneratorController::class, 'listQualityCertificates'])->name('documents.quality-certificate.list');
    Route::get('/documents/quality-certificate/{contract}/edit', [App\Http\Controllers\DocumentGeneratorController::class, 'editQualityCertificate'])->name('documents.quality-certificate.edit');
    Route::post('/documents/quality-certificate/{contract}/preview', [App\Http\Controllers\DocumentGeneratorController::class, 'previewQualityCertificate'])->name('documents.quality-certificate.preview');
    Route::post('/documents/quality-certificate/{contract}/send', [App\Http\Controllers\DocumentGeneratorController::class, 'sendQualityCertificate'])->name('documents.quality-certificate.send');
    Route::post('/documents/quality-certificate/{contract}', [App\Http\Controllers\DocumentGeneratorController::class, 'storeQualityCertificate'])->name('documents.quality-certificate.store');
    
    // Certificado de Calidad - Unión Europea
    Route::get('/documents/quality-certificate-eu', [App\Http\Controllers\DocumentGeneratorController::class, 'listQualityCertificatesEU'])->name('documents.quality-certificate-eu.list');
    Route::get('/documents/quality-certificate-eu/{contract}/edit', [App\Http\Controllers\DocumentGeneratorController::class, 'editQualityCertificateEU'])->name('documents.quality-certificate-eu.edit');
    Route::post('/documents/quality-certificate-eu/{contract}/preview', [App\Http\Controllers\DocumentGeneratorController::class, 'previewQualityCertificateEU'])->name('documents.quality-certificate-eu.preview');
    Route::post('/documents/quality-certificate-eu/{contract}/send', [App\Http\Controllers\DocumentGeneratorController::class, 'sendQualityCertificateEU'])->name('documents.quality-certificate-eu.send');
    Route::post('/documents/quality-certificate-eu/{contract}', [App\Http\Controllers\DocumentGeneratorController::class, 'storeQualityCertificateEU'])->name('documents.quality-certificate-eu.store');
    
    Route::get('/documents/shipping-instructions', [App\Http\Controllers\DocumentGeneratorController::class, 'listShippingInstructions'])->name('documents.shipping-instructions.list');
    Route::get('/documents/shipping-instructions/{contract}/edit', [App\Http\Controllers\DocumentGeneratorController::class, 'editShippingInstructions'])->name('documents.shipping-instructions.edit');
    Route::post('/documents/shipping-instructions/{contract}/preview', [App\Http\Controllers\DocumentGeneratorController::class, 'previewShippingInstructions'])->name('documents.shipping-instructions.preview');
    Route::post('/documents/shipping-instructions/{contract}/send', [App\Http\Controllers\DocumentGeneratorController::class, 'sendShippingInstructions'])->name('documents.shipping-instructions.send');
    Route::post('/documents/shipping-instructions/{contract}', [App\Http\Controllers\DocumentGeneratorController::class, 'storeShippingInstructions'])->name('documents.shipping-instructions.store');
    
    Route::get('/documents/transport-instructions', [App\Http\Controllers\DocumentGeneratorController::class, 'listTransportInstructions'])->name('documents.transport-instructions.list');
    Route::get('/documents/transport-instructions/{contract}/edit', [App\Http\Controllers\DocumentGeneratorController::class, 'editTransportInstructions'])->name('documents.transport-instructions.edit');
    Route::post('/documents/transport-instructions/{contract}/preview', [App\Http\Controllers\DocumentGeneratorController::class, 'previewTransportInstructions'])->name('documents.transport-instructions.preview');
    Route::post('/documents/transport-instructions/{contract}/send', [App\Http\Controllers\DocumentGeneratorController::class, 'sendTransportInstructions'])->name('documents.transport-instructions.send');
    Route::post('/documents/transport-instructions/{contract}', [App\Http\Controllers\DocumentGeneratorController::class, 'storeTransportInstructions'])->name('documents.transport-instructions.store');
    
    Route::get('/documents/dispatch-guides', [App\Http\Controllers\DocumentGeneratorController::class, 'listDispatchGuides'])->name('documents.dispatch-guides.list');
    Route::get('/documents/dispatch-guides/{contract}/edit', [App\Http\Controllers\DocumentGeneratorController::class, 'editDispatchGuide'])->name('documents.dispatch-guides.edit');
    Route::post('/documents/dispatch-guides/{contract}', [App\Http\Controllers\DocumentGeneratorController::class, 'storeDispatchGuide'])->name('documents.dispatch-guides.store');
    
    Route::get('/documents/invoice', [App\Http\Controllers\DocumentGeneratorController::class, 'listInvoices'])->name('documents.invoice.list');
    Route::get('/documents/invoice/{contract}/edit', [App\Http\Controllers\DocumentGeneratorController::class, 'editInvoice'])->name('documents.invoice.edit');
    Route::post('/documents/invoice/{contract}', [App\Http\Controllers\DocumentGeneratorController::class, 'storeInvoice'])->name('documents.invoice.store');
    
    // Documents routes (DEBE ir DESPUÉS de las rutas específicas de arriba)
    Route::resource('documents', App\Http\Controllers\DocumentController::class);
    Route::post('/documents/{id}/generate', [App\Http\Controllers\DocumentController::class, 'generate'])->name('documents.generate');
    Route::post('/documents/{id}/send', [App\Http\Controllers\DocumentController::class, 'send'])->name('documents.send');

    // Generación de Documentos Editables desde el Contrato
    Route::get('/contracts/{id}/generate/bill-of-lading', [App\Http\Controllers\ContractController::class, 'generateBillOfLading'])->name('contracts.generate.bill-of-lading');
    Route::get('/contracts/{id}/generate/invoice', [App\Http\Controllers\ContractController::class, 'generateInvoice'])->name('contracts.generate.invoice');
    Route::get('/contracts/{id}/generate/packing-list', [App\Http\Controllers\ContractController::class, 'generatePackingList'])->name('contracts.generate.packing-list');
    Route::get('/contracts/{id}/generate/certificate-origin', [App\Http\Controllers\ContractController::class, 'generateCertificateOfOrigin'])->name('contracts.generate.certificate-origin');
    Route::get('/contracts/{id}/generate/phytosanitary', [App\Http\Controllers\ContractController::class, 'generatePhytosanitaryCertificate'])->name('contracts.generate.phytosanitary');
    Route::get('/contracts/{id}/generate/quality', [App\Http\Controllers\ContractController::class, 'generateQualityCertificate'])->name('contracts.generate.quality');
    Route::post('/contracts/{id}/send-document', [App\Http\Controllers\ContractController::class, 'sendDocument'])->name('contracts.send-document');
    
    // Contract Documents routes
    Route::post('/contracts/{contract}/documents/upload', [App\Http\Controllers\ContractDocumentController::class, 'upload'])->name('contracts.documents.upload');
    Route::get('/contracts/{contract}/documents/{type}', [App\Http\Controllers\ContractDocumentController::class, 'getByType'])->name('contracts.documents.byType');
    Route::get('/contract-documents/{document}/download', [App\Http\Controllers\ContractDocumentController::class, 'download'])->name('contract-documents.download');
    Route::delete('/contract-documents/{document}', [App\Http\Controllers\ContractDocumentController::class, 'destroy'])->name('contract-documents.destroy');
    Route::get('/contracts/{contract}/quality-certificate', [App\Http\Controllers\ContractDocumentController::class, 'generateQualityCertificate'])->name('contracts.quality-certificate');
    
    // Exportations routes
    Route::resource('exportations', App\Http\Controllers\ExportationController::class);
    Route::post('/exportations/{id}/upload-document', [App\Http\Controllers\ExportationController::class, 'uploadDocument'])->name('exportations.upload-document');
    
    // Generate PDF Documents
    Route::get('/exportations/{id}/generate/bill-of-lading', [App\Http\Controllers\ExportationController::class, 'generateBillOfLading'])->name('exportations.generate.bill-of-lading');
    Route::get('/exportations/{id}/generate/invoice', [App\Http\Controllers\ExportationController::class, 'generateInvoice'])->name('exportations.generate.invoice');
    Route::get('/exportations/{id}/generate/packing-list', [App\Http\Controllers\ExportationController::class, 'generatePackingList'])->name('exportations.generate.packing-list');
    Route::get('/exportations/{id}/generate/certificate-origin', [App\Http\Controllers\ExportationController::class, 'generateCertificateOfOrigin'])->name('exportations.generate.certificate-origin');
    Route::get('/exportations/{id}/generate/phytosanitary', [App\Http\Controllers\ExportationController::class, 'generatePhytosanitaryCertificate'])->name('exportations.generate.phytosanitary');
    Route::get('/exportations/{id}/generate/quality', [App\Http\Controllers\ExportationController::class, 'generateQualityCertificate'])->name('exportations.generate.quality');
    
    // Send Documents by Email
    Route::post('/exportations/{id}/send-document', [App\Http\Controllers\ExportationController::class, 'sendDocument'])->name('exportations.send-document');
    
    // Módulo de Procesamiento
    Route::prefix('processing')->name('processing.')->group(function () {
        // Mantenedor de Plantas
        Route::resource('plants', App\Http\Controllers\PlantController::class);
        
        // Programa de Producción (Seguimiento de Producción)
        Route::resource('production-orders', App\Http\Controllers\PlantProductionOrderController::class);
        
        // Envío de Órdenes
        Route::resource('orders', App\Http\Controllers\ProcessOrderController::class);
        Route::post('/orders/{order}/update-progress', [App\Http\Controllers\ProcessOrderController::class, 'updateProgress'])->name('orders.update-progress');
        Route::post('/orders/{order}/send-alert', [App\Http\Controllers\ProcessOrderController::class, 'sendAlert'])->name('orders.send-alert');
        
        // Facturas de Proceso
        Route::resource('invoices', App\Http\Controllers\ProcessInvoiceController::class);
        Route::post('/invoices/{invoice}/mark-paid', [App\Http\Controllers\ProcessInvoiceController::class, 'markPaid'])->name('invoices.mark-paid');
        
        // Módulo de Contabilidad
        Route::resource('accounting', App\Http\Controllers\AccountingRecordController::class);
    });

    // Discards Management
    Route::prefix('discards')->group(function () {
        Route::get('/', [App\Http\Controllers\DiscardController::class, 'index'])->name('discards.index');
        Route::get('/create', [App\Http\Controllers\DiscardController::class, 'create'])->name('discards.create');
        Route::post('/', [App\Http\Controllers\DiscardController::class, 'store'])->name('discards.store');
        Route::get('/{discard}', [App\Http\Controllers\DiscardController::class, 'show'])->name('discards.show');
        Route::post('/{discard}/recover', [App\Http\Controllers\DiscardController::class, 'recover'])->name('discards.recover');
        Route::post('/{discard}/dispose', [App\Http\Controllers\DiscardController::class, 'dispose'])->name('discards.dispose');
        Route::post('/bulk-recover', [App\Http\Controllers\DiscardController::class, 'bulkRecover'])->name('discards.bulk-recover');
    });
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
