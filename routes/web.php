<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CashierController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KasirController;
use App\Http\Controllers\Admin\ReturController;
use App\Http\Controllers\Admin\MarketingController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfilController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SupplyController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Kasir\DashboardController as KasirDashboardController;
use App\Http\Controllers\Kasir\ProfilController as KasirProfilController;
use App\Http\Controllers\Kasir\ReportController as KasirReportController;
use App\Http\Controllers\Kasir\TransactionController as KasirTransactionController;
use App\Http\Controllers\Marketing\DashboardController as MarketingDashboardController;
use App\Http\Controllers\Marketing\ProductController as MarketingProductController;
use App\Http\Controllers\Marketing\TransactionController as MarketingTransactionController;
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
    return view('auth.login');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('', [DashboardController::class, 'index'])->name('index');
    });
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('', [AdminController::class, 'index'])->name('index');
        Route::post('store', [AdminController::class, 'store'])->name('store');
        Route::put('update', [AdminController::class, 'update'])->name('update');
        Route::delete('delete', [AdminController::class, 'delete'])->name('delete');
        Route::put('change-password', [AdminController::class, 'changePassword'])->name('change_password');
    });
    Route::prefix('cashier')->name('cashier.')->group(function () {
        Route::get('', [CashierController::class, 'index'])->name('index');
        Route::post('store', [CashierController::class, 'store'])->name('store');
        Route::put('update', [CashierController::class, 'update'])->name('update');
        Route::delete('delete', [CashierController::class, 'delete'])->name('delete');
        Route::put('change-password', [CashierController::class, 'changePassword'])->name('change_password');
    });
    Route::prefix('category')->name('category.')->group(function () {
        Route::get('', [CategoryController::class, 'index'])->name('index');
        Route::post('store', [CategoryController::class, 'store'])->name('store');
        Route::put('update', [CategoryController::class, 'update'])->name('update');
        Route::delete('delete', [CategoryController::class, 'delete'])->name('delete');
    });
    Route::prefix('product')->name('product.')->group(function () {
        Route::get('', [ProductController::class, 'index'])->name('index');
        Route::post('store', [ProductController::class, 'store'])->name('store');
        Route::put('update', [ProductController::class, 'update'])->name('update');
        Route::delete('delete', [ProductController::class, 'delete'])->name('delete');
        Route::get('print', [ProductController::class, 'print'])->name('printproduct');
    });
    Route::prefix('supply')->name('supply.')->group(function () {
        Route::get('', [SupplyController::class, 'index'])->name('index');
        Route::post('store', [SupplyController::class, 'store'])->name('store');
        Route::put('update', [SupplyController::class, 'update'])->name('update');
        Route::delete('delete', [SupplyController::class, 'delete'])->name('delete');
        Route::get('show/{id}', [SupplyController::class, 'show'])->name('show');
    });
    Route::prefix('retur')->name('retur.')->group(function () {
        Route::get('', [ReturController::class, 'index'])->name('index');
        Route::post('store', [ReturController::class, 'store'])->name('store');
        Route::put('update', [ReturController::class, 'update'])->name('update');
        Route::delete('delete', [ReturController::class, 'delete'])->name('delete');
        Route::get('show/{id}', [ReturController::class, 'show'])->name('show');
    });
    Route::prefix('marketing')->name('marketing.')->group(function () {
        Route::get('', [MarketingController::class, 'index'])->name('index');
        Route::post('store', [MarketingController::class, 'store'])->name('store');
        Route::put('update', [MarketingController::class, 'update'])->name('update');
        Route::delete('delete', [MarketingController::class, 'delete'])->name('delete');
        Route::get('show/{id}', [MarketingController::class, 'show'])->name('show');
    });
    Route::prefix('transaction')->name('transaction.')->group(function () {
        Route::get('', [TransactionController::class, 'index'])->name('index');
        Route::get('/api', [TransactionController::class, 'indexs'])->name('indexs');
        Route::post('store', [TransactionController::class, 'store'])->name('store');
        Route::put('update', [TransactionController::class, 'update'])->name('update');
        Route::delete('delete', [TransactionController::class, 'delete'])->name('delete');
        Route::get('show/{id}', [TransactionController::class, 'show'])->name('show'); // Perbaikan: Hapus definisi ini jika sudah ada di atas
        Route::get('getProductCode', [TransactionController::class, 'getProductCode'])->name('getProductCode');
        Route::post('addToCart', [TransactionController::class, 'addToCart'])->name('addToCart');
        Route::delete('deleteCart', [TransactionController::class, 'deleteCart'])->name('deleteCart');
        Route::get('totalBuy', [TransactionController::class, 'totalBuy'])->name('totalBuy');
        Route::post('pay', [TransactionController::class, 'pay'])->name('pay');
        Route::get('kasir/transaction/totalBuy', [TransactionController::class, 'totalBuy'])->name('kasir.transaction.totalBuy');
    });

    Route::prefix('report')->name('report.')->group(function () {
        Route::get('', [ReportController::class, 'index'])->name('index');
        Route::get('show/{id}', [ReportController::class, 'show'])->name('show');
        Route::get('cetak-nota/{id}', [ReportController::class, 'print'])->name('print');
        Route::delete('delete', [ReportController::class, 'delete'])->name('delete');
    });

    Route::prefix('setting')->name('setting.')->group(function () {
        Route::get('', [SettingController::class, 'index'])->name('index');
        Route::post('store', [SettingController::class, 'store'])->name('store');
        Route::put('update', [SettingController::class, 'update'])->name('update');
        Route::delete('delete', [SettingController::class, 'delete'])->name('delete');
    });
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('', [ProfilController::class, 'index'])->name('index');
        Route::put('update', [ProfilController::class, 'update'])->name('update');
    });
});

Route::prefix('kasir')->name('kasir.')->middleware(['auth', 'isCashier'])->group(function () {
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('', [KasirDashboardController::class, 'index'])->name('index');
    });
    Route::prefix('transaction')->name('transaction.')->group(function () {
        Route::get('', [KasirTransactionController::class, 'index'])->name('index');
        Route::get('/api', [KasirTransactionController::class, 'indexs'])->name('indexs');
        Route::post('store', [KasirTransactionController::class, 'store'])->name('store');
        Route::put('update', [KasirTransactionController::class, 'update'])->name('update');
        Route::delete('delete', [KasirTransactionController::class, 'delete'])->name('delete');
        Route::get('show/{id}', [KasirTransactionController::class, 'show'])->name('show');
        Route::get('getProductCode', [KasirTransactionController::class, 'getProductCode'])->name('getProductCode');
        Route::post('addToCart', [KasirTransactionController::class, 'addToCart'])->name('addToCart');
        Route::delete('deleteCart', [KasirTransactionController::class, 'deleteCart'])->name('deleteCart');
        Route::get('totalBuy', [KasirTransactionController::class, 'totalBuy'])->name('totalBuy');
        Route::get('getPembeli', [KasirTransactionController::class, 'getPembeli'])->name('getPembeli');
        Route::post('pay', [KasirTransactionController::class, 'pay'])->name('pay');
        Route::get('show/{id}', [KasirTransactionController::class, 'show'])->name('show');
    });
    Route::prefix('report')->name('report.')->group(function () {
        Route::get('', [KasirReportController::class, 'index'])->name('index');
        Route::get('show/{id}', [KasirReportController::class, 'show'])->name('show');
        Route::get('cetak-nota/{id}', [KasirReportController::class, 'print'])->name('print');
        Route::delete('delete', [KasirReportController::class, 'delete'])->name('delete');
    });
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('', [KasirProfilController::class, 'index'])->name('index');
        Route::put('update', [KasirProfilController::class, 'update'])->name('update');
    });
});
Route::prefix('marketing')->name('marketing.')->middleware(['auth', 'isMarketing'])->group(function () {
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('', [MarketingDashboardController::class, 'index'])->name('index');
    });
    Route::prefix('product')->name('product.')->group(function () {
        Route::get('', [MarketingProductController::class, 'index'])->name('index');
        Route::post('store', [ProductController::class, 'store'])->name('store');
        Route::put('update', [ProductController::class, 'update'])->name('update');
        Route::delete('delete', [ProductController::class, 'delete'])->name('delete');
        Route::get('print', [ProductController::class, 'print'])->name('printproduct');
    });
    Route::prefix('transaction')->name('transaction.')->group(function () {
        Route::get('', [MarketingTransactionController::class, 'index'])->name('index');
        Route::get('/api', [MarketingTransactionController::class, 'indexs'])->name('indexs');
        Route::post('store', [MarketingTransactionController::class, 'store'])->name('store');
        Route::put('update', [MarketingTransactionController::class, 'update'])->name('update');
        Route::delete('delete', [MarketingTransactionController::class, 'delete'])->name('delete');
        Route::get('show/{id}', [MarketingTransactionController::class, 'show'])->name('show');
        Route::get('getProductCode', [MarketingTransactionController::class, 'getProductCode'])->name('getProductCode');
        Route::post('addToCart', [MarketingTransactionController::class, 'addToCart'])->name('addToCart');
        Route::delete('deleteCart', [MarketingTransactionController::class, 'deleteCart'])->name('deleteCart');
        Route::get('totalBuy', [MarketingTransactionController::class, 'totalBuy'])->name('totalBuy');
        Route::post('pay', [MarketingTransactionController::class, 'pay'])->name('pay');
        Route::post('updateChartStatus', [MarketingTransactionController::class, 'updateChartStatus'])->name('updateChartStatus');
        Route::get('show/{id}', [MarketingTransactionController::class, 'show'])->name('show');
    });
});
Auth::routes(['register' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
