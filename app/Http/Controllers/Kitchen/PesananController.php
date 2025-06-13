<?php


namespace App\Http\Controllers\Kitchen;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index()
    {
        // Tampilkan pesanan yang baru atau sedang dimasak
        $pesanan = Pesanan::with(['meja', 'detailPesanan.menu', 'detailPesanan.metodeMasak'])
            ->whereIn('status_pesanan', ['antrian', 'diproses'])
            ->orderBy('waktu_pesanan', 'asc')
            ->get();

        return response()->json($pesanan);
    }

    public function updateStatus(Request $request, Pesanan $pesanan)
    {
        $validated = $request->validate([
            'status_pesanan' => 'required|in:dimasak,selesai,dibatalkan'
        ]);

        $pesanan->update($validated);
        return response()->json($pesanan);
    }

    public function show(Pesanan $pesanan)
    {
        $pesanan->load(['meja', 'detailPesanan.menu', 'detailPesanan.metodeMasak']);
        return response()->json($pesanan);
    }

    // Khusus untuk kitchen - lihat detail pesanan yang sedang dimasak
    public function cooking()
    {
        $pesanan = Pesanan::with(['meja', 'detailPesanan.menu', 'detailPesanan.metodeMasak'])
            ->where('status_pesanan', 'diproses')
            ->orderBy('waktu_pesanan', 'asc')
            ->get();

        return response()->json($pesanan);
    }

    // Tandai pesanan sebagai selesai dimasak
    public function markAsReady(Pesanan $pesanan)
    {
        $pesanan->update(['status_pesanan' => 'selesai']);
        return response()->json(['message' => 'Pesanan siap disajikan']);
    }
}
