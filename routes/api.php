<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// QR scanning endpoint (may need different auth for mobile apps)
Route::post('/scan-qr', [App\Http\Controllers\BinProcessingController::class, 'scan']);

// Get bins for a supplier
Route::get('/supplier/{supplier}/bins', [App\Http\Controllers\BinReceptionController::class, 'getSupplierBins']);

// Get delivered bins for a supplier
Route::get('/supplier/{supplier}/delivered-bins', [App\Http\Controllers\BinReceptionController::class, 'getDeliveredBins']);
