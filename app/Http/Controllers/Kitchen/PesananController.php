<?php

namespace App\Http\Controllers\Kitchen;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller {
    public function index() {
        $pesanan = Pesanan::with(['meja', 'detailPesanan.menu', 'detailPesanan.metodeMasak'])
            ->whereIn('status_pesanan', ['antrian', 'diproses'])
            ->orderBy('waktu_pesanan', 'asc')
            ->get();

        return view('kitchen.pesanan.index', compact('pesanan'));
    }

    public function show(Pesanan $pesanan) {
        $pesanan->load(['meja', 'detailPesanan.menu', 'detailPesanan.metodeMasak']);
        return view('kitchen.pesanan.show', compact('pesanan'));
    }

    public function updateStatus(Request $request, Pesanan $pesanan) {
        $validated = $request->validate([
            'status_pesanan' => 'required|in:dimasak,selesai,dibatalkan'
        ]);

        $pesanan->update($validated);

        return redirect()->back()->with('success', 'Status pesanan diperbarui.');
    }

    public function cooking() {
        $pesanan = Pesanan::with(['meja', 'detailPesanan.menu', 'detailPesanan.metodeMasak'])
            ->where('status_pesanan', 'diproses')
            ->orderBy('waktu_pesanan', 'asc')
            ->get();

        return view('kitchen.pesanan.cooking', compact('pesanan'));
    }

    public function markAsReady(Pesanan $pesanan) {
        $pesanan->update(['status_pesanan' => 'selesai']);

        return redirect()->back()->with('success', 'Pesanan siap disajikan');
    }
}
