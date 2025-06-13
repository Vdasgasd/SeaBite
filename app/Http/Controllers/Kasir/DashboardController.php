<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Reservasi;
use App\Models\Invoice;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $pesananSelesai = Pesanan::with(['meja', 'detailPesanan.menu'])
            ->where('status_pesanan', 'selesai')
            ->orderBy('waktu_pesanan', 'desc')
            ->paginate(10);

            $totalPesananHariIni = Pesanan::whereDate('waktu_pesanan', today())->count();
            $totalInvoiceHariIni = Invoice::whereDate('created_at', today())->count();

            return view('kasir.dashboard', [
                'user' => $request->user(),
                'pesananSelesai' => $pesananSelesai,
            'totalPesananHariIni' => $totalPesananHariIni,
            'totalInvoiceHariIni' => $totalInvoiceHariIni,
        ]);
    }
}
