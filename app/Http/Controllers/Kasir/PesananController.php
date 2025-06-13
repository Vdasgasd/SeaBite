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
        // Tampilkan semua pesanan
        $pesanan = Pesanan::with(['meja', 'detailPesanan.menu'])
            ->orderBy('waktu_pesanan', 'desc')
            ->paginate(15);

        return view('kasir.pesanan.index', compact('pesanan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'meja_id' => 'required|exists:meja,meja_id',
            'items' => 'required|array',
            'items.*.menu_id' => 'required|exists:menu,menu_id',
            'items.*.metode_masak_id' => 'nullable|exists:metode_masak,metode_id',
            'items.*.jumlah' => 'nullable|integer|min:1',
            'items.*.berat_gram' => 'nullable|numeric|min:0',
            'items.*.catatan' => 'nullable|string'
        ]);

        $pesanan = Pesanan::create([
            'meja_id' => $validated['meja_id'],
            'status_pesanan' => 'antrian'
        ]);

        $totalHarga = 0;

        foreach ($validated['items'] as $item) {
            $menu = Menu::findOrFail($item['menu_id']);

            // Hitung subtotal berdasarkan tipe harga
            if ($menu->tipe_harga === 'satuan') {
                $subtotal = $menu->harga * ($item['jumlah'] ?? 1);
            } else {
                $subtotal = ($menu->harga_per_100gr / 100) * ($item['berat_gram'] ?? 0);
            }

            // Tambahkan biaya metode masak jika ada
            if (isset($item['metode_masak_id'])) {
                $metodeMasak = \App\Models\MetodeMasak::find($item['metode_masak_id']);
                if ($metodeMasak) {
                    $subtotal += $metodeMasak->biaya_tambahan;
                }
            }

            DetailPesanan::create([
                'pesanan_id' => $pesanan->pesanan_id,
                'menu_id' => $item['menu_id'],
                'metode_masak_id' => $item['metode_masak_id'] ?? null,
                'jumlah' => $item['jumlah'] ?? null,
                'berat_gram' => $item['berat_gram'] ?? null,
                'catatan' => $item['catatan'] ?? null,
                'subtotal' => $subtotal
            ]);

            $totalHarga += $subtotal;
        }

        $pesanan->update(['total_harga' => $totalHarga]);

        return redirect()->route('kasir.pesanan.show', $pesanan)
            ->with('success', 'Pesanan berhasil dibuat');
    }

    public function show(Pesanan $pesanan)
    {
        $pesanan->load(['meja', 'detailPesanan.menu', 'detailPesanan.metodeMasak']);
        return view('kasir.pesanan.show', compact('pesanan'));
    }

    public function update(Request $request, Pesanan $pesanan)
    {
        if ($pesanan->status_pesanan !== 'antrian') {
            return redirect()->back()
                ->with('error', 'Pesanan tidak dapat diubah');
        }

        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.menu_id' => 'required|exists:menu,menu_id',
            'items.*.metode_masak_id' => 'nullable|exists:metode_masak,metode_id',
            'items.*.jumlah' => 'nullable|integer|min:1',
            'items.*.berat_gram' => 'nullable|numeric|min:0',
            'items.*.catatan' => 'nullable|string'
        ]);

        // Hapus detail pesanan lama
        $pesanan->detailPesanan()->delete();

        $totalHarga = 0;

        foreach ($validated['items'] as $item) {
            $menu = Menu::findOrFail($item['menu_id']);

            if ($menu->tipe_harga === 'satuan') {
                $subtotal = $menu->harga * ($item['jumlah'] ?? 1);
            } else {
                $subtotal = ($menu->harga_per_100gr / 100) * ($item['berat_gram'] ?? 0);
            }

            if (isset($item['metode_masak_id'])) {
                $metodeMasak = \App\Models\MetodeMasak::find($item['metode_masak_id']);
                if ($metodeMasak) {
                    $subtotal += $metodeMasak->biaya_tambahan;
                }
            }

            DetailPesanan::create([
                'pesanan_id' => $pesanan->pesanan_id,
                'menu_id' => $item['menu_id'],
                'metode_masak_id' => $item['metode_masak_id'] ?? null,
                'jumlah' => $item['jumlah'] ?? null,
                'berat_gram' => $item['berat_gram'] ?? null,
                'catatan' => $item['catatan'] ?? null,
                'subtotal' => $subtotal
            ]);

            $totalHarga += $subtotal;
        }

        $pesanan->update(['total_harga' => $totalHarga]);

        return redirect()->route('kasir.pesanan.show', $pesanan)
            ->with('success', 'Pesanan berhasil diupdate');
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
