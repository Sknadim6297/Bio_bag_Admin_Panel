<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\InvoiceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/admin/dashboard');
});

Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    Route::get('/production/download-report', [App\Http\Controllers\Admin\ProductionController::class, 'downloadReport'])
        ->name('production.download-report');
    Route::get('/consumption/download-report', [App\Http\Controllers\Admin\ConsumptionController::class, 'downloadReport'])
        ->name('consumption.download-report');
    Route::get('grn/{id}/download', [App\Http\Controllers\Admin\GrnController::class, 'downloadPdf'])->name('grn.download');

    // Inventory routes
    Route::resource('inventory', InventoryController::class);
    Route::get('fetch-final-output/{customer_id}', [InventoryController::class, 'fetchFinalOutput'])
        ->name('fetch-final-output');
    
    // Invoice routes
    Route::resource('invoice', InvoiceController::class);
    Route::post('search-inventory', [InvoiceController::class, 'searchInventory'])->name('search-inventory');
    Route::get('invoice/{id}/download', [InvoiceController::class, 'downloadPdf'])->name('invoice.download');

    // Stocks list route
    Route::get('stocks/list', [App\Http\Controllers\Admin\ConsumptionController::class, 'stocksList'])->name('stocks.list');
});

Route::get('/admin/stock/movement/{id}/download', [StockController::class, 'downloadMovement'])->name('admin.stock.movement.download');
