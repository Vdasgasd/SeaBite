<?php

namespace App\Http\Controllers\Customer;

use App\Models\Meja;
use App\Models\Pesanan;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard untuk pelanggan atau tamu.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $reservasiTerakhir = null;
        $riwayatPesanan = collect();

        if ($user) {
            $reservasiTerakhir = Reservasi::where('user_id', $user->id)
                ->with('meja')
                ->latest('waktu_reservasi')
                ->first();

            $riwayatPesanan = Pesanan::where('user_id', $user->id)
                ->whereIn('status_pesanan', ['selesai', 'dibayar'])
                ->with('meja')
                ->orderByDesc('waktu_pesanan')
                ->paginate(5);
        }

        return view('customer.dashboard', [
            'user' => $user,
            'pesananAktif' => $this->getPesananAktifFor($request),
            'reservasiTerakhir' => $reservasiTerakhir,
            'riwayatPesanan' => $riwayatPesanan,
            'daftarMejaTersedia' => Meja::where('status', 'tersedia')->orderBy('kapasitas')->get(),
        ]);
    }

    /**
     * Mendapatkan status pesanan aktif via API untuk polling.
     */
    public function getStatusPesananAktif(Request $request)
    {
        $pesananAktif = $this->getPesananAktifFor($request);

        if ($pesananAktif) {
            $statusClass = match ($pesananAktif->status_pesanan) {
                'antrian' => 'bg-yellow-100 text-yellow-800',
                'diproses' => 'bg-blue-100 text-blue-800',
                default => 'bg-gray-100 text-gray-800'
            };

            $statusHtml = "<span class='px-3 py-1 text-sm font-semibold rounded-full {$statusClass}'>"
                . ucfirst($pesananAktif->status_pesanan) . "</span>";

            return response()->json([
                'status' => 'found',
                'pesanan_id' => $pesananAktif->pesanan_id,
                'status_pesanan' => $pesananAktif->status_pesanan,
                'status_html' => $statusHtml
            ]);
        }

        // Cek apakah pesanan tamu baru saja selesai
        if (Auth::guest() && session()->has('pesanan_aktif_id')) {
            $pesananSelesai = Pesanan::where('pesanan_id', session('pesanan_aktif_id'))
                                     ->whereIn('status_pesanan', ['selesai', 'dibayar'])
                                     ->exists();
            if ($pesananSelesai) {
                session()->forget('pesanan_aktif_id');
            }
        }

        return response()->json(['status' => 'not_found']);
    }

    /**
     * Helper method untuk mengambil pesanan aktif (baik untuk user login maupun tamu).
     * Ini menghilangkan duplikasi kode.
     */
    private function getPesananAktifFor(Request $request): ?Pesanan
    {
        if ($user = $request->user()) {
            // Logika untuk user yang sudah login
            return Pesanan::where('user_id', $user->id)
                ->whereIn('status_pesanan', ['antrian', 'diproses'])
                ->with(['detailPesanan.menu', 'meja'])
                ->orderByDesc('waktu_pesanan')
                ->first();
        } else {
            // Logika untuk tamu (berdasarkan session)
            if (session()->has('pesanan_aktif_id')) {
                return Pesanan::where('pesanan_id', session('pesanan_aktif_id'))
                    ->whereIn('status_pesanan', ['antrian', 'diproses'])
                    ->with(['detailPesanan.menu', 'meja'])
                    ->first();
            }
        }

        return null;
    }
}
