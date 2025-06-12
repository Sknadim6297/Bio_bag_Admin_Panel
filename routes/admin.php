<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ConsumptionController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FinalOutputController;
use App\Http\Controllers\Admin\GrnController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\ProductionController;
use App\Http\Controllers\Admin\SkuController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\Wastage1Controller;
use App\Http\Controllers\Admin\Wastage2Controller;

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

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.submit');

// Protected routes
Route::middleware('auth:admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Wastage Management Routes
    Route::get('/wastage1', [Wastage1Controller::class, 'index'])->name('wastage1.index');
    Route::get('/wastage2', [Wastage2Controller::class, 'index'])->name('wastage2.index');

    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
    Route::resource('vendors', VendorController::class);
    Route::get('/vendors/search', [VendorController::class, 'search'])->name('vendor.search');

    //customer Routes
    Route::get('customers/search', [CustomerController::class, 'search'])->name('customer.search');

    Route::resource('customer', CustomerController::class);

    //Production Routes
    Route::get('production/filter', [ProductionController::class, 'filter'])->name('production.filter');
    Route::resource('production', ProductionController::class);

    Route::get('consumption/search', [ConsumptionController::class, 'search'])->name('consumption.search');
    Route::resource('consumption', ConsumptionController::class);

    Route::resource('category', CategoryController::class);
    Route::get('categories/search', [CategoryController::class, 'search'])->name('category.search');

    Route::resource('sku', SkuController::class);
    Route::resource('final-output', FinalOutputController::class);

    Route::get('stock/search', [StockController::class, 'search'])->name('stock.search');
    Route::get('/stock/export', [StockController::class, 'export'])->name('stock.export');
    Route::resource('stock', StockController::class);
    Route::get('stock/movement/{id}', [StockController::class, 'getStockMovement'])->name('stock.movement');


    Route::resource('grn', GrnController::class);
    Route::get('grn/show/{po_number}', [GrnController::class, 'show'])->name('grn.show');

    Route::get('/get-product-suggestions', [GrnController::class, 'getSuggestions'])->name('grn.suggestions');

    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoice.index');
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoice.create');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoice.store');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoice.show');
    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoice.pdf');

    // AJAX routes for invoice form
    Route::get('/get-customers', [InvoiceController::class, 'getCustomers'])->name('get-customers');
    Route::get('/get-customer-details', [InvoiceController::class, 'getCustomerDetails'])->name('get-customer-details');
    Route::get('/get-micron-values', [InvoiceController::class, 'getMicronValues'])->name('get-micron-values');
    Route::get('/get-sizes-by-micron', [InvoiceController::class, 'getSizesByMicron'])->name('get-sizes-by-micron');
    Route::get('/get-inventory-items', [InvoiceController::class, 'getInventoryItems'])->name('get-inventory-items');
});
