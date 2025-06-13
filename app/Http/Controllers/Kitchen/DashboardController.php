<?php

namespace App\Http\Controllers\Kitchen;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;

class DashboardController extends Controller {
    public function index() {
        // Pesanan yang masuk (antrian dan diproses)
        $pesananMasuk = Pesanan::with(['meja', 'detailPesanan.menu', 'detailPesanan.metodeMasak'])
            ->whereIn('status_pesanan', ['antrian', 'diproses'])
            ->orderBy('waktu_pesanan', 'asc')
            ->get();

        // Pesanan yang sudah selesai (untuk riwayat)
        $pesananSiap = Pesanan::with(['meja'])
            ->where('status_pesanan', 'selesai')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return view('kitchen.dashboard', compact('pesananMasuk', 'pesananSiap'));
    }

    // API endpoint untuk mendapatkan pesanan baru (untuk AJAX)
    public function getNewOrders() {
        $pesananMasuk = Pesanan::with(['meja', 'detailPesanan.menu', 'detailPesanan.metodeMasak'])
            ->whereIn('status_pesanan', ['antrian', 'diproses'])
            ->orderBy('waktu_pesanan', 'asc')
            ->get();

        $html = view('kitchen.partial.pesanan-cards', compact('pesananMasuk'))->render();

        return response()->json(['html' => $html]);
    }

    // Update status pesanan
    public function updateStatus(Request $request, Pesanan $pesanan) {
        $validated = $request->validate([
            'status_pesanan' => 'required|in:diproses,selesai'
        ]);

        $pesanan->update($validated);

        // Jika untuk web request, redirect dengan flash message
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status pesanan berhasil diperbarui'
            ]);
        }

        $message = $validated['status_pesanan'] === 'diproses'
            ? 'Pesanan mulai dimasak'
            : 'Pesanan selesai dimasak';

        return redirect()->back()->with('success', $message);
    }

    // Tampilkan detail pesanan

    public function showPesanan(Pesanan $pesanan) {
        $pesanan->load(['meja', 'detailPesanan.menu.kategori', 'detailPesanan.metodeMasak']);

        if (request()->expectsJson()) {
            return response()->json([
                'pesanan_id' => $pesanan->pesanan_id,
                'waktu_pesanan' => $pesanan->waktu_pesanan,
                'status_pesanan' => $pesanan->status_pesanan,
                'total_harga' => $pesanan->total_harga, // Corrected: was 'total'
                'meja' => [
                    'nomor_meja' => $pesanan->meja->nomor_meja
                ],
                'detail_pesanan' => $pesanan->detailPesanan->map(function ($detail) {
                    return [
                        'jumlah' => $detail->jumlah,
                        'subtotal' => $detail->subtotal, // Corrected: was 'harga'
                        'catatan' => $detail->catatan,
                        'menu' => [
                            'nama_menu' => $detail->menu->nama_menu,
                            'kategori' => $detail->menu->kategori->nama_kategori ?? null
                        ],
                        'metode_masak' => $detail->metodeMasak ? [
                            'nama_metode' => $detail->metodeMasak->nama_metode
                        ] : null
                    ];
                })
            ]);
        }

        // If not a JSON request, return the full page view
        return view('kitchen.detail', compact('pesanan'));
    }
}
