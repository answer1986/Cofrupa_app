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
    Route::get('/users-access', [App\Http\Controllers\UserController::class, 'access'])->name('users.access')->middleware('can:manage users');
    Route::post('/users-access/{roleId}', [App\Http\Controllers\UserController::class, 'updateRolePermissions'])->name('users.update-permissions')->middleware('can:manage users');
    Route::resource('suppliers', App\Http\Controllers\SupplierController::class);
    Route::resource('bins', App\Http\Controllers\BinController::class);
    // Quick purchase route (must be before resource route)
    Route::get('/purchases/quick-create', [App\Http\Controllers\PurchaseController::class, 'quickCreate'])->name('purchases.quick-create');
    Route::post('/purchases/quick-store', [App\Http\Controllers\PurchaseController::class, 'quickStore'])->name('purchases.quick-store');
    Route::post('/purchases/quick-create-supplier', [App\Http\Controllers\PurchaseController::class, 'quickCreateSupplier'])->name('purchases.quick-create-supplier');
    Route::resource('purchases', App\Http\Controllers\PurchaseController::class);

    // Bin Reception (initial QR generation for received bins)
    Route::resource('bin_reception', App\Http\Controllers\BinReceptionController::class)->middleware('can:manage processed bins');
    Route::post('/bin_reception/quick-create-supplier', [App\Http\Controllers\BinReceptionController::class, 'quickCreateSupplier'])->name('bin_reception.quick-create-supplier')->middleware('can:manage processed bins');

    // Processed Bins (management after reception)
    Route::resource('processed_bins', App\Http\Controllers\ProcessedBinController::class)->middleware('can:manage processed bins');

    // Bin Processing (mixing and calibration)
    Route::resource('bin_processing', App\Http\Controllers\BinProcessingController::class)->middleware('can:manage processed bins');
    Route::post('/bin_processing/quick-create-external-client', [App\Http\Controllers\BinProcessingController::class, 'quickCreateExternalClient'])->name('bin_processing.quick-create-external-client')->middleware('can:manage processed bins');
    Route::get('/bin_processing/{id}/traceability', [App\Http\Controllers\BinProcessingController::class, 'traceability'])->name('bin_processing.traceability')->middleware('can:manage processed bins');
    
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

    // Bitácora de eventos de la campana (pendientes atendidos / no atendidos)
    Route::get('/vitacora', [App\Http\Controllers\VitacoraController::class, 'index'])->name('vitacora.index')->middleware('can:manage users');
    Route::post('/vitacora', [App\Http\Controllers\VitacoraController::class, 'store'])->name('vitacora.store')->middleware('can:manage users');

    // Reports routes
    Route::get('/reports', [App\Http\Controllers\ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/payments', [App\Http\Controllers\ReportsController::class, 'payments'])->name('reports.payments');
    Route::get('/reports/supplier-debts', [App\Http\Controllers\ReportsController::class, 'supplierDebts'])->name('reports.supplier-debts');

    // Clients and Brokers routes
    Route::resource('clients', App\Http\Controllers\ClientController::class);
    Route::post('/clients/conversations/store', [App\Http\Controllers\ClientController::class, 'storeConversation'])->name('clients.conversations.store');
    Route::resource('brokers', App\Http\Controllers\BrokerController::class);
    Route::resource('customs-agencies', App\Http\Controllers\CustomsAgencyController::class);
    
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
    Route::get('/documents/quality-certificate/create', [App\Http\Controllers\DocumentGeneratorController::class, 'createQualityCertificate'])->name('documents.quality-certificate.create');
    Route::get('/documents/quality-certificate/{contract}/edit', [App\Http\Controllers\DocumentGeneratorController::class, 'editQualityCertificate'])->name('documents.quality-certificate.edit');
    Route::post('/documents/quality-certificate/{contract}/preview', [App\Http\Controllers\DocumentGeneratorController::class, 'previewQualityCertificate'])->name('documents.quality-certificate.preview');
    Route::post('/documents/quality-certificate/{contract}/send', [App\Http\Controllers\DocumentGeneratorController::class, 'sendQualityCertificate'])->name('documents.quality-certificate.send');
    Route::post('/documents/quality-certificate/{contract}', [App\Http\Controllers\DocumentGeneratorController::class, 'storeQualityCertificate'])->name('documents.quality-certificate.store');
    
    // Certificado de Calidad - Unión Europea
    Route::get('/documents/quality-certificate-eu', [App\Http\Controllers\DocumentGeneratorController::class, 'listQualityCertificatesEU'])->name('documents.quality-certificate-eu.list');
    Route::get('/documents/quality-certificate-eu/create', [App\Http\Controllers\DocumentGeneratorController::class, 'createQualityCertificateEU'])->name('documents.quality-certificate-eu.create');
    Route::get('/documents/quality-certificate-eu/{contract}/edit', [App\Http\Controllers\DocumentGeneratorController::class, 'editQualityCertificateEU'])->name('documents.quality-certificate-eu.edit');
    Route::post('/documents/quality-certificate-eu/{contract}/preview', [App\Http\Controllers\DocumentGeneratorController::class, 'previewQualityCertificateEU'])->name('documents.quality-certificate-eu.preview');
    Route::post('/documents/quality-certificate-eu/{contract}/send', [App\Http\Controllers\DocumentGeneratorController::class, 'sendQualityCertificateEU'])->name('documents.quality-certificate-eu.send');
    Route::post('/documents/quality-certificate-eu/{contract}', [App\Http\Controllers\DocumentGeneratorController::class, 'storeQualityCertificateEU'])->name('documents.quality-certificate-eu.store');
    
    Route::get('/documents/shipping-instructions', [App\Http\Controllers\DocumentGeneratorController::class, 'listShippingInstructions'])->name('documents.shipping-instructions.list');
    Route::get('/documents/shipping-instructions/create', [App\Http\Controllers\DocumentGeneratorController::class, 'createShippingInstructions'])->name('documents.shipping-instructions.create');
    Route::get('/documents/shipping-instructions/{contract}/edit', [App\Http\Controllers\DocumentGeneratorController::class, 'editShippingInstructions'])->name('documents.shipping-instructions.edit');
    Route::post('/documents/shipping-instructions/{contract}/preview', [App\Http\Controllers\DocumentGeneratorController::class, 'previewShippingInstructions'])->name('documents.shipping-instructions.preview');
    Route::post('/documents/shipping-instructions/{contract}/send', [App\Http\Controllers\DocumentGeneratorController::class, 'sendShippingInstructions'])->name('documents.shipping-instructions.send');
    Route::post('/documents/shipping-instructions/{contract}', [App\Http\Controllers\DocumentGeneratorController::class, 'storeShippingInstructions'])->name('documents.shipping-instructions.store');
    
    Route::get('/documents/transport-instructions', [App\Http\Controllers\DocumentGeneratorController::class, 'listTransportInstructions'])->name('documents.transport-instructions.list');
    Route::get('/documents/transport-instructions/create', [App\Http\Controllers\DocumentGeneratorController::class, 'createTransportInstructions'])->name('documents.transport-instructions.create');
    Route::get('/documents/transport-instructions/{contract}/edit', [App\Http\Controllers\DocumentGeneratorController::class, 'editTransportInstructions'])->name('documents.transport-instructions.edit');
    Route::post('/documents/transport-instructions/{contract}/preview', [App\Http\Controllers\DocumentGeneratorController::class, 'previewTransportInstructions'])->name('documents.transport-instructions.preview');
    Route::post('/documents/transport-instructions/{contract}/send', [App\Http\Controllers\DocumentGeneratorController::class, 'sendTransportInstructions'])->name('documents.transport-instructions.send');
    Route::post('/documents/transport-instructions/{contract}', [App\Http\Controllers\DocumentGeneratorController::class, 'storeTransportInstructions'])->name('documents.transport-instructions.store');
    
    Route::get('/documents/dispatch-guides', [App\Http\Controllers\DocumentGeneratorController::class, 'listDispatchGuides'])->name('documents.dispatch-guides.list');
    Route::get('/documents/dispatch-guides/create', [App\Http\Controllers\DocumentGeneratorController::class, 'createDispatchGuide'])->name('documents.dispatch-guides.create');
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
        // Mantenciones de Máquinas (ruta principal /processing/)
        Route::get('/', [App\Http\Controllers\MachineMaintenanceController::class, 'index'])->name('maintenances.index');
        Route::resource('maintenances', App\Http\Controllers\MachineMaintenanceController::class)->except(['index']);
        
        // Gestión de Máquinas Internas
        Route::resource('machines', App\Http\Controllers\MachineController::class);
        
        // Mantenedor de Plantas
        Route::resource('plants', App\Http\Controllers\PlantController::class);
        
        // Programa de Producción (Histórico de Envíos a Producción)
        // COMENTADO: Funcionalidad de crear deshabilitada - solo histórico
        Route::get('/production-orders', [App\Http\Controllers\PlantProductionOrderController::class, 'index'])->name('production-orders.index');
        Route::get('/production-orders/{productionOrder}', [App\Http\Controllers\PlantProductionOrderController::class, 'show'])->name('production-orders.show');
        Route::get('/production-orders/{productionOrder}/edit', [App\Http\Controllers\PlantProductionOrderController::class, 'edit'])->name('production-orders.edit');
        Route::put('/production-orders/{productionOrder}', [App\Http\Controllers\PlantProductionOrderController::class, 'update'])->name('production-orders.update');
        Route::delete('/production-orders/{productionOrder}', [App\Http\Controllers\PlantProductionOrderController::class, 'destroy'])->name('production-orders.destroy');
        // Route::get('/production-orders/create', [App\Http\Controllers\PlantProductionOrderController::class, 'create'])->name('production-orders.create'); // COMENTADO
        // Route::post('/production-orders', [App\Http\Controllers\PlantProductionOrderController::class, 'store'])->name('production-orders.store'); // COMENTADO
        
        // Petición de cupos (tarjas rebajadas, kilos enviados vs devueltos, rendimiento)
        Route::get('/request', [App\Http\Controllers\CupoRequestController::class, 'index'])->name('request.index');

        // Envío de Órdenes
        Route::resource('orders', App\Http\Controllers\ProcessOrderController::class);
        Route::post('/orders/{order}/update-progress', [App\Http\Controllers\ProcessOrderController::class, 'updateProgress'])->name('orders.update-progress');
        Route::post('/orders/{order}/send-alert', [App\Http\Controllers\ProcessOrderController::class, 'sendAlert'])->name('orders.send-alert');
        Route::get('/orders/{order}/preview-pdf', [App\Http\Controllers\ProcessOrderController::class, 'previewPdf'])->name('orders.preview-pdf');
        Route::post('/orders/{order}/send-to-plant', [App\Http\Controllers\ProcessOrderController::class, 'sendToPlant'])->name('orders.send-to-plant');
        
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

    // Finance Module (Módulo de Finanzas: Cofrupa, Luis Gonzalez, Comercializadora)
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/', [App\Http\Controllers\FinanceController::class, 'index'])->name('index');
        
        // Deuda/Capital por banco (registro aparte)
        Route::get('/bank-debts/create', [App\Http\Controllers\FinanceController::class, 'createBankDebt'])->name('bank-debts.create');
        Route::post('/bank-debts', [App\Http\Controllers\FinanceController::class, 'storeBankDebt'])->name('bank-debts.store');
        Route::get('/bank-debts/{bankDebt}/edit', [App\Http\Controllers\FinanceController::class, 'editBankDebt'])->name('bank-debts.edit');
        Route::put('/bank-debts/{bankDebt}', [App\Http\Controllers\FinanceController::class, 'updateBankDebt'])->name('bank-debts.update');
        Route::delete('/bank-debts/{bankDebt}', [App\Http\Controllers\FinanceController::class, 'destroyBankDebt'])->name('bank-debts.destroy');
        
        // Compras
        Route::get('/purchases/create', [App\Http\Controllers\FinanceController::class, 'createPurchase'])->name('purchases.create');
        Route::post('/purchases', [App\Http\Controllers\FinanceController::class, 'storePurchase'])->name('purchases.store');
        Route::get('/purchases/{purchase}/edit', [App\Http\Controllers\FinanceController::class, 'editPurchase'])->name('purchases.edit');
        Route::put('/purchases/{purchase}', [App\Http\Controllers\FinanceController::class, 'updatePurchase'])->name('purchases.update');
        Route::delete('/purchases/{purchase}', [App\Http\Controllers\FinanceController::class, 'destroyPurchase'])->name('purchases.destroy');
        
        // Ventas
        Route::get('/sales/create', [App\Http\Controllers\FinanceController::class, 'createSale'])->name('sales.create');
        Route::post('/sales', [App\Http\Controllers\FinanceController::class, 'storeSale'])->name('sales.store');
        Route::get('/sales/{sale}/edit', [App\Http\Controllers\FinanceController::class, 'editSale'])->name('sales.edit');
        Route::put('/sales/{sale}', [App\Http\Controllers\FinanceController::class, 'updateSale'])->name('sales.update');
        Route::delete('/sales/{sale}', [App\Http\Controllers\FinanceController::class, 'destroySale'])->name('sales.destroy');
    });
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
