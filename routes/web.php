<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StockController;

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

    Route::get('/final-output/download-invoice', [App\Http\Controllers\Admin\FinalOutputController::class, 'downloadInvoice'])
        ->name('final-output.download-invoice');
    Route::get('/production/download-report', [App\Http\Controllers\Admin\ProductionController::class, 'downloadReport'])
        ->name('production.download-report');
    Route::get('/consumption/download-report', [App\Http\Controllers\Admin\ConsumptionController::class, 'downloadReport'])
        ->name('consumption.download-report');
    Route::get('grn/{id}/download', [App\Http\Controllers\Admin\GrnController::class, 'downloadPdf'])->name('grn.download');
});

Route::get('/admin/stock/movement/{id}/download', [StockController::class, 'downloadMovement'])->name('admin.stock.movement.download');
Route::get('/test-pdf', [App\Http\Controllers\Admin\FinalOutputController::class, 'testPdf'])->name('test.pdf');


