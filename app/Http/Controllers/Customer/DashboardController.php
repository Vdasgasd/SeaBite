<?php

namespace App\Http\Controllers\Customer;

use App\Models\Meja;
use App\Models\Pesanan;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller {
    public function index(Request $request) {
        $user = $request->user();

        $pesananAktif = null;
        $riwayatPesanan = collect();

        if ($user) {
            $mejaIds = $user->reservasi()->pluck('meja_id');
            $pesananAktif = Pesanan::where(function ($query) use ($mejaIds, $user) {
                $query->whereIn('meja_id', $mejaIds)
                    ->orWhere('user_id', $user->id);
            })
                ->whereIn('status_pesanan', ['antrian', 'diproses'])
                ->with(['detailPesanan.menu', 'meja'])
                ->orderByDesc('waktu_pesanan')
                ->first();

            $reservasiTerakhir = Reservasi::where('user_id', $user->id)
                ->with('meja')
                ->latest('waktu_reservasi')
                ->first();

            $riwayatPesanan = Pesanan::where(function ($query) use ($mejaIds, $user) {
                $query->whereIn('meja_id', $mejaIds)

                    ->orWhere('user_id', $user->id);
            })
                ->whereIn('status_pesanan', ['selesai', 'dibayar'])
                ->with('meja')
                ->orderByDesc('waktu_pesanan')
                ->paginate(5);
        } else {



            if (session()->has('pesanan_aktif_id')) {
                $pesananAktifId = session('pesanan_aktif_id');


                $pesananAktif = Pesanan::where('pesanan_id', $pesananAktifId)
                    ->whereIn('status_pesanan', ['antrian', 'diproses'])
                    ->with(['detailPesanan.menu', 'meja'])
                    ->first();


                if (!$pesananAktif) {

                    $pesananSelesai = Pesanan::where('pesanan_id', $pesananAktifId)
                        ->whereIn('status_pesanan', ['selesai', 'dibayar'])
                        ->exists();

                    if ($pesananSelesai) {
                        session()->forget('pesanan_aktif_id');
                    }
                }
            }


            $riwayatPesanan = collect();
            $reservasiTerakhir = collect();
        }


        $daftarMejaTersedia = Meja::where('status', 'tersedia')->orderBy('kapasitas')->get();

        return view('customer.dashboard', [
            'user' => $user,
            'reservasiTerakhir' => $reservasiTerakhir,
            'pesananAktif' => $pesananAktif,
            'riwayatPesanan' => $riwayatPesanan,
            'daftarMejaTersedia' => $daftarMejaTersedia,
            'isGuest' => !$user,
        ]);
    }


    public function getStatusPesananAktif(Request $request) {
        $user = $request->user();
        $pesananAktif = null;

        if ($user) {

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

            if (session()->has('pesanan_aktif_id')) {
                $pesananAktifId = session('pesanan_aktif_id');

                $pesananAktif = Pesanan::where('pesanan_id', $pesananAktifId)
                    ->whereIn('status_pesanan', ['antrian', 'diproses'])
                    ->with(['detailPesanan.menu', 'meja'])
                    ->first();
            }
        }

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

        return response()->json([
            'status' => 'not_found'
        ]);
    }
}
