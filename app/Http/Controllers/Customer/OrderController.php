<?php

namespace App\Http\Controllers\Customer;

use App\Models\Meja;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\Kategori;
use App\Models\MetodeMasak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller {
    public function show(Meja $meja) {
        if ($meja->status !== 'tersedia') {
            return view('order.failed', compact('meja'));
        }

        $menus = Menu::with('kategori')->get();
        $kategoris = Kategori::all();
        $metodeMasaks = MetodeMasak::all();

        $cart = Session::get('cart_' . $meja->nomor_meja, []);

        return view('order.show', compact('meja', 'menus', 'kategoris', 'metodeMasaks', 'cart'));
    }
    public function addToCart(Request $request, Meja $meja) {
        $request->validate([
            'menu_id' => 'required|exists:menu,menu_id',
            'jumlah' => 'required|integer|min:1',
            'berat_gram' => 'nullable|numeric|min:0',
            'metode_masak_id' => 'nullable|exists:metode_masak,metode_id',
            'catatan' => 'nullable|string',
        ]);

        $menu = Menu::find($request->menu_id);
        $metodeMasak = $request->metode_masak_id ? MetodeMasak::find($request->metode_masak_id) : null;

        $subtotal = 0;
        if ($menu->tipe_harga == 'satuan') {
            $subtotal = $menu->harga * $request->jumlah;
        } else {
            $harga_per_gram = $menu->harga_per_100gr / 100;
            $subtotal = $harga_per_gram * $request->berat_gram;
        }

        if ($metodeMasak) {
            $subtotal += $metodeMasak->biaya_tambahan;
        }

        $cartKey = 'cart_' . $meja->nomor_meja;
        $cart = Session::get($cartKey, []);

        $cartItemId = uniqid();

        $cart[$cartItemId] = [
            'menu_id' => $menu->menu_id,
            'nama_menu' => $menu->nama_menu,
            'jumlah' => $request->jumlah,
            'berat_gram' => $request->berat_gram,
            'metode_masak_id' => $request->metode_masak_id,
            'nama_metode' => $metodeMasak ? $metodeMasak->nama_metode : null,
            'catatan' => $request->catatan,
            'subtotal' => $subtotal,
        ];

        Session::put($cartKey, $cart);

        return redirect()->route('order.show', $meja->nomor_meja)->with('success', 'Menu berhasil ditambahkan ke keranjang.');
    }

    public function removeFromCart(Request $request, Meja $meja) {
        $request->validate(['cart_item_id' => 'required|string']);

        $cartKey = 'cart_' . $meja->nomor_meja;
        $cart = Session::get($cartKey, []);

        if (isset($cart[$request->cart_item_id])) {
            unset($cart[$request->cart_item_id]);
            Session::put($cartKey, $cart);
        }

        return redirect()->route('order.show', $meja->nomor_meja)->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function placeOrder(Request $request, Meja $meja) {
        $cartKey = 'cart_' . $meja->nomor_meja;
        $cart = Session::get($cartKey, []);

        if (empty($cart)) {
            return redirect()->route('order.show', $meja->nomor_meja)->with('error', 'Keranjang Anda kosong.');
        }

        DB::beginTransaction();
        try {
            $totalHarga = array_sum(array_column($cart, 'subtotal'));

            $pesanan = Pesanan::create([
                'meja_id' => $meja->meja_id,
                'user_id' => Auth::id(), // Akan null jika guest
                'waktu_pesanan' => now(),
                'status_pesanan' => 'antrian',
                'total_harga' => $totalHarga,
            ]);

            foreach ($cart as $item) {
                $pesanan->detailPesanan()->create([
                    'menu_id' => $item['menu_id'],
                    'metode_masak_id' => $item['metode_masak_id'],
                    'jumlah' => $item['jumlah'],
                    'berat_gram' => $item['berat_gram'],
                    'catatan' => $item['catatan'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            $meja->status = 'terisi';
            $meja->save();

            DB::commit();

            Session::forget($cartKey);

            // --- SIMPAN ID PESANAN UNTUK GUEST ---
            // Simpan pesanan ID di session untuk tracking (berlaku untuk guest maupun user)
            if (!Auth::check()) {
                session(['pesanan_aktif_id' => $pesanan->pesanan_id]);
            }
            // --- AKHIR ---

            return redirect()->route('customer.dashboard')->with('success', 'Pesanan Anda berhasil dibuat dan sedang diproses!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal membuat pesanan: ' . $e->getMessage());

            return redirect()->route('order.show', $meja->nomor_meja)->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    // OPTIONAL: Method untuk menghapus session pesanan ketika selesai/dibayar
    public function clearGuestOrder($pesananId) {
        if (session('pesanan_aktif_id') == $pesananId) {
            session()->forget('pesanan_aktif_id');
        }
    }
    public function success($pesanan_id) {
        $pesanan = Pesanan::with('detailPesanan.menu')->findOrFail($pesanan_id);
        return view('order.success', compact('pesanan'));
    }
}
