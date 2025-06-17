<?php

namespace App\Http\Controllers\Kitchen;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
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




}
