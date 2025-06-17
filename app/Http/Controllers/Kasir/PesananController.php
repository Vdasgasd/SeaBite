<?php
namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Menu;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index()
    {

        $pesanan = Pesanan::with(['meja', 'detailPesanan.menu'])
            ->orderBy('waktu_pesanan', 'desc')
            ->paginate(15);

        return view('kasir.pesanan.index', compact('pesanan'));
    }


    public function show(Pesanan $pesanan)
    {
        $pesanan->load(['meja', 'detailPesanan.menu', 'detailPesanan.metodeMasak']);
        return view('kasir.pesanan.show', compact('pesanan'));
    }

    public function destroy(Pesanan $pesanan)
    {
        if ($pesanan->status_pesanan !== 'antrian') {
            return redirect()->back()
                ->with('error', 'Pesanan tidak dapat dibatalkan');
        }

        $pesanan->update(['status_pesanan' => 'dibatalkan']);
        return redirect()->route('kasir.pesanan.index')
            ->with('success', 'Pesanan berhasil dibatalkan');
    }
}
