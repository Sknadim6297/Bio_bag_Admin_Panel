<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ConsumptionController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\GrnController;
use App\Http\Controllers\Admin\ProductionController;
use App\Http\Controllers\Admin\SkuController;
use App\Http\Controllers\Admin\VendorController;

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
    Route::get('/dashboard', function () {
        return view('admin.index');
    })->name('admin.dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
    Route::resource('vendors', VendorController::class);
    //customer Routes
    Route::get('customers/search', [CustomerController::class, 'search'])->name('customer.search');

    Route::resource('customer', CustomerController::class);

    //Production Routes
    Route::get('production/filter', [ProductionController::class, 'filter'])->name('production.filter');
    Route::resource('production', ProductionController::class);

    Route::get('consumption/search', [ConsumptionController::class, 'search'])->name('consumption.search');
    Route::resource('consumption', ConsumptionController::class);

    Route::resource('category', CategoryController::class);
    Route::resource('sku', SkuController::class);

    Route::get('manage-grn', [GrnController::class, 'edit'])->name('grn.manage');
    Route::resource('grn', GrnController::class);
});
