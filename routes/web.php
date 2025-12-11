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
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
