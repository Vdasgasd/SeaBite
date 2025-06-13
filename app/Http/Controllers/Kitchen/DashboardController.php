<?php

namespace App\Http\Controllers\Kitchen;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;

class DashboardController extends Controller {
    public function index() {

        $pesananMasuk = Pesanan::with(['meja', 'detailPesanan.menu', 'detailPesanan.metodeMasak'])
            ->whereIn('status_pesanan', ['antrian', 'diproses'])
            ->orderBy('waktu_pesanan', 'asc')
            ->get();


        $pesananSiap = Pesanan::with(['meja'])
            ->where('status_pesanan', 'selesai')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return view('kitchen.dashboard', compact('pesananMasuk', 'pesananSiap'));
    }


    public function updateStatus(Request $request, Pesanan $pesanan) {
        $validated = $request->validate([
            'status_pesanan' => 'required|in:diproses,selesai'
        ]);

        $pesanan->update($validated);


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



    public function showPesanan(Pesanan $pesanan) {
        $pesanan->load(['meja', 'detailPesanan.menu.kategori', 'detailPesanan.metodeMasak']);

        if (request()->expectsJson()) {
            return response()->json([
                'pesanan_id' => $pesanan->pesanan_id,
                'waktu_pesanan' => $pesanan->waktu_pesanan,
                'status_pesanan' => $pesanan->status_pesanan,
                'total_harga' => $pesanan->total_harga,
                'meja' => [
                    'nomor_meja' => $pesanan->meja->nomor_meja
                ],
                'detail_pesanan' => $pesanan->detailPesanan->map(function ($detail) {
                    return [
                        'jumlah' => $detail->jumlah,
                        'subtotal' => $detail->subtotal,
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


        return view('kitchen.detail', compact('pesanan'));
    }
}
