<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Guest Controller
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingController;

// Admin Controllers
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\IkanController as AdminIkanController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\MejaController as AdminMejaController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\KategoriController as AdminKategoriController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MetodeMasakController as AdminMetodeMasakController;

// Kitchen Controllers
use App\Http\Controllers\Kitchen\PesananController as KitchenPesananController;
use App\Http\Controllers\Kitchen\DashboardController as KitchenDashboardController;

// Kasir Controllers
use App\Http\Controllers\Kasir\InvoiceController as KasirInvoiceController;
use App\Http\Controllers\Kasir\PesananController as KasirPesananController;
use App\Http\Controllers\Kasir\ReservasiController as KasirReservasiController;
use App\Http\Controllers\Kasir\DashboardController as KasirDashboardController;

// Customer Controllers
use App\Http\Controllers\Customer\MenuController as CustomerMenuController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\ReservasiController as CustomerReservasiController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;


Route::get('/', [LandingController::class, 'landing'])->name('landing');
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');

// ================= PERUBAHAN UTAMA DI SINI =================
// Route Dashboard untuk customer (tamu & login) dibuat publik
Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');
// Route API untuk cek status pesanan juga dibuat publik
Route::get('/api/customer/pesanan-aktif', [CustomerDashboardController::class, 'getStatusPesananAktif'])->name('api.customer.pesanan.status');
// ==========================================================

// Redirect dashboard berdasarkan role (HANYA UNTUK YANG SUDAH LOGIN)
Route::get('/dashboard-redirect', function () { // URL diubah agar tidak konflik
    $user = Auth::user();

    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'kitchen':
            return redirect()->route('kitchen.dashboard');
        case 'kasir':
            return redirect()->route('kasir.dashboard');
        case 'cust':
            // Langsung arahkan ke dashboard customer, bukan redirect loop
            return redirect()->route('customer.dashboard');
        default:
            // Fallback jika role tidak dikenal
            return redirect('/');
    }
})->middleware(['auth'])->name('dashboard'); // Nama 'dashboard' ini untuk redirect setelah login

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Admin Routes (Tidak ada perubahan)
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


// Route untuk Proses Pemesanan Pelanggan (Tidak ada perubahan, ini sudah benar)
Route::prefix('pesan')->name('order.')->group(function () {
    Route::get('/{meja:nomor_meja}', [CustomerOrderController::class, 'show'])->name('show');
    Route::post('/{meja:nomor_meja}/add', [CustomerOrderController::class, 'addToCart'])->name('cart.add');
    Route::get('/{meja:nomor_meja}/cart', [CustomerOrderController::class, 'viewCart'])->name('cart.view');
    Route::post('/{meja:nomor_meja}/remove', [CustomerOrderController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/{meja:nomor_meja}/place', [CustomerOrderController::class, 'placeOrder'])->name('place');
    Route::get('/sukses/{pesanan_id}', [CustomerOrderController::class, 'success'])->name('success');
});



// Kitchen Routes - Update yang sudah ada
Route::middleware(['auth', 'role:kitchen'])->prefix('kitchen')->name('kitchen.')->group(function () {
    // Dashboard Kitchen
    Route::get('/dashboard', [KitchenDashboardController::class, 'index'])->name('dashboard');

    // API Routes untuk AJAX
    Route::get('/api/new-orders', [KitchenDashboardController::class, 'getNewOrders'])->name('api.getNewOrders');

    // Pesanan Routes
    Route::prefix('pesanan')->name('pesanan.')->group(function () {
        // API endpoints untuk mobile/AJAX
        Route::get('/', [KitchenPesananController::class, 'index'])->name('index');
        Route::get('/cooking', [KitchenPesananController::class, 'cooking'])->name('cooking');

        // Detail pesanan - untuk AJAX modal
        Route::get('/{pesanan}', [KitchenDashboardController::class, 'showPesanan'])->name('show');

        // Update status pesanan
        Route::patch('/{pesanan}/status', [KitchenDashboardController::class, 'updateStatus'])->name('updateStatus');
        Route::patch('/{pesanan}/ready', [KitchenPesananController::class, 'markAsReady'])->name('markAsReady');
    });

    // View Routes
    Route::get('/cooking-orders', function () {
        $pesananCooking = \App\Models\Pesanan::with(['meja', 'detailPesanan.menu', 'detailPesanan.metodeMasak'])
            ->where('status_pesanan', 'diproses')
            ->orderBy('updated_at', 'asc')
            ->get();

        return view('kitchen.cooking-orders', compact('pesananCooking'));
    })->name('cooking-orders');
});


// Kasir Routes (Tidak ada perubahan)
Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/dashboard', [KasirDashboardController::class, 'index'])->name('dashboard');
    Route::resource('pesanan', KasirPesananController::class);
    Route::resource('invoice', KasirInvoiceController::class)->only(['index', 'store', 'show']);
    Route::resource('reservasi', KasirReservasiController::class);
});


// Customer Routes (KHUSUS UNTUK YANG SUDAH LOGIN)
Route::middleware(['auth', 'role:cust'])->prefix('customer')->name('customer.')->group(function () {

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


// Public Routes (Tidak ada perubahan)
Route::prefix('public')->name('public.')->group(function () {
    Route::get('/menu', [CustomerMenuController::class, 'index'])->name('menu');
    Route::get('/menu/{menu}', [CustomerMenuController::class, 'show'])->name('menu.show');
    Route::get('/kategori', [CustomerMenuController::class, 'kategori'])->name('kategori');
});

require __DIR__ . '/auth.php';
