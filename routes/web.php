<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MenuController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\ReservasiController as AdminReservasiController;
use App\Http\Controllers\Admin\IkanController as AdminIkanController;
use App\Http\Controllers\Admin\MejaController as AdminMejaController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Customer\MenuController as CustomerMenuController;
use App\Http\Controllers\Kasir\InvoiceController as KasirInvoiceController;

use App\Http\Controllers\Kasir\PesananController as KasirPesananController;
use App\Http\Controllers\Admin\KategoriController as AdminKategoriController;

use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Kasir\DashboardController as KasirDashboardController;

use App\Http\Controllers\Kitchen\PesananController as KitchenPesananController;
use App\Http\Controllers\Admin\MetodeMasakController as AdminMetodeMasakController;
use App\Http\Controllers\Kitchen\DashboardController as KitchenDashboardController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\ReservasiController as CustomerReservasiController;


Route::get('/', [LandingController::class, 'landing'])->name('landing');
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');



Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');

Route::get('/api/customer/pesanan-aktif', [CustomerDashboardController::class, 'getStatusPesananAktif'])->name('api.customer.pesanan.status');



Route::get('/dashboard-redirect', function () {
    $user = Auth::user();

    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'kitchen':
            return redirect()->route('kitchen.dashboard');
        case 'kasir':
            return redirect()->route('kasir.dashboard');
        case 'cust':

            return redirect()->route('customer.dashboard');
        default:

            return redirect('/');
    }
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('menu', AdminMenuController::class);
    Route::resource('ikan', AdminIkanController::class)->names('ikan');
    Route::resource('kategori', AdminKategoriController::class);
    Route::resource('metode-masak', AdminMetodeMasakController::class)
        ->parameters(['metode-masak' => 'metode']);
    Route::resource('meja', AdminMejaController::class);
    Route::resource('user', AdminUserController::class);
    Route::get('/laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
    Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export-pdf');

    Route::resource('reservasi', AdminReservasiController::class);
    Route::patch('reservasi/{reservasi}/hadir', [AdminReservasiController::class, 'markAsHadir'])->name('reservasi.hadir');
    Route::patch('reservasi/{reservasi}/batal', [AdminReservasiController::class, 'markAsBatal'])->name('reservasi.batal');
    Route::patch('reservasi/{reservasi}/konfirmasi', [AdminReservasiController::class, 'markAsDikonfirmasi'])->name('reservasi.konfirmasi');
});



Route::prefix('pesan')->name('order.')->group(function () {
    Route::get('/{meja:nomor_meja}', [CustomerOrderController::class, 'show'])->name('show');
    Route::post('/{meja:nomor_meja}/add', [CustomerOrderController::class, 'addToCart'])->name('cart.add');
    Route::get('/{meja:nomor_meja}/cart', [CustomerOrderController::class, 'viewCart'])->name('cart.view');
    Route::post('/{meja:nomor_meja}/remove', [CustomerOrderController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/{meja:nomor_meja}/place', [CustomerOrderController::class, 'placeOrder'])->name('place');
    Route::get('/sukses/{pesanan_id}', [CustomerOrderController::class, 'success'])->name('success');
});




Route::middleware(['auth', 'role:kitchen'])->prefix('kitchen')->name('kitchen.')->group(function () {

    Route::get('/dashboard', [KitchenDashboardController::class, 'index'])->name('dashboard');

Route::patch('/kitchen/pesanan/{pesanan}/update-status', [KitchenDashboardController::class, 'updateStatus'])->name('kitchen.pesanan.updateStatus');
});



Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/dashboard', [KasirDashboardController::class, 'index'])->name('dashboard');
    Route::resource('pesanan', KasirPesananController::class);
    Route::resource('invoice', KasirInvoiceController::class)->only(['index', 'store', 'show']);
});


Route::middleware(['auth', 'role:cust'])->prefix('customer')->name('customer.')->group(function () {


    Route::get('/menu', [CustomerMenuController::class, 'index'])->name('menu.index');
    Route::get('/menu/{menu}', [CustomerMenuController::class, 'show'])->name('menu.show');
    Route::get('/kategori', [CustomerMenuController::class, 'kategori'])->name('kategori');


    Route::resource('reservasi', CustomerReservasiController::class);
     Route::get('reservasi-check', [CustomerReservasiController::class, 'availableTables'])->name('reservasi.availableTables');
});



Route::prefix('public')->name('public.')->group(function () {
    Route::get('/menu', [CustomerMenuController::class, 'index'])->name('menu');
    Route::get('/menu/{menu}', [CustomerMenuController::class, 'show'])->name('menu.show');
    Route::get('/kategori', [CustomerMenuController::class, 'kategori'])->name('kategori');
});

require __DIR__ . '/auth.php';
