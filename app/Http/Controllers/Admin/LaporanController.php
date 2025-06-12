<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman laporan penjualan dengan filter tanggal.
     */
    public function penjualan(Request $request)
    {
        // Tetapkan tanggal default: awal dan akhir bulan ini
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Query dasar untuk invoice
        $query = Invoice::query()->with('kasir', 'pesanan');

        // Terapkan filter tanggal jika ada
        $query->whereBetween('waktu_pembayaran', [$startDate, Carbon::parse($endDate)->endOfDay()]);

        // Ambil data invoice dengan paginasi
        $invoices = $query->latest('waktu_pembayaran')->paginate(15);

        // Hitung total pendapatan untuk rentang tanggal yang dipilih
        $totalPendapatan = $query->sum('total_bayar');
        $jumlahTransaksi = $query->count();

        return view('admin.laporan.index', compact(
            'invoices',
            'totalPendapatan',
            'jumlahTransaksi',
            'startDate',
            'endDate'
        ));
    }

}
