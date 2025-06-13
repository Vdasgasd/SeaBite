<?php

namespace App\Http\Controllers\Customer;

use App\Models\Meja;
use App\Models\Pesanan;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // <-- TAMBAHKAN USE STATEMENT INI

class DashboardController extends Controller {
    public function index(Request $request) {
        $user = $request->user(); // Akan null jika guest
        $pesananAktif = null;
        $riwayatPesanan = collect();

        if ($user) {
            // === USER YANG LOGIN ===

            // Ambil semua meja yang direservasi oleh user yang login
            $mejaIds = $user->reservasi()->pluck('meja_id');

            // Ambil pesanan aktif yang dibuat oleh user ini
            // Baik dari reservasi maupun pesanan langsung
            $pesananAktif = Pesanan::where(function ($query) use ($mejaIds, $user) {
                $query->whereIn('meja_id', $mejaIds) // dari reservasi
                    ->orWhere('user_id', $user->id); // pesanan langsung user
            })
                ->whereIn('status_pesanan', ['antrian', 'diproses']) // sesuai enum migration
                ->with(['detailPesanan.menu', 'meja'])
                ->orderByDesc('waktu_pesanan')
                ->first();

            $reservasiTerakhir = Reservasi::where('user_id', $user->id)
                                ->with('meja') // Eager load untuk detail meja
                                ->latest('waktu_reservasi') // Ambil yang paling baru/mendatang
                                ->first();

            // Ambil riwayat pesanan selesai dari user ini
            $riwayatPesanan = Pesanan::where(function ($query) use ($mejaIds, $user) {
                $query->whereIn('meja_id', $mejaIds) // dari reservasi
                    ->orWhere('user_id', $user->id); // pesanan langsung user
            })
                ->whereIn('status_pesanan', ['selesai', 'dibayar'])
                ->with('meja')
                ->orderByDesc('waktu_pesanan')
                ->paginate(5);
        } else {
            // === GUEST USER (TIDAK LOGIN) ===

            // Cek apakah ada pesanan aktif di session
            if (session()->has('pesanan_aktif_id')) {
                $pesananAktifId = session('pesanan_aktif_id');

                // Ambil pesanan aktif berdasarkan ID yang disimpan di session
                $pesananAktif = Pesanan::where('pesanan_id', $pesananAktifId)
                    ->whereIn('status_pesanan', ['antrian', 'diproses']) // sesuai enum migration
                    ->with(['detailPesanan.menu', 'meja'])
                    ->first();

                // Jika pesanan sudah selesai/dibayar, hapus dari session
                if (!$pesananAktif) {
                    // Cek apakah pesanan ada tapi sudah selesai
                    $pesananSelesai = Pesanan::where('pesanan_id', $pesananAktifId)
                        ->whereIn('status_pesanan', ['selesai', 'dibayar'])
                        ->exists();

                    if ($pesananSelesai) {
                        session()->forget('pesanan_aktif_id');
                    }
                }
            }

            // Untuk guest, tidak ada riwayat pesanan
            $riwayatPesanan = collect();
            $reservasiTerakhir = collect();

        }

        // Daftar meja tersedia (untuk semua user)
        $daftarMejaTersedia = Meja::where('status', 'tersedia')->orderBy('kapasitas')->get();

        return view('customer.dashboard', [
            'user' => $user,
            'reservasiTerakhir' => $reservasiTerakhir,
            'pesananAktif' => $pesananAktif,
            'riwayatPesanan' => $riwayatPesanan,
            'daftarMejaTersedia' => $daftarMejaTersedia,
            'isGuest' => !$user, // flag untuk view
        ]);
    }

    // Method untuk API status pesanan (juga harus support guest)
    public function getStatusPesananAktif(Request $request) {
        $user = $request->user();
        $pesananAktif = null;

        if ($user) {
            // User yang login
            $mejaIds = $user->reservasi()->pluck('meja_id');

            $pesananAktif = Pesanan::where(function ($query) use ($mejaIds, $user) {
                $query->whereIn('meja_id', $mejaIds)
                    ->orWhere('user_id', $user->id);
            })
                ->whereIn('status_pesanan', ['antrian', 'diproses'])
                ->with(['detailPesanan.menu', 'meja'])
                ->orderByDesc('waktu_pesanan')
                ->first();
        } else {
            // Guest user
            if (session()->has('pesanan_aktif_id')) {
                $pesananAktifId = session('pesanan_aktif_id');

                $pesananAktif = Pesanan::where('pesanan_id', $pesananAktifId)
                    ->whereIn('status_pesanan', ['antrian', 'diproses'])
                    ->with(['detailPesanan.menu', 'meja'])
                    ->first();
            }
        }

        if ($pesananAktif) {
            // Generate status badge HTML
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

        return response()->json([
            'status' => 'not_found'
        ]);
    }
}
