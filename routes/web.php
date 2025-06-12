<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Admin Controllers
use App\Http\Controllers\MenuController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\IkanController as AdminIkanController;
use App\Http\Controllers\Admin\MejaController as AdminMejaController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;

// Kitchen Controllers
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Customer\MenuController as CustomerMenuController;

// Kasir Controllers
use App\Http\Controllers\Kasir\InvoiceController as KasirInvoiceController;
use App\Http\Controllers\Kasir\PesananController as KasirPesananController;
use App\Http\Controllers\Admin\KategoriController as AdminKategoriController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

// Customer Controllers
use App\Http\Controllers\Kasir\DashboardController as KasirDashboardController;
use App\Http\Controllers\Kasir\ReservasiController as KasirReservasiController;
use App\Http\Controllers\Kitchen\PesananController as KitchenPesananController;
use App\Http\Controllers\Admin\MetodeMasakController as AdminMetodeMasakController;
use App\Http\Controllers\Kitchen\DashboardController as KitchenDashboardController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\ReservasiController as CustomerReservasiController;

Route::get('/', [LandingController::class, 'landing'])->name('landing');
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');


// Redirect dashboard berdasarkan role
Route::get('/dashboard', function () {
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
            return view('dashboard');
    }
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
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
});

// Kitchen Routes
Route::middleware(['auth', 'role:kitchen'])->prefix('kitchen')->name('kitchen.')->group(function () {
    Route::get('/dashboard', [KitchenDashboardController::class, 'index'])->name('dashboard');

    // Pesanan Management untuk Kitchen
    Route::get('/pesanan', [KitchenPesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{pesanan}', [KitchenPesananController::class, 'show'])->name('pesanan.show');
    Route::patch('/pesanan/{pesanan}/status', [KitchenPesananController::class, 'updateStatus'])->name('pesanan.updateStatus');

    // Khusus Kitchen
    Route::get('/cooking', [KitchenPesananController::class, 'cooking'])->name('pesanan.cooking');
    Route::patch('/pesanan/{pesanan}/ready', [KitchenPesananController::class, 'markAsReady'])->name('pesanan.ready');
});

// Kasir Routes
Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/dashboard', [KasirDashboardController::class, 'index'])->name('dashboard');

    // Pesanan Management untuk Kasir
    Route::resource('pesanan', KasirPesananController::class);

    // Invoice Management
    Route::resource('invoice', KasirInvoiceController::class)->only(['index', 'store', 'show']);

    // Reservasi Management untuk Kasir
    Route::resource('reservasi', KasirReservasiController::class);
});

// Customer Routes
Route::middleware(['auth', 'role:cust'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');

    // Menu untuk Customer (read-only)
    Route::get('/menu', [CustomerMenuController::class, 'index'])->name('menu.index');
    Route::get('/menu/{menu}', [CustomerMenuController::class, 'show'])->name('menu.show');
    Route::get('/kategori', [CustomerMenuController::class, 'kategori'])->name('kategori');

    // Reservasi untuk Customer
    Route::get('/reservasi', [CustomerReservasiController::class, 'index'])->name('reservasi.index');
    Route::post('/reservasi', [CustomerReservasiController::class, 'store'])->name('reservasi.store');
    Route::get('/reservasi/{reservasi}', [CustomerReservasiController::class, 'show'])->name('reservasi.show');
    Route::get('/available-tables', [CustomerReservasiController::class, 'availableTables'])->name('availableTables');
});

// Public Routes (untuk customer yang belum login)
Route::prefix('public')->name('public.')->group(function () {
    Route::get('/menu', [CustomerMenuController::class, 'index'])->name('menu');
    Route::get('/menu/{menu}', [CustomerMenuController::class, 'show'])->name('menu.show');
    Route::get('/kategori', [CustomerMenuController::class, 'kategori'])->name('kategori');
});

require __DIR__ . '/auth.php';
